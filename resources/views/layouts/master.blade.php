
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>RC-AKIC SSC SYSTEM</title>
  <link rel="icon" type="image/png" href="{{asset('images/akic-logo.png')}}">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">
  <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
  <style>
    .dark-mode .swal2-popup .swal2-title {
        color: green;
    }
    .dark-mode .page-item:not(.active) .page-link {
        background-color: #fff;
        border-color: #3f474e;
    }
    .content .container-fluid{
      background: #fff;
      padding: 1%;
      color: black;
      border-radius: 10px;
    }
    .dataTables_filter .form-control,.department-datatable_length .custom-select{
      background: #fff;
    }
    .dark-mode .form-control:not(.form-control-navbar):not(.form-control-sidebar){
      background: #fff;
      color: #000;
    }
    .modal .modal-header, .modal label{
      color: #fff;
    }
    .swal2-html-container{
      color: #fff!important;
    }
    .dark-mode .page-item.disabled a, .dark-mode .page-item.disabled .page-link{
      color: #fff!important;
    }
    .dataTable{
      width: 100%!important;
    }
  </style>
  @yield('css')
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
@include('sweetalert::alert')
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{asset('images/akic-logo.png')}}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- Messages Dropdown Menu -->
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('images/akic-logo.png')}}" alt="AKIC Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 1rem;">RC-AKIC SSC SYSTEM</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('images/default-image.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
            <li class="nav-item">
                <a href="{{route('department.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-building" style="color:#2596be"></i>
                    <p>
                    Departments
                    </p>
                </a>
            </li>
          @endif
          @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
            <li class="nav-item">
                <a href="{{route('course.index')}}" class="nav-link">
                    <i class="nav-icon fas fa-th-list" style="color: #e99d59"></i>
                    <p>
                    Courses
                    </p>
                </a>
            </li>
          @endif
            <li class="nav-item">
            @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin" || Auth::user()->role === "Ssc-officer")
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users" style="color:pink"></i>
              <p>
                Students
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            @endif
            <ul class="nav nav-treeview" style="display: none;">
              @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
              <li class="nav-item">
                <a href="{{route('student.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All</p>
                </a>
              </li>
              @endif
              @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin" || Auth::user()->role === "Ssc-officer")
              <li class="nav-item">
                <a href="{{route('student.without.house')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Without House</p>
                </a>
              </li>
              @endif
              @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin" || Auth::user()->role === "Ssc-officer")
              <li class="nav-item">
                <a href="{{route('student.with.houses.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>With House</p>
                </a>
              </li>
              @endif
              @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin" || Auth::user()->role === "Ssc-officer")
              <li class="nav-item">
                <a href="{{route('student.with.fines.index')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>With Fines</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
          <li class="nav-item">
            <a href="{{route('officer.index')}}" class="nav-link">
              <i class="nav-icon fas fa-user-tie" style="color:purple"></i>
              <p>
                Officers
              </p>
            </a>
          </li>
          @endif
          @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
          <li class="nav-item">
            <a href="{{route('house.index')}}" class="nav-link">
              <i class="nav-icon fas fa-house-user" style="color:yellow"></i>
              <p>
                House of Wisdom
              </p>
            </a>
          </li>
          @endif
          @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
          <li class="nav-item">
            <a href="{{route('account.index')}}" class="nav-link">
              <i class="nav-icon fas fa-user-circle" style="color:blue"></i>
              <p>
                Accounts
              </p>
            </a>
          </li>
          @endif
          @if(Auth::user()->role === "Adviser" || Auth::user()->role === "Admin")
          <li class="nav-item">
            <a href="{{route('fine.index')}}" class="nav-link">
              <i class="nav-icon fas fa-money-bill-wave" style="color:green"></i>
              <p>
                Fines
              </p>
            </a>
          </li>
          @endif
          @if(Auth::user()->role === "Cashier" || Auth::user()->role === "Admin")
          <li class="nav-item">
            <a href="{{route('student_payment.index')}}" class="nav-link">
              <i class="nav-icon fas fa-hand-holding-usd"></i>
              <p>
                Fine Payment
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link text-white text-left" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off" style="color:red"></i>
              <p>
              Logout
              </p>
          </a>
          </li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
              @csrf
          </form>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
              @yield('page-title')
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @yield('breadcrumb-item')
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2021-2031 RC-AKIC SSC SYSTEM.</strong>
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('js/sweetalert2.min.js')}}"></script>
<script>
  var url = window.location;
  // for sidebar menu entirely but not cover treeview
  $('ul.nav-sidebar a').filter(function() {
      return this.href == url;
  }).addClass('active');
  // for treeview
  $('ul.nav-treeview a').filter(function() {
      return this.href == url;
  }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
  })
</script>
@yield('scripts')
</body>
</html>
