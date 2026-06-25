<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SegAgro - Sistema Administrativo')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/Logos/SegagroIcon.png') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="{{ asset('css/segagro.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    @yield('content')

    <script src="{{ asset('js/segagro.js') }}"></script>
    @stack('scripts')

    {{-- ── Toast container ── --}}
    <div id="segagro-toast-container" aria-live="polite"></div>

    <style>
        #segagro-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 380px;
            pointer-events: none;
        }
        .sg-toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px 18px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,.14);
            border-left: 4px solid;
            position: relative;
            overflow: hidden;
            pointer-events: all;
            animation: sgSlideIn .3s cubic-bezier(.16,1,.3,1);
            cursor: default;
        }
        .sg-toast.sg-hiding { animation: sgSlideOut .3s ease forwards; }

        .sg-toast-icon  { font-size: 20px; flex-shrink: 0; margin-top: 1px; }
        .sg-toast-body  { flex: 1; }
        .sg-toast-title { font-weight: 700; font-size: 14px; margin-bottom: 3px; }
        .sg-toast-msg   { font-size: 13px; line-height: 1.5; }
        .sg-toast-close {
            background: none; border: none; cursor: pointer;
            color: #9ca3af; font-size: 15px; padding: 0; line-height: 1;
            flex-shrink: 0; margin-top: 1px;
        }
        .sg-toast-close:hover { color: #374151; }
        .sg-toast-bar {
            position: absolute; bottom: 0; left: 0; height: 3px;
            border-radius: 0 0 0 12px;
        }

        /* Tipos */
        .sg-toast.sg-success { background: #f0fdf4; border-left-color: #16a34a; }
        .sg-toast.sg-success .sg-toast-icon  { color: #16a34a; }
        .sg-toast.sg-success .sg-toast-title { color: #15803d; }
        .sg-toast.sg-success .sg-toast-msg   { color: #166534; }
        .sg-toast.sg-success .sg-toast-bar   { background: #16a34a; }

        .sg-toast.sg-error  { background: #fef2f2; border-left-color: #dc2626; }
        .sg-toast.sg-error  .sg-toast-icon  { color: #dc2626; }
        .sg-toast.sg-error  .sg-toast-title { color: #b91c1c; }
        .sg-toast.sg-error  .sg-toast-msg   { color: #991b1b; }
        .sg-toast.sg-error  .sg-toast-bar   { background: #dc2626; }

        .sg-toast.sg-warning { background: #fffbeb; border-left-color: #d97706; }
        .sg-toast.sg-warning .sg-toast-icon  { color: #d97706; }
        .sg-toast.sg-warning .sg-toast-title { color: #b45309; }
        .sg-toast.sg-warning .sg-toast-msg   { color: #92400e; }
        .sg-toast.sg-warning .sg-toast-bar   { background: #d97706; }

        .sg-toast.sg-info   { background: #eff6ff; border-left-color: #2563eb; }
        .sg-toast.sg-info   .sg-toast-icon  { color: #2563eb; }
        .sg-toast.sg-info   .sg-toast-title { color: #1d4ed8; }
        .sg-toast.sg-info   .sg-toast-msg   { color: #1e40af; }
        .sg-toast.sg-info   .sg-toast-bar   { background: #2563eb; }

        @keyframes sgSlideIn {
            from { transform: translateX(110%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        @keyframes sgSlideOut {
            from { transform: translateX(0);    opacity: 1; max-height: 200px; margin-bottom: 0; }
            to   { transform: translateX(110%); opacity: 0; max-height: 0;     margin-bottom: -10px; }
        }
        @keyframes sgProgress {
            from { width: 100%; }
            to   { width: 0%;   }
        }
    </style>

    <script>
        // ── Sistema de toasts SEGAGRO ──
        const _sgMeta = {
            success: { icon: 'fa-check-circle',        title: 'Éxito' },
            error:   { icon: 'fa-times-circle',        title: 'Error' },
            warning: { icon: 'fa-exclamation-triangle', title: 'Advertencia' },
            info:    { icon: 'fa-info-circle',         title: 'Información' },
        };

        function showToast(message, type, duration) {
            type     = type     || 'info';
            duration = duration || 5000;
            var meta = _sgMeta[type] || _sgMeta.info;
            var id   = 'sg-toast-' + Date.now() + '-' + Math.random().toString(36).slice(2);
            var c    = document.getElementById('segagro-toast-container');

            var el = document.createElement('div');
            el.id        = id;
            el.className = 'sg-toast sg-' + type;
            el.innerHTML =
                '<div class="sg-toast-icon"><i class="fas ' + meta.icon + '"></i></div>' +
                '<div class="sg-toast-body">' +
                    '<div class="sg-toast-title">' + meta.title + '</div>' +
                    '<div class="sg-toast-msg">'   + message    + '</div>' +
                '</div>' +
                '<button class="sg-toast-close" onclick="closeToast(\'' + id + '\')"><i class="fas fa-times"></i></button>' +
                '<div class="sg-toast-bar" style="animation: sgProgress ' + (duration/1000) + 's linear forwards;"></div>';

            c.appendChild(el);
            setTimeout(function () { closeToast(id); }, duration);
        }

        function closeToast(id) {
            var el = document.getElementById(id);
            if (!el) return;
            el.classList.add('sg-hiding');
            setTimeout(function () { if (el.parentNode) el.parentNode.removeChild(el); }, 300);
        }

        // ── Flash messages desde Laravel ──
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success')) showToast(@json(session('success')), 'success'); @endif
            @if(session('error'))   showToast(@json(session('error')),   'error');   @endif
            @if(session('warning')) showToast(@json(session('warning')), 'warning'); @endif
            @if(session('info'))    showToast(@json(session('info')),    'info');    @endif

            // ── Convertir alerts Bootstrap existentes en toasts ──
            var alertMap = {
                'alert-success': 'success',
                'alert-danger':  'error',
                'alert-warning': 'warning',
                'alert-info':    'info',
            };
            Object.keys(alertMap).forEach(function(cls) {
                document.querySelectorAll('.' + cls + '[role="alert"]').forEach(function(el) {
                    var text = el.innerText.replace(/\n/g, ' ').trim();
                    if (text.length > 1) showToast(text, alertMap[cls]);
                    el.style.display = 'none';
                });
            });

            // ── Confirmación de eliminación ──
            document.querySelectorAll('form input[name="_method"][value="DELETE"]').forEach(function (input) {
                var form = input.closest('form');
                if (!form) return;
                form.addEventListener('submit', function (e) {
                    if (!confirm('¿Estás seguro de que deseas eliminar este registro?\n\nEsta acción no se puede deshacer.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".nav-item.has-submenu").forEach(function (btn) {
        btn.addEventListener("click", function () {
            this.classList.toggle("open");
            // cerrar otros submenus si quieres exclusivo
            // document.querySelectorAll(".nav-item.has-submenu").forEach(el => {
            //     if (el !== this) el.classList.remove("open");
            // });
        });
    });
});
</script>

</html>
