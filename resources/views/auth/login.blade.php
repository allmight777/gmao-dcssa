@extends('layouts.welcome')

@section('content')
<div class="d-flex justify-content-center align-items-center login-bg" style="height: 70vh;">


    <div class="login-card">

        <div class="login-header">
            <h3 class="text-light">Connexion</h3>
        </div>

        <div class="login-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email ou Login</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="email" name="email"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            Mot de passe oublié ?
                        </a>
                    </div>
                @endif
            </form>

            <hr class="my-4">

        </div>
    </div>
</div>

<style>
    /* Image de fond sur tout le body du login */
 .login-bg {
    background: url('{{ asset("images/1.webp") }}') no-repeat center center;
    background-size: cover;
    height: 100%;
}


    .login-card {
        width: 100%;
        max-width: 450px;
        background: rgba(255, 255, 255, 0.95); /* blanc semi-transparent */
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        overflow: hidden;
    }

    .login-header {
        background: #0d6efd;
        color: white;
        padding: 30px;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.3);
    }

    .login-body {
        padding: 40px;
    }

    .btn-primary {
        background: #0d6efd;
        color: white;
        padding: 12px;
        font-weight: 600;
        border: none;
    }

    .btn-primary:hover {
        background: #0b5ed7;
        color: white;
    }

    .form-control {
        background: rgba(255,255,255,0.9);
    }

    .input-group-text {
        background: rgba(255,255,255,0.9);
        border: none;
    }
</style>
@endsection
