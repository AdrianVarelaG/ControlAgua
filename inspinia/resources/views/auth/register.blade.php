<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Aguas</title>

    <!-- Bootstrap -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("fonts/font-awesome.min.css") }}" rel="stylesheet">
    <!-- ICheck -->
    <link href="{{ asset("css/plugins/iCheck/custom.css") }}" rel="stylesheet">
    <!-- Animate -->
    <link href="{{ asset("css/animate.css") }}" rel="stylesheet">
    <!-- Custom Style -->
    <link href="{{ asset("css/style.css") }}" rel="stylesheet">
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">IN+</h1>

            </div>
            <h3>Register to IN+</h3>
            <p>Create account to see it in action.</p>

                    <!-- show erros -->
                    @if (count($errors) > 0)
                      <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <i class="fa fa-exclamation-triangle"></i> <strong>Disculpe!</strong>
                        <ul>
                          @foreach ($errors->all() as $error)
                            <li>{!! $error !!}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif
                    <!-- /show erros -->

            <form class="m-t" id="form" role="form" method="post" action="{{ url('/register') }}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Name" required="">
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required="">
                </div>
                <div class="form-group">
                    <div class="checkbox i-checks"><label>
                        <input id="agree" required="required" name="agree" type="checkbox"><i></i> Agree the terms and policy </label>
                    </div>
                </div>
                <button type="submit" id="btn_submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="{{ url('/login') }}">Login</a>
            </form>
            <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset("js/jquery-2.1.1.js") }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset("js/bootstrap.min.js") }}"></script>
    <!-- iCheck -->
    <script src="{{ asset("js/plugins/iCheck/icheck.min.js") }}"></script>
    <!-- Jquery Validate -->
    <script src="{{ URL::asset('js/plugins/jquery-validation-1.16.0/jquery.validate.min.js') }}"></script>
    <script src="{{ URL::asset('js/plugins/jquery-validation-1.16.0/messages_es.js') }}"></script>

    <script>
        $(document).ready(function(){

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            // Validation
            $("#form").validate({
                submitHandler: function(form) {
                    $("#btn_submit").attr("disabled",true);
                    form.submit();
                }
            });

        });
    </script>
</body>

</html>
