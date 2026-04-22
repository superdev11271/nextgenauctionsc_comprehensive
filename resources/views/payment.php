<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Process Payment</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <h1>Process Payment</h1>
        <form action="{{ url('/xero/payment') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="invoice_id">Invoice ID:</label>
                <input type="text" class="form-control" id="invoice_id" name="invoice_id" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            </div>
            <button type="submit" class="btn btn-primary">Process Payment</button>
        </form>
        @if (session('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
        @endif
    </div>
</body>

</html>
