@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Staff Information')}}</h5>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <form class="form-horizontal" action="{{ route('staffs.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Name')}}" id="name"   oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')"
                             name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Email')}}"  name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="mobile">{{translate('Phone')}}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Phone')}}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" id="mobile" name="mobile" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="password">{{translate('Password')}}</label>
                        <div class="col-sm-9">
                            <span class="view-password"><i class="las la-eye-slash" aria-hidden="true"></i> </span>
                            <input type="password" placeholder="{{translate('Password')}}" id="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="confirm_password">{{translate('Confirm Password')}}</label>
                        <div class="col-sm-9 position-relative">
                            <span class="view-password"><i class="las la-eye-slash" aria-hidden="true"></i> </span>
                            <input type="password" class="form-control" placeholder="{{translate('Confirm Password')}}" name="confirm_password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{translate('Role')}}</label>
                        <div class="col-sm-9">
                            <select name="role_id" required class="form-control aiz-selectpicker">
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    //eye icon password show/////
    const eyes = document.querySelectorAll('.view-password');
        eyes.forEach((eye) => {
            const passwordInput = eye.nextElementSibling;
            eye.addEventListener('click', () => {
                const isActive = eye.parentElement.classList.toggle('active');
                passwordInput.type = isActive ? 'text' : 'password';

            });
        });
    //end///
    </script>
@endsection
