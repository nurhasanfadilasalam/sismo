<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Sistem Monioring Jaringan @yield('title')</title>

  <link rel="icon" href="{{ asset('img/ikon.png') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('img/ikon.png') }}" type="image/x-icon">

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/spinner/jquery-spinner.min.css') }}">

    	<!-- Chart JS -->
	<script src="{{ asset('js/chart.js/chart.min.js') }}"></script>

  <!-- CSS Custum -->
  <style>
    .jquery-spinner-wrap {
      position: fixed !important;
      z-index: 99999;
    }
    .navbar-bg {
      /* background-color: #19b1ec; */
      background-color: #150485;
      
    }

    .main-sidebar .sidebar-brand a {
      font-size: 28px;
    }

    .action1 {
      min-width: 150px;
    }

    .action2 {
      min-width: 200px;
    }

    .action3 {
      min-width: 260px;
    }

    .table {
      font-size: 12px !important;
    }

    .th-md {
      min-width: 180px;
    }

    .btn-insearch {
      background: white;
      border-color: #e4e6fc;
    }

    .trFilter>th {
      background-color: white !important;
    }

    .linkMaster {
      font-size: 20px !important;
    }

    .myform-textarea {
      min-height: 80px;
    }
  </style>
  
  <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
</head>

<body id="mainContainer" class="@yield('bodyclass')">
  @php( $userRoles = json_decode(Auth::user()->roles) )
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">
                @foreach($userRoles as $role)<span>{{ $role }}</span>, @endforeach
              </div>
              <a href="#" class="dropdown-item has-icon"><i class="far fa-user"></i> Profile</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item has-icon text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-head').submit();"><i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}</a>
              <form id="logout-form-head" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            </div>
          </li>
        </ul>
      </nav>
      @section('sidebar')
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand" >
            <a href="/" style="text-transform : capitalize !important;">SISMO PERANGKAT JARINGAN</a>
          </div> <div class="sidebar-brand sidebar-brand-sm" style="background-color:#150485">
            <a href="/" style="background-color:#ffffff">SISMO</a>
          </div>
          <ul class="sidebar-menu">
            <hr>
          @if(in_array("OWNER", $userRoles) || in_array("ADMIN", $userRoles) )
            <!-- menu bar -->            
            <li @if (!empty($halaman) && $halaman == 'dashboard')class="active" @endif><a href="/" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            
            
            <li class="menu-header">NAVIGATION</li>
            <li @if (!empty($halaman) && $halaman == 'laporan')class="active"@endif><a class="nav-link" href="{{ url('status_perangkat') }}"><i class="fas fa-chalkboard-teacher"></i> <span>Laporan</span></a></li>
            <li @if (!empty($halaman) && $halaman == 'logstatus')class="active"@endif><a class="nav-link" href="{{ url('logstatus') }}"><i class="far fa-file-alt"></i> <span>Log Status</span></a></li> 

          
            

            @if(in_array("OWNER", $userRoles))
            <li class="menu-header">Master Data</li>
            {{-- <li @if (!empty($halaman) && $halaman == 'parameter')class="active"@endif><a class="nav-link" href="{{ url('masters?do=inventoryType') }}"><i class="fas fa-database"></i> <span>Data Perangkat Jaringan</span></a></li> --}}
            <li @if (!empty($halaman) && $halaman == 'parameter')class="active"@endif><a class="nav-link" href="{{ url('perangkat') }}"><i class="fas fa-database"></i> <span>Data Perangkat Jaringan</span></a></li>
            <li @if (!empty($halaman) && $halaman == 'gedung')class="active"@endif><a class="nav-link" href="{{ url('gedung') }}"><i class="fas fa-university"></i> <span>Data Gedung</span></a></li>
            <li @if (!empty($halaman) && $halaman == 'userakses')class="active"@endif><a class="nav-link" href="{{ url('users') }}"><i class="fas fa-users"></i> <span>User Akses</span></a></li>
            @endif

          @elseif(in_array("DRIVER", $userRoles))            
            <li @if (!empty($halaman) && $halaman == 'dashboard')class="active"@endif><a href="/" class="nav-link"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
           
          @endif
          </ul>

          <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a class="btn btn-danger btn-lg btn-block btn-icon-split" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </aside>
      </div>
      @show

      <!-- Main Content -->
      <div class="main-content">
        @yield('content')
      </div>

      <footer class="main-footer">
        <div class="footer-left">Copyright &copy; 2021 <div class="bullet"></div> Allright reserved</div>
        <div class="footer-right">Beta Version 1.0.0</div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('js/popper.min.js') }}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
  <script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script> -->
  <script src="{{ asset('js/moment.min.js') }}"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script> -->
  <!-- <script src="{{ asset('js/stisla.js') }}"></script> -->
  <!-- JS Libraies -->



  <!-- Template JS File -->
  <script src="{{ asset('js/scripts.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>

  <script src="{{ asset('vendor/spinner/jquery-spinner.min.js') }}"></script>
  <script type="text/javascript">
    const spinner = new jQuerySpinner({ parentId: 'mainContainer' });
    spinner.show();
    $(document).ready(function() { spinner.hide(); });
  </script>
  <!-- Page Specific JS File -->
  @yield('footer-scripts')
</body>

</html>