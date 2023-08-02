<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Skydash Admin</title>

    <!-- MyCSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}">


    <script src="{{ asset('assets/js/axios.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery-3.7.0.min.js')}}"></script>
    <script src="{{ asset('assets/js/toastify-js.js')}}"></script>
    <script src="{{ asset('assets/js/config.js')}}"></script>

</head>

<body>
<div id="loader" class="LoadingOverlay d-none">
    <div class="Line-Progress">
        <div class="indeterminate"></div>
    </div>
</div>

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                        <div class="brand-logo">
                            <img src="{{ asset('inc/images/logo.svg')}}" alt="logo">
                        </div>
                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light">Sign in to continue.</h6>
                        <form class="pt-3">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-sm" id="email" placeholder="Email Address">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-sm" id="password" placeholder="Password">
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <button onclick="SubmitLogin()" class="btn btn-outline-primary btn-sm">Login</button>
                                </div>
                                <a href="#" class="auth-link text-black">Forgot password?</a>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Don't have an account? <a href="{{ route('register')}}" class="text-primary">Create</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

<script src="{{ asset('/assets/js/bootstrap.bundle.js')}}"></script>
<script src="{{ asset('/assets/js/jquery.dataTables.min.js')}}"></script>

<script>
    async function SubmitLogin() {
        let email=$('#email').val();
        let password=$('#password').val();

        if(email.length===0){
            errorToast("Email is required");
        }
        else if(password.length===0){
            errorToast("Password is required");
        }
        else{
            showLoader();
            let res=await axios.post("/userLogin",{email:email, password:password});
            hideLoader()
            if(res.status===200 && res.data['status']==='success'){
                window.location.href="/dashboard";
            }
            else{
                errorToast(res.data['message']);
            }
        }
    }
</script>

</body>

</html>
