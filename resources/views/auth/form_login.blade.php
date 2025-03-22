<div>
    <form class="form-horizontal" action="index.html">

        <div class="form-group auth-form-group-custom mb-4">
            <i class="ri-user-2-line auti-custom-input-icon"></i>
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Enter username">
        </div>

        <div class="form-group auth-form-group-custom mb-4">
            <i class="ri-lock-2-line auti-custom-input-icon"></i>
            <label for="userpassword">Password</label>
            <input type="password" class="form-control" id="userpassword" placeholder="Enter password">
        </div>

        <div class="mt-4 text-center">
            <button class="btn btn-primary w-md waves-effect waves-light btn-block btn-lg" type="submit">Log In</button>
        </div>

        {{-- <div class="mt-4 text-center">
            <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock mr-1"></i> Forgot your password?</a>
        </div> --}}
    </form>
</div>
