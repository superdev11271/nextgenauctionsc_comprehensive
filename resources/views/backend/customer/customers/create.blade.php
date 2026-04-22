@extends('backend.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Create New Customer') }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('customers.stores') }}" method="POST">
            @csrf

            {{-- Name --}}
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Name') }}</label>
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Enter customer name" required>
                    @error('name')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Email') }}</label>
                <div class="col-md-8">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="Enter customer email" required>
                    @error('email')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Phone --}}
            <div class="form-group row">
                <label class="col-md-3 col-from-label">{{ translate('Phone') }}</label>
                <div class="col-md-8">
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" placeholder="Enter customer phone" required>
                    @error('phone')
                        <span class="text-danger small d-block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
{{-- Password --}}
<div class="form-group row">
    <label class="col-md-3 col-form-label">{{ translate('Password') }}</label>
    <div class="col-md-8">
        <div class="input-group">
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Enter password" required>
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                    <i class="las la-eye"></i>
                </button>
            </div>
        </div>
        @error('password')
            <span class="text-danger small d-block mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

{{-- Confirm Password --}}
<div class="form-group row">
    <label class="col-md-3 col-form-label">{{ translate('Confirm Password') }}</label>
    <div class="col-md-8">
        <div class="input-group">
            <input type="password" name="password_confirmation" id="confirm_password"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Re-enter password" required>
            <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_password', this)">
                    <i class="las la-eye"></i>
                </button>
            </div>
        </div>
        @error('password_confirmation')
            <span class="text-danger small d-block mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>

            {{-- Submit --}}
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-primary">{{ translate('Create') }}</button>
            </div>

        </form>
    </div>
</div>

{{-- Toggle Password Script --}}
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('la-eye');
            icon.classList.add('la-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('la-eye-slash');
            icon.classList.add('la-eye');
        }
    }
</script>

@endsection
