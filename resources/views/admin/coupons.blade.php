<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin | Coupons</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="admin/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="admin/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="admin/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="admin/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="admin/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="admin/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="admin/assets/css/style.css">
    <!-- End layout styles -->
    
  </head>
  <body>
    <div class="container-scroller">
      @include('admin.sidebar')
      <div class="container-fluid page-body-wrapper">
        @include('admin.navbar')
        <div class="main-panel">
          <div class="content-wrapper">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    {{session()->get('message')}}
                </div>
            @endif
            <div class="text-center pt-4 pb-5">
                <h2>Coupons</h2>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">All Coupons</h4>
                            @foreach ($coupons as $coupon)
                                <div class="form-group">
                                    <div class="input-group">
                                        <input style="color: #fff" type="text" class="form-control" value="{{$coupon->code}}">
                                        <div class="input-group-append">
                                            <a href="{{url('delete_coupon',$coupon->id)}}" class="btn btn-sm btn-danger" type="Delete">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add New Coupon</h4>
                            <form class="forms-sample" action="{{route('admin.add_coupon')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label for="coupon_code" class="col-sm-3 col-form-label">Coupon Code</label>
                                    <div class="col-sm-9">
                                        <input style="color: #fff" type="text" name="coupon_code" class="form-control" id="coupon_code" placeholder="Enter Coupon Code..." required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="discount_percentage" class="col-sm-3 col-form-label">Discount Percentage</label>
                                    <div class="col-sm-9">
                                        <input style="color: #fff" type="number" name="discount_percentage" class="form-control" id="discount_percentage" placeholder="Enter Discount Percentage..." required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="min_order_amount" class="col-sm-3 col-form-label">Min Order Amount</label>
                                    <div class="col-sm-9">
                                        <input style="color: #fff" type="number" name="min_order_amount" class="form-control" id="min_order_amount" placeholder="Enter Min Order Amount..." required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <a class="btn btn-dark" href="{{route('admin.coupons')}}">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          @include('admin.footer')
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="admin/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="admin/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="admin/assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="admin/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="admin/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="admin/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="admin/assets/js/off-canvas.js"></script>
    <script src="admin/assets/js/hoverable-collapse.js"></script>
    <script src="admin/assets/js/misc.js"></script>
    <script src="admin/assets/js/settings.js"></script>
    <script src="admin/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="admin/assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>
