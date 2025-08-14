@extends('layouts.app')

@section('title', 'Login - SEGAGRO')

@section('content')
<div class="login-container">
    <div class="login-left">
        <div class="logo-login">
            <div class="logo-icon">SEG</div>
            <div class="logo-text">AGRO</div>
        </div>
        
        <div class="welcome-text">
            <h2>Welcome to SEG<span style="color: #4cd137;">AGRO</span>!</h2>
            <p>Tu aliado digital en la gestión de CEDAGRO.</p>
        </div>
        
        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-group">
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="forgot-password">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Recordar contraseña</a>
                @endif
            </div>
            
            <button type="submit" class="login-btn">Iniciar sesión</button>
        </form>
    </div>
    
    <div class="login-right"></div>
</div>
@endsection