<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Ansu Invest</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('backend/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="{{ asset('backend/css/sb-admin-2.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
  <link rel="stylesheet" type="text/css" href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}">
  <link href="{{ asset('backend/css/custom-action-button.css') }}" rel="stylesheet" >
  @yield('custom-css')
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    @include('backend.include.sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        @include('backend.include.top-bar')     
        <div class="container permission-button">
          @yield('role-button')
        </div>
        <div class="container-fluid">   
          @yield('content')
        </div>
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2019</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary a_submit_button" href="#" related-id="logout-form">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <form method="post" id="logout-form" action="{{ route('logout') }}">
    {{ csrf_field() }}
  </form>
  <style>
    .permission-button{
      display: flex;
      flex-flow: column;
      align-items: flex-end;
    }
  </style>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script type="text/javascript" charset="utf8" src="/DataTables/datatables.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="{{ asset('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js ')}}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('backend/js/sb-admin-2.min.js') }}"></script>

  <!-- Page level plugins -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
  <script type="text/javascript">
    $( function() {
        $( ".datetime" ).datetimepicker({

          dateFormat: 'yy-mm-dd',
          timeFormat: 'HH:mm:ss',
        });
      } );

    $( function() {
        $( ".date" ).datepicker({

          dateFormat: 'yy-mm-dd'
        });
      } );
  </script>
  
  <script src="{{ asset('core/js/confirm-dialogue.js') }}" type="text/javascript"></script>
  <script type="text/javascript">

  $(function()
  {
    $('.collapse').each(function(e)
    {
      var collapse_element = $(this)
      var navs = $(this).find('.nav-item')
      navs.each(function(e)
      {
        let link = $(this).find('a').attr('href');
        console.log(link)
        if(link == '{{ url()->current() }}')
        {

          $(this).addClass('active');
          collapse_element.addClass('show')
        }	
      })
    })
  })
  @if(\Session::has('success-msg'))
    $.notify({
      icon: 'fa fa-bell',
      title: '<strong>Heads up!</strong>',
      message: '{{ \Session::get('success-msg') }}'
    },{
      type: 'success'
    });
  @endif

  @if(\Session::has('error-msg'))
    $.notify({
      icon: 'fa fa-bell',
      title: '<strong>Heads up!</strong>',
      message: '{{ \Session::get('error-msg') }}'
    },{
      type: 'danger'
    });
  @endif

  @if(\Session::has('friendly-error-msg'))
    $.notify({
        icon: 'fa fa-bell',
        title: '<strong>Heads up!</strong>',
        message: '{{ \Session::get('friendly-error-msg') }}'
      },{
        type: 'info'
    });
  @endif

  @if(\Session::has('warning-msg'))
    $.notify({
        icon: 'fa fa-bell',
        title: '<strong>Heads up!</strong>',
        message: '{{ \Session::get('warning-msg') }}'
      },{
        type: 'warning'
    });
  @endif
</script>
  @yield('custom-js')

</body>

</html>

