<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midtrans CSV Manager - Yajra Datatables</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background-color: #f8fafc; 
            color: #1e293b;
        }
        .card {
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border-radius: 20px;
        }
        .header-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: -50px;
        }
        .navbar {
            background: #4338ca !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #6366f1;
            border: none;
            border-radius: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #4f46e5;
        }
        .btn-outline-success {
            border-radius: 12px;
            border-width: 2px;
            font-weight: 600;
        }
        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 15px;
            border: none;
        }
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        #drop-zone {
            border-radius: 16px;
            background: #f8fafc;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-0">
        <div class="container">
            <a class="navbar-brand" href="/">Midtrans Manager</a>
            <div class="navbar-nav">
                <a class="nav-link active" href="/">Transactions</a>
                <a class="nav-link" href="/companies">Companies</a>
                <a class="nav-link" href="/users">Users</a>
            </div>
            <div class="navbar-nav ms-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="header-gradient">
        <div class="container text-center">
            <h1 class="fw-bold">Midtrans CSV Manager</h1>
            <p class="lead text-white-50">Upload and sync Midtrans transaction reports with ease.</p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-11">
                
                <!-- Filter & Export Card -->
                <div class="card mb-4 mt-5">
                    <div class="card-body p-4">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Pilih Company</label>
                                <select id="filter_company_id" class="form-select">
                                    <option value="">Semua Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Dari Tanggal</label>
                                <input type="date" id="from_date" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Hingga Tanggal</label>
                                <input type="date" id="to_date" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="filter" class="btn btn-primary w-100 mb-0">Filter</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="export" class="btn btn-outline-success w-100 mb-0">Export CSV</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Import Card -->
                <div class="card mb-4">
                    <div class="card-body p-4 text-center">
                        <form action="{{ route('transactions.import') }}" method="POST" enctype="multipart/form-data" id="import-form">
                            @csrf
                            
                            <div class="row justify-content-center mb-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Target Company</label>
                                    <select name="company_id" id="import_company_id" class="form-select select2" required>
                                        <option value="">-- Pilih Company --</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold">Pilih File CSV</label>
                                    <input type="file" name="csv_file" class="form-control" id="csv_file" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload me-2" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
                                </svg>
                                Proses Import Data
                            </button>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mx-auto" style="max-width: 600px;" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="p-4 border-2 border-dashed rounded-3 bg-light" 
                                 id="drop-zone"
                                 style="border-style: dashed !important; border-color: #dee2e6 !important; transition: all 0.2s ease;">
                                <p class="mb-0 fw-semibold text-muted">Atau Tarik File CSV ke sini</p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Table Card -->
                <div class="card p-4">
                    <div class="table-responsive">
                        <table class="table table-hover w-100" id="transactions-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Order ID</th>
                                    <th>Company</th>
                                    <th>Nama Customer</th>
                                    <th>Channel</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Waktu Transaksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript">
        $(function () {
            var table = $('#transactions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/",
                    data: function (d) {
                        d.company_id = $('#filter_company_id').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'company_name', name: 'company_name'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'channel', name: 'channel'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                    {data: 'transaction_time', name: 'transaction_time'},
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
                }
            });

            $('#filter').click(function(){
                table.draw();
            });

            $('#export').click(function(){
                var company_id = $('#filter_company_id').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                window.location.href = "{{ route('transactions.export') }}?company_id=" + company_id + "&from_date=" + from_date + "&to_date=" + to_date;
            });

            // Drag and Drop Logic
            var dropZone = document.getElementById('drop-zone');
            var fileInput = document.getElementById('csv_file');

            ['dragover', 'dragenter'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropZone.classList.add('bg-primary', 'bg-opacity-10', 'border-primary');
                });
            });

            ['dragleave', 'dragend', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('bg-primary', 'bg-opacity-10', 'border-primary');
                });
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                const files = e.dataTransfer.files;
                if (files.length) {
                    fileInput.files = files;
                }
            });
        });
    </script>
</body>
</html>
