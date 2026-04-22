<!-- resources/views/invoice.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Invoice</title>
</head>
<body>
@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

<form action="{{ route('xero.invoice.create') }}" method="POST">
    @csrf
    <input type="text" name="customer_name" placeholder="Customer Name" required>
    <input type="text" name="product_description" placeholder="Product Description" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <input type="number" name="unit_amount" placeholder="Unit Amount" required>
    <button type="submit">Create Invoice</button>
</form>

<form action="{{ route('xero.payment.record') }}" method="POST">
    @csrf
    <input type="text" name="invoice_id" placeholder="Invoice ID" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <button type="submit">Record Payment</button>
</form>
</body>
</html>
