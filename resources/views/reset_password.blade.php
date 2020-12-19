<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password GBPS</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- jQuery and JS bundle w/ Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>

    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="d-flex align-items-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Reset Password
                        </div>

                        <div class="card-body">
                            @if (isset($reset) && $is_valid)
                                @if ($errors->count() > 0)
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif
                                <form action="{{ route('reset_password') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">New Password Verification</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" required>
                                    </div>

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </form>
                            @elseif (isset($success) && $success)
                                <h4 class="text-success">Your password has been reset successfully</h4>
                            @else
                                <h4 class="text-danger">Your Reset Password Request Is Invalid</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>