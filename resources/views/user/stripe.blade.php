<!DOCTYPE html>
<html>
<head>
    <title>AmineMarket | Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    
<div class="container">
    <h3 class="text-center m-5">Payment By Card</h3>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table">
                    <h3 class="panel-title">Payment Details</h3>
                </div>
                <div class="panel-body">
    
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
    
                    <form action="{{ route('stripe.post', $totalPrice) }}" method="post">
                        @csrf
    
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label>
                                <input class='form-control' name="name_on_card" type='text' required>
                            </div>
                        </div>
    
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Card Number</label>
                                <input class='form-control' name="card_number" type='text' required>
                            </div>
                        </div>
    
                        <div class='form-row row'>
                            <div class='col-xs-12 col-md-4 form-group required'>
                                <label class='control-label'>CVC</label>
                                <input class='form-control' name="cvc" type='text' required>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group required'>
                                <label class='control-label'>Expiration Month</label>
                                <input class='form-control' name="expiry_month" type='text' required>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group required'>
                                <label class='control-label'>Expiration Year</label>
                                <input class='form-control' name="expiry_year" type='text' required>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now (${{$totalPrice}})</button>
                            </div>
                        </div>
                            
                    </form>
                </div>
            </div>        
        </div>
    </div>
</div>

</body>
</html>
