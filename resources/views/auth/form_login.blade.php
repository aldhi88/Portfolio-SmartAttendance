<div>
    <div class="loading-50" wire:loading wire:target="submit">
        <div class="loader"></div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @endif

    <form class="form-horizontal" wire:submit="formSubmit">

        <div class="form-group auth-form-group-custom mb-4">
            <i class="ri-user-2-line auti-custom-input-icon"></i>
            <label for="username">Username</label>
            <input autofocus type="text" class="form-control @error('dt.username') is-invalid @enderror" wire:model="dt.username" placeholder="Ketik username anda disini..">
                @error('dt.username')
            <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-group auth-form-group-custom">
            <i style="cursor: pointer;" wire:click="togglePassword()" class="{{ $showPassword ? 'ri-eye-line' : 'ri-eye-off-line' }} auti-custom-input-icon"></i>
            <label for="userpassword">Password</label>
            <input type="{{ $showPassword ? 'text' : 'password' }}" class="form-control @error('dt.password') is-invalid @enderror" wire:model="dt.password" placeholder="Ketik password anda disini..">
            @error('dt.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary w-md waves-effect waves-light btn-block btn-lg" type="submit">Log In</button>
        </div>

    </form>
</div>
