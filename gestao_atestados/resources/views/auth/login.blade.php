@extends('auth.layouts.app')

@section('content')
    <section>
        <div class="d-flex justify-content-center align-items-center flex-column mt-2">
            <div><img src="{{ asset('storage/images/logo.jpg') }}" alt=""></div>
            <div class="mb-2" style="width: 430px; border: 1px solid rgb(232 232 232); border-top: 0; padding: 25px; margin-top: 30px; border-radius: 10px; box-shadow: 3px 2px 2px rgb(232 232 232);">
                @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Endereço de Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Senha') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ __('Lembrar de mim') }}</label>
                    </div>
                    <div>
                        <a href="{{ route('password.request') }}" style="font-size: 14px; color: black; margin-right: 4px;">{{ __('Esqueceu Sua Senha?') }}</a>
                        <a href="{{ route('register') }}" style="font-size: 14px; color: black; margin-right: 4px;">{{ __('Ainda não está registrado?') }}</a>
                        <button type="submit" class="btn btn-dark" style="font-size: 12px; font-weight: bold; width: 75px;">{{ __('Login') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
