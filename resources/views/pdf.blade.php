<?php

?>

<!-- <div>quotation_date: {{ $record->quotation_date }}</div>
<div>customer_id: {{ $record->customer_id }}</div>
<div>quotation_date: {{ $record->quotation_date }}</div>
<div>quote_status: {{ $record->quote_status }}</div>
<div>title: {{ $record->title }}</div> -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quotation Invoice</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
Absolutely, here's an HTML and Bootstrap 5 invoice template with a professional look:

index.html:
HTML

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Professional Invoice with Bootstrap 5</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlSQALhIjekNlfoxEEMkhrvpcwgONs2QQzTzub7P9zUke9DmAzizU7z6EjxGkEO" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="container py-4 border-bottom">
    <div class="row d-flex justify-content-between align-items-center">
      <div class="col-md-6">
        <img src="your_logo.png" alt="Company Logo" width="150">
      </div>
      <div class="col-md-6 text-end">
        <h1>Acme Inc.</h1>
        <p>123 Main Street, Anytown, CA 12345</p>
        <p>info@acmeinc.com</p>
      </div>
    </div>
  </header>

  <main class="container py-4">
    <div class="row">
      <div class="col-md-6">
        <h2>Bill To:</h2>
        <p>John Doe</p>
        <p>123 Fake Street, Otherville, NY 56789</p>
      </div>
      <div class="col-md-6 text-end">
        <h2>Invoice Details</h2>
        <p>Invoice #: 2024-04-001</p>
        <p>Invoice Date: 2024-04-15</p>
      </div>
    </div>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Description</th>
          <th>Quantity</th>
          <th>Rate</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Web Design</td>
          <td>1</td>
          <td>$1000.00</td>
          <td>$1000.00</td>
        </tr>
        <tr>
          <td>Development</td>
          <td>5</td>
          <td>$200.00</td>
          <td>$1000.00</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3">Subtotal</th>
          <td id="subtotal">$2000.00</td>
        </tr>
        <tr>
          <th colspan="3">Tax (10%)</th>
          <td id="tax">$200.00</td>
        </tr>
        <tr>
          <th colspan="3">Total</th>
          <td id="total">$2200.00</td>
        </tr>
      </tfoot>
    </table>

    <p class="text-end mt-3">**Note:** This invoice is due upon receipt.</p>
  </main>
</body>
</html>