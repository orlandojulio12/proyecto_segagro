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
                <div style="background:#fef2f2;border:1.5px solid #fca5a5;border-left:4px solid #dc2626;
                            border-radius:10px;padding:14px 16px;margin-bottom:20px;
                            display:flex;align-items:flex-start;gap:12px;">
                    <i class="fas fa-exclamation-circle" style="color:#dc2626;font-size:18px;margin-top:1px;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;color:#b91c1c;font-size:14px;margin-bottom:4px;">
                            Credenciales incorrectas
                        </div>
                        @foreach ($errors->all() as $error)
                            <div style="color:#991b1b;font-size:13px;">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(session('status'))
                <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-left:4px solid #16a34a;
                            border-radius:10px;padding:14px 16px;margin-bottom:20px;
                            display:flex;align-items:center;gap:12px;">
                    <i class="fas fa-check-circle" style="color:#16a34a;font-size:18px;flex-shrink:0;"></i>
                    <span style="color:#15803d;font-size:14px;">{{ session('status') }}</span>
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