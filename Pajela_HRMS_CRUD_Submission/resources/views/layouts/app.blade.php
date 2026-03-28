<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HRMS</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f7fb;
            color: #2c3e50;
        }

        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar strong {
            font-size: 1.1rem;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .navbar a.active-link {
            background: rgba(255, 255, 255, 0.18);
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
        }

        .navbar a.active-link:hover {
            text-decoration: none;
        }

        .container {
            padding: 20px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 24px;
        }

        .page-header h2 {
            margin: 0;
            font-size: 1.75rem;
        }

        .subtitle {
            margin: 6px 0 0;
            color: #ecf0f1;
            opacity: 0.85;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #2980b9;
            color: white;
        }

        .btn-danger {
            background-color: #c0392b;
            color: white;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .data-table th,
        .data-table td {
            padding: 14px 16px;
            border: 1px solid #dfe6e9;
            text-align: left;
        }

        .data-table thead {
            background-color: #34495e;
            color: white;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-link {
            color: #2980b9;
            text-decoration: none;
            font-weight: 600;
        }

        .text-link:hover {
            text-decoration: underline;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 18px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .form-group {
            display: grid;
            gap: 8px;
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 600;
            color: #34495e;
        }

        .form-input,
        .form-select {
            width: 90%;
            padding: 12px 14px;
            border: 1px solid #ced6e0;
            border-radius: 8px;
            font-size: 1rem;
            background: #fff;
            color: #2c3e50;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.12);
        }

        .form-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-top: 8px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #dff0d8;
            color: #2f6627;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }

        .field-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="container">
        @if (session('success'))
            <p style="color: green;">
                {{ session('success') }}
            </p>
        @endif

        @yield('content')
    </div>

</body>
</html>