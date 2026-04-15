<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::query();

            // Apply Filters
            if ($request->company_id) {
                $data->where('company_id', $request->company_id);
            }
            if ($request->from_date) {
                $data->where('settlement_time', '>=', $request->from_date . ' 00:00:00');
            }
            if ($request->to_date) {
                $data->where('settlement_time', '<=', $request->to_date . ' 23:59:59');
            }

            $data->with('company')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company_name', function($row) {
                    return $row->company ? $row->company->name : '-';
                })
                ->editColumn('amount', function($row) {
                    return 'Rp' . number_format($row->amount, 0, ',', '.');
                })
                ->editColumn('transaction_time', function($row) {
                    return $row->transaction_time ?? '-';
                })
                ->editColumn('status', function($row) {
                    $status = strtolower($row->status);
                    $badge = 'bg-secondary';
                    if ($status === 'settlement' || $status === 'success') $badge = 'bg-success';
                    elseif ($status === 'pending' || $status === 'in_progress') $badge = 'bg-warning text-dark';
                    elseif (in_array($status, ['cancel', 'expire', 'deny', 'failed'])) $badge = 'bg-danger';
                    
                    return '<span class="badge ' . $badge . '">' . strtoupper($row->status) . '</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        $companies = Company::all();
        return view('transactions.index', compact('companies'));
    }

    public function export(Request $request)
    {
        $query = Transaction::query();

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->from_date) {
            $query->where('settlement_time', '>=', $request->from_date . ' 00:00:00');
        }
        if ($request->to_date) {
            $query->where('settlement_time', '<=', $request->to_date . ' 23:59:59');
        }

        $transactions = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="transaction-export-'.now()->format('Y-m-d').'.csv"',
        ];

        $columns = [
            'Date Created', 'Order ID', 'Transaction Type', 'Channel', 'Status', 'Reference Id', 
            'Amount', 'Total Fee', 'Notes', 'Customer name', 'Customer e-mail', 'Customer mobile no.', 
            'Shipping address', 'Transaction time', 'Settlement time', 'Expiry time', 'Custom Field 1', 
            'Custom Field 2', 'Custom Field 3', 'POP Name', 'Payment provider reference ID', 
            'Invoice ID', 'Subscription ID', "Receiver's account number", 'Sender', 'Receiver', 'Settlement Date'
        ];

        $callback = function() use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->date_created, $tx->order_id, $tx->transaction_type, $tx->channel, $tx->status, $tx->reference_id,
                    $tx->amount, $tx->total_fee, $tx->notes, $tx->customer_name, $tx->customer_email, $tx->customer_mobile,
                    $tx->shipping_address, $tx->transaction_time, $tx->settlement_time, $tx->expiry_time, $tx->custom_field_1,
                    $tx->custom_field_2, $tx->custom_field_3, $tx->pop_name, $tx->payment_provider_ref_id, $tx->invoice_id,
                    $tx->subscription_id, $tx->receiver_account_number, $tx->sender, $tx->receiver, $tx->settlement_date
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'company_id' => 'required|exists:companies,id',
        ]);

        $file = $request->file('csv_file');
        $company_id = $request->company_id;
        $handle = fopen($file->getPathname(), 'r');
        
        // Read header
        $header = fgetcsv($handle);
        
        $columnMapping = [
            'Date Created' => 'date_created',
            'Order ID' => 'order_id',
            'Transaction Type' => 'transaction_type',
            'Channel' => 'channel',
            'Status' => 'status',
            'Reference Id' => 'reference_id',
            'Amount' => 'amount',
            'Total Fee' => 'total_fee',
            'Notes' => 'notes',
            'Customer name' => 'customer_name',
            'Customer e-mail' => 'customer_email',
            'Customer mobile no.' => 'customer_mobile',
            'Shipping address' => 'shipping_address',
            'Transaction time' => 'transaction_time',
            'Settlement time' => 'settlement_time',
            'Expiry time' => 'expiry_time',
            'Custom Field 1' => 'custom_field_1',
            'Custom Field 2' => 'custom_field_2',
            'Custom Field 3' => 'custom_field_3',
            'POP Name' => 'pop_name',
            'Payment provider reference ID' => 'payment_provider_ref_id',
            'Invoice ID' => 'invoice_id',
            'Subscription ID' => 'subscription_id',
            "Receiver's account number" => 'receiver_account_number',
            'Sender' => 'sender',
            'Receiver' => 'receiver',
            'Settlement Date' => 'settlement_date',
        ];

        $count = 0;
        $dateColumns = ['date_created', 'transaction_time', 'settlement_time', 'expiry_time', 'settlement_date'];
        
        while (($row = fgetcsv($handle)) !== false) {
            $data = [];
            $data['company_id'] = $company_id;

            foreach ($header as $index => $colName) {
                if (isset($columnMapping[$colName])) {
                    $val = $row[$index] ?? null;
                    
                    // Convert empty strings to null
                    if ($val === '') {
                        $val = null;
                    }
                    
                    $dbKey = $columnMapping[$colName];
                    
                    // Handle numeric fields
                    if (in_array($dbKey, ['amount', 'total_fee']) && $val !== null) {
                        $val = (int) $val;
                    }
                    
                    // Handle date fields
                    if (in_array($dbKey, $dateColumns) && $val !== null) {
                        try {
                            $val = Carbon::parse($val);
                        } catch (\Exception $e) {
                            $val = null;
                        }
                    }
                    
                    $data[$dbKey] = $val;
                }
            }

            if (!empty($data['order_id'])) {
                Transaction::updateOrCreate(
                    ['order_id' => $data['order_id'], 'company_id' => $company_id],
                    $data
                );
                $count++;
            }
        }

        fclose($handle);

        return back()->with('success', "$count data berhasil diimpor/diupdate.");
    }
}
