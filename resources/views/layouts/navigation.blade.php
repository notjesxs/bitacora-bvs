<nav x-data="{ open: false }" class="app-navbar">

<style>
    .app-navbar{
        background:#0f172a;
        border-bottom:1px solid rgba(255,255,255,.08);
        position:sticky;
        top:0;
        z-index:999;
        box-shadow:0 4px 20px rgba(0,0,0,.15);
        margin-bottom:5px;
    }

    .nav-wrap{
        width:100%;
        padding:10px 28px;
        min-height:78px;
        display:flex;
        align-items:center;
        justify-content:space-between;
    }

    .brand{
        display:flex;
        align-items:center;
        gap:14px;
        text-decoration:none;
        color:white;
    }

    .brand-icon{
        width:52px;
        height:52px;
        border-radius:16px;
        background:linear-gradient(135deg,rgba(255,255,255,.06),rgba(255,255,255,.06)),#2563eb;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
        font-weight:900;
        box-shadow:0 8px 20px rgba(37,99,235,.35);
        flex-shrink:0;
    }

    .brand-info{
        display:flex;
        flex-direction:column;
        justify-content:center;
    }

    .brand-title{
        font-size:18px;
        font-weight:900;
        line-height:1;
        color:white;
    }

    .brand-subtitle{
        font-size:12px;
        color:#94a3b8;
        margin-top:6px;
    }

    .nav-actions{
        display:flex;
        align-items:center;
        gap:12px;
    }

    .user-dropdown{
        position:relative;
    }

    .user-box{
        display:flex;
        align-items:center;
        gap:10px;
        background:rgba(255,255,255,.06);
        border:1px solid rgba(255,255,255,.10);
        padding:8px 12px;
        border-radius:18px;
        cursor:pointer;
        min-width:230px;
    }

    .avatar{
        width:40px;
        height:40px;
        font-size:16px;
        border-radius:50%;
        background:#2563eb;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:900;
        color:white;
        flex-shrink:0;
    }

    .user-details{
        display:flex;
        flex-direction:column;
        min-width:0;
        flex:1;
    }

    .user-name{
        font-size:15px;
        font-weight:900;
        color:white;
        line-height:1;
    }

    .user-email{
        font-size:12px;
        color:#94a3b8;
        margin-top:5px;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
        max-width:150px;
    }

    .dropdown-arrow{
        color:#94a3b8;
        font-size:13px;
        margin-left:4px;
    }

    .user-menu{
        position:absolute;
        top:64px;
        right:0;
        width:220px;
        background:white;
        border-radius:16px;
        box-shadow:0 15px 40px rgba(0,0,0,.18);
        padding:8px;
        z-index:9999;
    }

    .user-menu-item{
        display:block;
        width:100%;
        padding:12px 14px;
        border-radius:12px;
        color:#0f172a;
        text-decoration:none;
        font-weight:800;
        font-size:14px;
        background:white;
        border:0;
        text-align:left;
        cursor:pointer;
    }

    .user-menu-item:hover{
        background:#f1f5f9;
    }

    .logout-item{
        color:#dc2626;
    }

    .mobile-btn{
        display:none;
        background:rgba(255,255,255,.08);
        border:1px solid rgba(255,255,255,.12);
        color:white;
        font-size:22px;
        cursor:pointer;
        border-radius:12px;
        padding:8px 12px;
    }

    .mobile-menu{
        display:none;
        padding:16px 24px 22px;
        border-top:1px solid rgba(255,255,255,.08);
        background:#0f172a;
    }

    .mobile-menu.open{
        display:block;
    }

    .nav-link-main{
        display:block;
        background:rgba(255,255,255,.06);
        color:white;
        padding:12px 16px;
        border-radius:12px;
        text-decoration:none;
        font-size:14px;
        font-weight:800;
        border:1px solid rgba(255,255,255,.08);
        margin-bottom:10px;
    }

    .logout-btn{
        width:100%;
        background:#ef4444;
        color:white;
        border:none;
        padding:12px 16px;
        font-size:14px;
        border-radius:12px;
        font-weight:800;
        cursor:pointer;
    }

    @media(max-width:768px){
        .desktop-only{
            display:none;
        }

        .mobile-btn{
            display:block;
        }

        .nav-wrap{
            padding:10px 18px;
            min-height:72px;
        }

        .brand-icon{
            width:44px;
            height:44px;
            font-size:18px;
        }

        .brand-title{
            font-size:16px;
        }

        .brand-subtitle{
            display:none;
        }
    }
</style>

<div class="nav-wrap">
    <a href="{{ route('bitacoras.index') }}" class="brand">
        <div class="brand-icon">
            BVS
        </div>

        <div class="brand-info">
            <span class="brand-title">Bitácora BVS</span>
            <span class="brand-subtitle">Gestión interna de casos</span>
        </div>
    </a>

    <div class="nav-actions desktop-only">
        <div x-data="{ userMenu: false }" class="user-dropdown">
            <button @click="userMenu = ! userMenu" class="user-box" type="button">
                <div class="avatar">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>

                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-email">{{ Auth::user()->email }}</div>
                </div>

                <div class="dropdown-arrow">▾</div>
            </button>

            <div x-show="userMenu"
                 @click.away="userMenu = false"
                 x-transition
                 class="user-menu">

                <a href="{{ route('profile.edit') }}" class="user-menu-item">
                    Mi Perfil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="user-menu-item logout-item">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <button @click="open = ! open" class="mobile-btn">
        ☰
    </button>
</div>

<div :class="{ 'open': open }" class="mobile-menu">


    <a href="{{ route('profile.edit') }}" class="nav-link-main">
        Mi Perfil
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            Cerrar sesión
        </button>
    </form>
</div>

</nav>