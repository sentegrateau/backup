@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-6 col-md-offset-3">
         <div class="login-form commonform form-card">
            <h2>{{ __('Reset Password') }} </h2>
            <form method="POST" action="{{ route('password.update') }}">
               @csrf
               <input type="hidden" name="token" value="{{ $token }}">
               <div class="form-group">
               <label for="email">{{ __('E-Mail Address') }}</label>
                  <input placeholder="E-Mail Address" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}"  autofocus>
                  @if ($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
               </div>
               <div class="form-group">
               <label for="Password">{{ __('Password') }}</label>
                  <input placeholder="Password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                  @if ($errors->has('password'))
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
               </div>
               <div class="form-group">
               <label for="password-confirm">{{ __('Confirm Password') }}</label>
                  <input placeholder="Confirm Password" id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
               </div>
               <div class=" align-center">
                  <button  type="submit btn btn-login" class="button">  {{ __('Reset Password') }}</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection