<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demasiados intentos — SEGAGRO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        body { background: #f5f5f5; }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* ── Panel izquierdo ── */
        .panel-left {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
        }

        /* ── Logo ── */
        .logo { display: flex; align-items: center; margin-bottom: 48px; }
        .logo-icon {
            background: #4cd137; color: white;
            padding: 12px; border-radius: 8px;
            margin-right: 4px; font-weight: bold; font-size: 18px;
        }
        .logo-text { font-size: 24px; font-weight: bold; color: #333; }

        /* ── Icono de bloqueo ── */
        .lock-circle {
            width: 100px; height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 3px solid #fca5a5;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 28px;
            animation: pulseRed 2s infinite;
        }
        .lock-circle i { font-size: 44px; color: #dc2626; }

        @keyframes pulseRed {
            0%,100% { box-shadow: 0 0 0 0 rgba(220,38,38,.25); }
            50%      { box-shadow: 0 0 0 16px rgba(220,38,38,0); }
        }

        /* ── Textos ── */
        .error-code { font-size: 13px; font-weight: 700; letter-spacing: 3px; color: #dc2626; text-transform: uppercase; margin-bottom: 12px; }
        .title { font-size: 28px; font-weight: 700; color: #111827; margin-bottom: 12px; text-align: center; }
        .subtitle { font-size: 15px; color: #6b7280; text-align: center; max-width: 380px; line-height: 1.6; margin-bottom: 36px; }

        /* ── Countdown ── */
        .countdown-box {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 16px;
            padding: 24px 40px;
            text-align: center;
            margin-bottom: 36px;
            min-width: 260px;
        }
        .countdown-label { font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .countdown-timer { font-size: 52px; font-weight: 800; color: #dc2626; font-variant-numeric: tabular-nums; line-height: 1; }
        .countdown-unit  { font-size: 13px; color: #9ca3af; margin-top: 4px; }

        /* ── Barra de progreso ── */
        .progress-track {
            width: 260px;
            height: 6px;
            background: #f3f4f6;
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 36px;
        }
        .progress-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #dc2626, #f87171);
            width: 100%;
            transition: width 1s linear;
        }

        /* ── Botones ── */
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            background: #4cd137; color: white;
            padding: 13px 28px; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 600; cursor: pointer;
            text-decoration: none; transition: background .2s, transform .2s;
        }
        .btn-back:hover { background: #44bd32; transform: translateY(-2px); color: white; }

        .tip-box {
            margin-top: 24px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 14px 20px;
            max-width: 380px;
            font-size: 13px;
            color: #15803d;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }
        .tip-box i { margin-top: 1px; flex-shrink: 0; }

        /* ── Panel derecho (verde degradado igual que login) ── */
        .panel-right {
            flex: 1;
            background: linear-gradient(135deg, #15803d 0%, #4cd137 50%, #86efac 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 60px;
        }
        .right-content { text-align: center; color: white; }
        .right-content .big-lock { font-size: 100px; opacity: .25; margin-bottom: 24px; }
        .right-content h3 { font-size: 26px; font-weight: 700; margin-bottom: 12px; opacity: .9; }
        .right-content p  { font-size: 15px; opacity: .7; max-width: 320px; line-height: 1.6; }

        @media (max-width: 768px) {
            .panel-right { display: none; }
            .panel-left  { padding: 40px 24px; }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Panel izquierdo -->
    <div class="panel-left">

        <div class="logo">
            <div class="logo-icon">SEG</div>
            <div class="logo-text">AGRO</div>
        </div>

        <div class="lock-circle">
            <i class="fas fa-lock"></i>
        </div>

        <div class="error-code">Error 429</div>
        <h1 class="title">Demasiados intentos</h1>
        <p class="subtitle">
            Has superado el límite de intentos de inicio de sesión.<br>
            Por seguridad, el acceso está bloqueado temporalmente.
        </p>

        <div class="countdown-box">
            <div class="countdown-label">Podrás intentarlo de nuevo en</div>
            <div class="countdown-timer" id="countdown">60</div>
            <div class="countdown-unit">segundos</div>
        </div>

        <div class="progress-track">
            <div class="progress-bar" id="progress-bar"></div>
        </div>

        <a href="{{ route('login') }}" class="btn-back" id="btn-back" style="pointer-events:none;opacity:.5;">
            <i class="fas fa-arrow-left"></i>
            Volver al inicio de sesión
        </a>

        <div class="tip-box">
            <i class="fas fa-lightbulb"></i>
            <span>Si olvidaste tu contraseña, usa el enlace <strong>"Recordar contraseña"</strong> en el formulario de inicio de sesión.</span>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="panel-right">
        <div class="right-content">
            <div class="big-lock"><i class="fas fa-shield-alt"></i></div>
            <h3>Protección activa</h3>
            <p>Limitamos los intentos de acceso para proteger tu cuenta contra accesos no autorizados.</p>
        </div>
    </div>

</div>

<script>
    const SECONDS = 60;
    let remaining = SECONDS;

    const countdownEl  = document.getElementById('countdown');
    const progressEl   = document.getElementById('progress-bar');
    const btnBack      = document.getElementById('btn-back');

    function tick() {
        remaining--;
        countdownEl.textContent = remaining;
        progressEl.style.width  = (remaining / SECONDS * 100) + '%';

        if (remaining <= 0) {
            countdownEl.textContent = '0';
            progressEl.style.width  = '0%';
            btnBack.style.pointerEvents = 'auto';
            btnBack.style.opacity = '1';
            clearInterval(timer);
        }
    }

    const timer = setInterval(tick, 1000);
</script>

</body>
</html>
