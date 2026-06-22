<section>
<style>
    .profile-form-title{
        font-size:22px;
        font-weight:900;
        color:#0f172a;
        margin-bottom:6px;
    }

    .profile-form-description{
        color:#64748b;
        margin-bottom:24px;
    }

    .profile-group{
        margin-bottom:20px;
    }

    .profile-label{
        display:block;
        margin-bottom:8px;
        font-size:13px;
        font-weight:800;
        color:#334155;
        text-transform:uppercase;
    }

    .profile-input{
        width:100%;
        border:1px solid #dbe2ea;
        border-radius:14px;
        padding:14px 16px;
        font-size:15px;
        transition:.2s;
    }

    .profile-input:focus{
        outline:none;
        border-color:#2563eb;
        box-shadow:0 0 0 4px rgba(37,99,235,.12);
    }

    .profile-actions{
        display:flex;
        align-items:center;
        gap:12px;
        margin-top:25px;
    }

    .profile-save-btn{
        background:#2563eb;
        color:white;
        border:none;
        border-radius:14px;
        padding:12px 22px;
        font-weight:800;
        cursor:pointer;
        transition:.2s;
    }

    .profile-save-btn:hover{
        background:#1d4ed8;
    }

    .profile-success{
        color:#16a34a;
        font-weight:700;
    }

    .verification-box{
        margin-top:12px;
        padding:12px;
        border-radius:12px;
        background:#f8fafc;
        border:1px solid #e2e8f0;
    }

    .verification-link{
        color:#2563eb;
        font-weight:700;
        text-decoration:none;
        background:none;
        border:none;
        cursor:pointer;
    }
</style>

<header>
    <h2 class="profile-form-title">
        Información del Perfil
    </h2>

    <p class="profile-form-description">
        Actualiza tu nombre y correo electrónico.
    </p>
</header>

<form id="send-verification" method="POST" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="profile-group">
        <label class="profile-label">
            Nombre
        </label>

        <input
            type="text"
            name="name"
            class="profile-input"
            value="{{ old('name', $user->name) }}"
            required
        >

        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div class="profile-group">
        <label class="profile-label">
            Correo Electrónico
        </label>

        <input
            type="email"
            name="email"
            class="profile-input"
            value="{{ old('email', $user->email) }}"
            required
        >

        <x-input-error class="mt-2" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

            <div class="verification-box">

                <p>
                    Tu correo aún no ha sido verificado.
                </p>

                <button form="send-verification" class="verification-link">
                    Reenviar correo de verificación
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="profile-success" style="margin-top:10px;">
                        Correo de verificación enviado correctamente.
                    </p>
                @endif

            </div>

        @endif
    </div>

    <div class="profile-actions">

        <button type="submit" class="profile-save-btn">
            Guardar Cambios
        </button>

        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2500)"
                class="profile-success"
            >
                ✓ Información actualizada
            </p>
        @endif

    </div>

</form>


</section>
