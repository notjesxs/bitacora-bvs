<x-app-layout>
    <style>
        .profile-page{
            padding:32px 24px;
            background:#f4f7fb;
            min-height:calc(100vh - 80px);
        }

        .profile-header{
            background:linear-gradient(135deg,#0f172a,#1d4ed8);
            color:white;
            border-radius:24px;
            padding:28px;
            margin-bottom:24px;
            box-shadow:0 18px 45px rgba(37,99,235,.22);
        }

        .profile-header h1{
            font-size:30px;
            font-weight:900;
            margin:0;
        }

        .profile-header p{
            margin-top:8px;
            color:#cbd5e1;
        }

        .profile-grid{
            max-width:1100px;
            margin:auto;
            display:grid;
            grid-template-columns:1fr;
            gap:22px;
        }

        .profile-card{
            background:white;
            border:1px solid #e5e7eb;
            border-radius:22px;
            padding:26px;
            box-shadow:0 12px 35px rgba(15,23,42,.08);
        }

        .profile-danger{
            border-color:#fecaca;
            background:#fffafa;
        }

        .profile-card .max-w-xl{
            max-width:100%;
        }
    </style>

    <div class="profile-page">
        <div class="profile-grid">

            <div class="profile-card">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="profile-card">
                @include('profile.partials.update-password-form')
            </div>

            {{-- <div class="profile-card profile-danger">
                @include('profile.partials.delete-user-form')
            </div> --}}

        </div>
    </div>
</x-app-layout>