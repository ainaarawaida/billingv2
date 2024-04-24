<!DOCTYPE html>
<html>
<head>
  <title>Print Page</title>
  <link href="{{ url('css/bootstrap.min.css' )}}" rel="stylesheet" >
</head>
<body onload="window.print()">


  <div class="container">
    <div class="row">
      <div class="col-md-12">
  
        <div class="invoice-title">
          <h2>Invoice</h2>
          <h3 class="text-muted">Company Name</h3>
        </div>
  
        <div class="row">
          <div class="col-md-6">
            <address>
              <strong>Billing Address</strong><br>
              123 Main Street<br>
              Anytown, CA 12345<br>
              (555) 555-0100
            </address>
          </div>
          <div class="col-md-6 text-right">
            <p class="h3">Invoice # 2024-04-14</p>
            <p>Issue Date: April 14, 2024</p>
          </div>
        </div>
  
        <div class="row">
          <div class="col-md-6">
            <address>
              <strong>Ship To</strong><br>
              Customer Name<br>
              123 Ship Street<br>
              Anytown, CA 12345
            </address>
          </div>
          <div class="col-md-6 text-right">
            <p>Due Date: April 30, 2024</p>
          </div>
        </div>
  
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Product 1</td>
                <td>2</td>
                <td>$10.00</td>
                <td class="text-right">$20.00</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Product 2</td>
                <td>1</td>
                <td>$20.00</td>
                <td class="text-right">$20.00</td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><strong>Subtotal</strong></td>
                <td class="text-right"><strong>$40.00</strong></td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <div class="row">
          <div class="col-md-6">
            <p>Thank you for your business!</p>
          </div>
          <div class="col-md-6 text-right">
            <p><strong>Total: $40.00</strong></p>
          </div>
        </div>
  
      </div>
    </div>
  </div>


  </body>
</html>