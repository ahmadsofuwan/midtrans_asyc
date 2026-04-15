<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(10);
        return view('companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Company::create($request->all());
        return back()->with('success', 'Company berhasil ditambahkan.');
    }

    public function update(Request $request, Company $company)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $company->update($request->all());
        return back()->with('success', 'Company berhasil diperbarui.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Company berhasil dihapus.');
    }
}
