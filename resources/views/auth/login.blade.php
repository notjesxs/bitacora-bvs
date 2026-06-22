<x-guest-layout>
    <style>
        body{
            margin:0;
            background:linear-gradient(135deg,#0f172a,#1e3a8a);
        }

        .login-page{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:24px;
        }

        .login-card{
            width:100%;
            max-width:430px;
            background:white;
            border-radius:26px;
            padding:34px;
            box-shadow:0 25px 60px rgba(0,0,0,.28);
        }

        .login-logo{
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

        .login-title{
            font-size:30px;
            font-weight:900;
            color:#0f172a;
            margin:0;
        }

        .login-subtitle{
            color:#64748b;
            margin-top:8px;
            margin-bottom:26px;
        }

        .form-group{
            margin-bottom:18px;
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

        .remember-row{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin:10px 0 22px;
        }

        .remember-label{
            display:flex;
            align-items:center;
            gap:8px;
            color:#475569;
            font-size:14px;
        }

        .forgot-link{
            color:#2563eb;
            font-weight:700;
            font-size:14px;
            text-decoration:none;
        }

        .forgot-link:hover{
            text-decoration:underline;
        }

        .login-btn{
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

        .login-btn:hover{
            background:#1d4ed8;
        }

        .status-box{
            margin-bottom:16px;
        }
    </style>

    <div class="login-page">
        <div class="login-card">

            {{-- <div class="login-logo">
                📋
            </div> --}}

            <h1 class="login-title">
                Bitácora BVS
            </h1>

            <p class="login-subtitle">
                Inicia sesión para gestionar tus casos internos.
            </p>

            <div class="status-box">
                <x-auth-session-status :status="session('status')" />
            </div>

            <form method="POST" action="{{ route('login') }}">
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
                        autocomplete="username"
                    >

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        Contraseña
                    </label>

                    <input
                        id="password"
                        class="form-input"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="remember-row">
                    <label for="remember_me" class="remember-label">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                        >

                        Recordarme
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="login-btn">
                    Iniciar Sesión
                </button>
            </form>

        </div>
    </div>
</x-guest-layout>