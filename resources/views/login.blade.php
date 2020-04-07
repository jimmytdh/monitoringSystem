
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('/images') }}//favicon.png" sizes="16x16" type="image/png">

    <title>Login: COVID Monitoring System</title>


    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css') }}/bootstrap.css" rel="stylesheet">
    <link href="{{ asset('/css') }}/register.css" rel="stylesheet">
    <link href="{{ asset('/css') }}/font-awesome.css" rel="stylesheet">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="card card-signin flex-row my-5">
                <div class="card-img-left d-none d-md-flex">
                    <!-- Background image for card set in CSS! -->
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('/images') }}//logo.png" alt="" width="150">
                    </div>

                    <h5 class="card-title text-center">
                        <span class="font-weight-bold">COVID-19</span><br>Monitoring System
                        <br>
                        <small class="text-muted">
                            Sign in to start your session
                        </small>
                    </h5>

                    <form class="form-signin" method="post" action="{{ url('/login/validate') }}">
                        @if(session('status')=='error')
                        <div class="alert alert-danger">
                            <i class="icon fa fa-ban"></i> Failed to login! Please contact Administrator!
                        </div>
                        @endif

                        @if(session('status')=='denied')
                            <div class="alert alert-warning">
                                <i class="icon fa fa-exclamation-triangle"></i> Access Denied.
                            </div>
                        @endif
                        {{ csrf_field() }}
                        <div class="form-label-group">
                            <input type="text" name="username" id="inputUserame" class="form-control" placeholder="Username" required autofocus>
                            <label for="inputUserame">Username</label>
                        </div>

                        <div class="form-label-group">
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                            <label for="inputPassword">Password</label>
                        </div>

                        <button class="btn btn-lg btn-success btn-block text-uppercase" type="submit">Login</button>
                        <a class="d-block text-center mt-2 small" href="#">Don't have account? Register Now!</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
