<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Companies - Midtrans Manager</title>
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8fafc; color: #1e293b; }
        .navbar { background: #4338ca !important; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .btn-primary { border-radius: 12px; font-weight: 600; background: #6366f1; border: none; }
        .form-control { border-radius: 12px; padding: 10px 15px; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">Midtrans Manager</a>
            <div class="navbar-nav">
                <a class="nav-link" href="/">Transactions</a>
                <a class="nav-link active" href="/companies">Companies</a>
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

    <div class="container text-center mb-5">
        <h1 class="fw-bold">Manage Companies</h1>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4">
                    <h5>Add New Company</h5>
                    <form action="{{ route('companies.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Company</button>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ $company->name }}</td>
                                    <td>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus company ini?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
