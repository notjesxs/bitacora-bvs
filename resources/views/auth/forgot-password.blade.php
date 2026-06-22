<x-guest-layout>
    <style>
        body{
            margin:0;
            background:linear-gradient(135deg,#0f172a,#1e3a8a);
        }

        .forgot-page{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:24px;
        }

        .forgot-card{
            width:100%;
            max-width:430px;
            background:white;
            border-radius:26px;
            padding:34px;
            box-shadow:0 25px 60px rgba(0,0,0,.28);
        }

        .forgot-logo{
            width:58px;
            height:58px;
            border-radius:18px;
            background:linear-gradient(135deg,#2563eb,#38bdf8);
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-size:26px;
            margin-bottom:18px;
        }

        .forgot-title{
            font-size:28px;
            font-weight:900;
            color:#0f172a;
            margin:0;
        }

        .forgot-subtitle{
            color:#64748b;
            margin-top:8px;
            margin-bottom:26px;
            line-height:1.5;
        }

        .form-group{
            margin-bottom:20px;
        }

        .form-label{
            display:block;
            font-size:13px;
            font-weight:800;
            color:#334155;
            margin-bottom:8px;
            text-transform:uppercase;
        }

        .form-input{
            width:100%;
            border:1px solid #dbe2ea;
            border-radius:14px;
            padding:14px 16px;
            font-size:15px;
            transition:.2s;
        }

        .form-input:focus{
            outline:none;
            border-color:#2563eb;
            box-shadow:0 0 0 4px rgba(37,99,235,.12);
        }

        .forgot-btn{
            width:100%;
            background:#2563eb;
            color:white;
            border:none;
            border-radius:14px;
            padding:14px;
            font-size:15px;
            font-weight:900;
            cursor:pointer;
            transition:.2s;
        }

        .forgot-btn:hover{
            background:#1d4ed8;
        }

        .back-link{
            display:block;
            text-align:center;
            margin-top:18px;
            color:#2563eb;
            font-weight:800;
            text-decoration:none;
        }

        .back-link:hover{
            text-decoration:underline;
        }

        .status-box{
            margin-bottom:16px;
        }
    </style>

    <div class="forgot-page">
        <div class="forgot-card">

            {{-- <div class="forgot-logo">
                📋
            </div> --}}

            <h1 class="forgot-title">
                Recuperar contraseña
            </h1>

            <p class="forgot-subtitle">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
            </p>

            <div class="status-box">
                <x-auth-session-status :status="session('status')" />
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        Correo electrónico
                    </label>

                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit" class="forgot-btn">
                    Enviar enlace de recuperación
                </button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                Volver al inicio de sesión
            </a>

        </div>
    </div>
</x-guest-layout>