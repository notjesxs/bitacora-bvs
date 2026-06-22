<section>

    <style>
        .password-title{
            font-size:22px;
            font-weight:900;
            color:#0f172a;
            margin-bottom:6px;
        }

        .password-description{
            color:#64748b;
            margin-bottom:24px;
        }

        .password-group{
            margin-bottom:20px;
        }

        .password-label{
            display:block;
            margin-bottom:8px;
            font-size:13px;
            font-weight:800;
            color:#334155;
            text-transform:uppercase;
        }

        .password-input{
            width:100%;
            border:1px solid #dbe2ea;
            border-radius:14px;
            padding:14px 16px;
            font-size:15px;
            transition:.2s;
        }

        .password-input:focus{
            outline:none;
            border-color:#2563eb;
            box-shadow:0 0 0 4px rgba(37,99,235,.12);
        }

        .password-actions{
            display:flex;
            align-items:center;
            gap:12px;
            margin-top:25px;
        }

        .password-save-btn{
            background:#2563eb;
            color:white;
            border:none;
            border-radius:14px;
            padding:12px 22px;
            font-weight:800;
            cursor:pointer;
            transition:.2s;
        }

        .password-save-btn:hover{
            background:#1d4ed8;
        }

        .password-success{
            color:#16a34a;
            font-weight:700;
        }

        .password-tip{
            background:#f8fafc;
            border:1px solid #e2e8f0;
            border-radius:14px;
            padding:14px;
            color:#475569;
            font-size:14px;
            margin-bottom:22px;
        }
    </style>

    <header>
        <h2 class="password-title">
            Cambiar Contraseña
        </h2>

        <p class="password-description">
            Mantén tu cuenta segura usando una contraseña fuerte.
        </p>
    </header>

    <div class="password-tip">
        Recomendación: usa mínimo 8 caracteres, combinando letras, números y símbolos.
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="password-group">
            <label class="password-label">
                Contraseña Actual
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="password-input"
                autocomplete="current-password"
            >

            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="password-group">
            <label class="password-label">
                Nueva Contraseña
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                class="password-input"
                autocomplete="new-password"
            >

            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="password-group">
            <label class="password-label">
                Confirmar Nueva Contraseña
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="password-input"
                autocomplete="new-password"
            >

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="password-actions">
            <button type="submit" class="password-save-btn">
                Actualizar Contraseña
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="password-success"
                >
                    ✓ Contraseña actualizada
                </p>
            @endif
        </div>
    </form>

</section>