@extends('auth.login_layout')
@section('title', __('common.login'))

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="login-brand">
                    <img src="https://en.gravatar.com/userimage/271610222/21204421de6c2fde4291415afe01795b.png" alt="logo" width="100" class="shadow-light rounded-circle">
                </div>

                <div class="card card-primary">
                    <div class="card-header"><h4>@lang('common.login')</h4></div>

                    <div class="card-body">
                        {{ Form::open(['url' => url('/login'), 'method' => 'post', 'class' => 'form needs-validation', 'novalidate' => '']) }}
                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">EMAIL/CALLSIGN</label>
                                {{
                                    Form::email('email', old('email'), [
                                        'id' => 'email',
                                        'placeholder' => __('common.email'),
                                        'class' => 'form-control',
                                        'tabindex' => '1',
                                        'required' => true,
                                        'autofocus' => true,
                                    ])
                                }}
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <div class="d-block">
                                    <label for="password" class="control-label">@lang('auth.password')</label>
                                    <div class="float-right">
                                        <a href="{{ url('/password/reset') }}" class="text-small">@lang('auth.forgotpassword')?</a>
                                    </div>
                                </div>
                                {{
                                    Form::password('password', [
                                        'name' => 'password',
                                        'id' => 'password',
                                        'class' => 'form-control',
                                        'placeholder' => __('auth.password'),
                                        'tabindex' => '2',
                                        'required' => true,
                                    ])
                                }}
                                @if ($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary btn-lg btn-block" tabindex="3">@lang('common.login')</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <div class="mt-5 text-muted text-center">
                    @lang('stisla.noaccount') <a href="{{ url('/register') }}">@lang('auth.createaccount')!</a>
                </div>
                <div class="simple-footer">
                    Copyright &copy; {{ config('app.name') }} <?php echo date('Y'); ?>
                    <br>
                    CrewCenter by <a href="https://flyazoresvirtual.com/users/1">Rui Alves</a>
                </div>
            </div>
        </div>
    </div>
@endsection
