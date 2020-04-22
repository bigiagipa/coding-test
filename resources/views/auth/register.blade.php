@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Registration') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group">
                        
                            <label for="telephone" class="col-form-label text-md-right">{{ __('Mobile Number') }}</label>

                            <input id="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" name="telephone" value="{{ old('telephone') }}" required autocomplete="telephone" autofocus>

                            @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                
                        </div>

                        <div class="form-group">
                            <label for="firstname" class="col-form-label text-md-right">{{ __('First name') }}</label>

                            <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus>

                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                        </div>

                        <div class="form-group">
                            <label for="lastname" class="col-form-label text-md-right">{{ __('Last name') }}</label>

                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus>

                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                           
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                           
                        </div>

                        <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
                            {{ Form::label('dob', 'Date of Birth', ['class' => 'col-form-label text-md-right']) }}
                            <div class="form-inline">
                                {{ Form::selectMonth('dob[month]', null, ['class' => 'form-control mr-3', 'placeholder' => 'Month']) }}
                                {{ Form::selectRange('dob[date]', 1, 31, null, ['class' => 'form-control mr-3', 'placeholder' => 'Date']) }}
                                {{ Form::selectYear('dob[year]', date('Y') - 3, date('Y') - 50, null, ['class' => 'form-control mr-3', 'placeholder' => 'Year']) }}
                            </div>
                            @error('dob')
                                <span class="invalid-feedback" role="alert" style="display:block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gender" class="col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="form-inline">

                                @foreach (constant('App\\User::GENDER') as $label => $value)
                                    {{ Form::radio('gender', $value, false, ['id'=> 'penyakit-'.$label, 'class' => 'form-check-input']) }}
                                    {{ Form::label('penyakit-'.$label, $label, ['class' => 'form-check-label mr-3']) }}
                                @endforeach
                            
                            </div>

                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                           
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>

                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                           
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
