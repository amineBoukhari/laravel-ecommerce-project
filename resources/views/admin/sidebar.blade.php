<!-- sidebar starts -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    </div>
    <ul class="nav">
   
        <li class="nav-item nav-category">
        <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('home')}}">
                <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.category') }}">
                <span class="menu-icon">
                <i class="mdi mdi-playlist-play"></i>
                </span>
                <span class="menu-title">Product Categories</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-cart"></i>
              </span>
              <span class="menu-title">Products</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.view_product')}}">Add Product</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('admin.show_product')}}">Show Products</a></li>
              </ul>
            </div>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{route('admin.user_orders')}}">
                <span class="menu-icon">
                <i class="mdi mdi-basket"></i>
                </span>
                <span class="menu-title">Orders</span>
            </a>
        </li>

        <!-- Add Coupon Section -->
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('admin.coupons') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-tag"></i> <!-- You can change this icon -->
                </span>
                <span class="menu-title">Coupons</span>
            </a>
        </li>
    </ul>
</nav>
<!-- sidebar ends -->
