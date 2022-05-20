@extends('layouts.app')
@section('content')
    @php
        $roles=['owner'=>'Home Owner','installer'=>'Installer / Electrician','developer'=>'Developer'];
    @endphp
    <section class="loginbg" style="background-image: url({{asset('front/images/login.jpg')}});
        background-size: cover; height:100vh;display: flex;align-items: center;">
        <div class="container">
            <div class="row">
                <div class="login-register">
                    <div class="login-form commonform">
                        <h2>Login</h2>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="login" value="true"/>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" placeholder=" "
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @if ($errors->has('email') && !empty(old('login')))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" required>
                            </div>

                            <div class="checkbox">
                                <label><input type="checkbox"> Remember me</label>
                            </div>

                            <div class="checkbox pull-right">
                                <label>
                                    <a href="{{route('password.request')}}"> Forgot Password</a>
                                </label>
                            </div>


                            <div class=" align-center">
                                <button class="button" href="#">Login</button>
                            </div>
                        </form>


                    </div>


                    <div class="register-form commonform">
                        <h2>Register</h2>
                        @if ($message = Session::get('success'))
                            <p class="text-success">{{$message}}</p>
                        @elseif ($message = Session::get('error'))
                            <p class="text-danger">{{$message}}</p>
                        @endif
                        <form method="POST" id="contact-form-submit" onsubmit="return telephoneCheck()"
                              action="{{ route('register') }}">
                            @csrf


                            <ul>
                                <?php $i = 0; ?>
                                @foreach($roles as $key=>$role)

                                    <li><p>
                                            <input type="radio" id="role-{{$i}}" value="{{$key}}"
                                                   name="role2" {{(old('role2')==$key?'checked':($i==0)?'checked':'')}}>
                                            <label for="role-{{$i}}">{{$role}}</label>
                                        </p></li>
                                    <?php $i++; ?>
                                @endforeach

                            </ul>

                            <div class="form-group">
                                <input id="name" type="text" placeholder="Name"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                                       value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">

                                <input type="email" placeholder="Email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                       value="{{ old('email') }}" required>

                                @if ($errors->has('email') && empty(old('login')))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">

                                <input type="text" placeholder="0XXXXXXXXX" id="contact-number-validate"
                                       class="form-control{{ $errors->has('contact') ? ' is-invalid' : '' }}"
                                       {{--pattern="[0]{1}[23478]{1}[0-9]{8}"--}}

                                       name="contact" value="{{ old('contact') }}" required>
                                <span id="show-contact-error"></span>
                                @if ($errors->has('contact'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">

                                <input id="password" type="password"
                                       placeholder="Password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).{6,}"
                                       title="Must contain at least one number and one uppercase and lowercase and special letter, and at least 6 or more characters"
                                       name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">

                                <input id="password-confirm"
                                       placeholder="Confirm Password"
                                       type="password"
                                       class="form-control"
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#\$%\^&\*]).{6,}"
                                       title="Must contain at least one number and one uppercase and lowercase and special letter, and at least 6 or more characters"
                                       name="password_confirmation" required>

                            </div>

                            <div class="form-group show-hide">

                                <input type="text" placeholder="Company"
                                       class="form-control{{ $errors->has('company') ? ' is-invalid' : '' }}"
                                       name="company" value="{{ old('company') }}">

                                @if ($errors->has('company'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group show-hide">

                                <input type="number" placeholder="ABN (11 Digits)"
                                       pattern="[0-9]{11}"
                                       title="ABN number should be greater than or equal to 11 digit"
                                       class="form-control{{ $errors->has('abn') ? ' is-invalid' : '' }}"
                                       min="11"
                                       name="abn" value="{{ old('abn') }}">

                                @if ($errors->has('abn'))
                                    <span class="invalid-feedback text-danger" role="alert">
                                        <strong>{{ $errors->first('abn') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="checkbox">
                                <label><input type="checkbox" required> Agree with the <a href="{{url('/terms-conditions-signup')}}"
                                                                                 target="_blank">Terms of Use</a> and <a
                                        href="{{url('/privacy-policy')}}" target="_blank">Privacy Statement</a>. </label>
                            </div>


                            <div class=" align-center">
                                <button class="button" href="#">Send</button>
                            </div>
                        </form>


                    </div>


                </div>


            </div>

        </div>

    </section>
@endsection
@section('js')
    <script>
        $(function () {
            $('.show-hide').hide()
            @if(!empty(old('role2')) && old('role2')!='owner')
            $('.show-hide').show()
            @endif
            $('input[type=radio]').change(function () {
                if ($(this).val() != 'owner') {
                    $('.show-hide').show()
                } else {
                    $('.show-hide').hide()
                }
            })
        });
    </script>
@endsection
