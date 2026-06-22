<x-guest-layout>
    <style>
        body{
            margin:0;
            background:linear-gradient(135deg,#0f172a,#1e3a8a);
        }

        .register-page{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:24px;
        }

        .register-card{
            width:100%;
            max-width:460px;
            background:white;
            border-radius:26px;
            padding:34px;
            box-shadow:0 25px 60px rgba(0,0,0,.28);
        }

        .register-logo{
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

        .register-title{
            font-size:30px;
            font-weight:900;
            color:#0f172a;
            margin:0;
        }

        .register-subtitle{
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

        .register-btn{
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
            margin-top:8px;
        }

        .register-btn:hover{
            background:#1d4ed8;
        }

        .login-link{
            display:block;
            text-align:center;
            margin-top:18px;
            color:#2563eb;
            font-weight:800;
            text-decoration:none;
        }

        .login-link:hover{
            text-decoration:underline;
        }
    </style>

    <div class="register-page">
        <div class="register-card">

            {{-- <div class="register-logo">
                📋
            </div> --}}

            <h1 class="register-title">
                Crear cuenta
            </h1>

            <p class="register-subtitle">
                Registra un usuario para acceder al sistema de Bitácora BVS.
            </p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">
                        Nombre
                    </label>

                    <input
                        id="name"
                        class="form-input"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                    >

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

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
                        autocomplete="new-password"
                    >

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        Confirmar contraseña
                    </label>

                    <input
                        id="password_confirmation"
                        class="form-input"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    >

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="register-btn">
                    Crear Cuenta
                </button>
            </form>

            <a href="{{ route('login') }}" class="login-link">
                Ya tengo una cuenta
            </a>

        </div>
    </div>
</x-guest-layout>