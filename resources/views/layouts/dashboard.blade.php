<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — VETAPP</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --navy:#0A192F;--navy-light:#112240;--navy-mid:#1A3255;
    --mint:#64FFDA;--mint-dim:rgba(100,255,218,0.12);--mint-glow:rgba(100,255,218,0.3);
    --bg:#F5F7FA;--white:#fff;--text:#1F2937;--text-muted:#6B7280;
    --border:#E5E7EB;--sidebar-w:260px;--topbar-h:64px;
    --card-shadow:0 1px 3px rgba(0,0,0,.08),0 1px 2px rgba(0,0,0,.04);
    --card-shadow-hover:0 4px 16px rgba(0,0,0,.1);
    --bronze:#CD7F32;--silver:#9CA3AF;--gold:#F59E0B;
    --status-pending:#F59E0B;--status-confirmed:#10B981;
    --status-completed:#9CA3AF;--status-cancelled:#EF4444;
}
body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);font-size:15px;line-height:1.6;min-height:100vh}

/* ── SIDEBAR ── */
.sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--navy);display:flex;flex-direction:column;z-index:200;transition:transform .3s ease}
.sidebar-brand{padding:20px 20px 16px;border-bottom:1px solid rgba(255,255,255,.06)}
.sidebar-logo{display:flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:10px}
.sidebar-logo-icon{width:34px;height:34px;background:var(--mint);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:17px}
.sidebar-logo-text{font-family:'Poppins',sans-serif;font-weight:800;font-size:1.25rem;color:#fff}
.sidebar-logo-text span{color:var(--mint)}
.sidebar-clinic{font-size:.78rem;color:rgba(255,255,255,.5);margin-bottom:8px;padding-left:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pkg-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:50px;font-size:.68rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase}
.pkg-badge.bronze{background:rgba(205,127,50,.15);color:var(--bronze);border:1px solid rgba(205,127,50,.3)}
.pkg-badge.silver{background:rgba(156,163,175,.15);color:var(--silver);border:1px solid rgba(156,163,175,.3)}
.pkg-badge.gold{background:rgba(245,158,11,.15);color:var(--gold);border:1px solid rgba(245,158,11,.3)}

.sidebar-nav{flex:1;overflow-y:auto;padding:12px 0}
.sidebar-nav::-webkit-scrollbar{width:4px}
.sidebar-nav::-webkit-scrollbar-track{background:transparent}
.sidebar-nav::-webkit-scrollbar-thumb{background:rgba(255,255,255,.1);border-radius:2px}
.nav-section-label{font-size:.65rem;font-weight:700;color:rgba(255,255,255,.25);text-transform:uppercase;letter-spacing:1px;padding:12px 20px 6px}
.nav-item{display:flex;align-items:center;gap:11px;padding:10px 20px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.855rem;font-weight:500;transition:all .2s;position:relative;cursor:pointer;border:none;background:none;width:100%;text-align:left}
.nav-item:hover{color:rgba(255,255,255,.9);background:rgba(255,255,255,.04)}
.nav-item.active{color:var(--mint);background:var(--mint-dim)}
.nav-item.active::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:60%;background:var(--mint);border-radius:0 3px 3px 0}
.nav-item-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0}
.nav-item-lock{margin-left:auto;font-size:.7rem;opacity:.5}
.nav-item.locked{opacity:.5;cursor:not-allowed}
.nav-item.locked:hover{background:none;color:rgba(255,255,255,.55)}

.sidebar-bottom{padding:12px 12px 20px;border-top:1px solid rgba(255,255,255,.06)}
.sidebar-user{display:flex;align-items:center;gap:10px;padding:10px;border-radius:10px;background:rgba(255,255,255,.04);margin-bottom:8px}
.user-avatar{width:34px;height:34px;border-radius:50%;background:var(--mint-dim);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:var(--mint);font-weight:700;font-family:'Poppins',sans-serif}
.user-info{flex:1;min-width:0}
.user-name{font-size:.8rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.user-plan{font-size:.68rem;color:rgba(255,255,255,.4)}
.nav-item.logout{color:rgba(255,100,100,.7)}
.nav-item.logout:hover{color:#F87171;background:rgba(239,68,68,.08)}

/* ── TOPBAR ── */
.topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 28px;gap:20px;z-index:100}
.topbar-title{font-family:'Poppins',sans-serif;font-size:1.15rem;font-weight:700;color:var(--navy);white-space:nowrap}
.topbar-search{flex:1;max-width:380px;position:relative}
.topbar-search input{width:100%;padding:9px 16px 9px 38px;border:1.5px solid var(--border);border-radius:10px;font-size:.85rem;font-family:'Inter',sans-serif;color:var(--text);background:var(--bg);outline:none;transition:border-color .2s}
.topbar-search input:focus{border-color:var(--mint);background:#fff}
.topbar-search::before{content:'🔍';position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:.85rem}
.topbar-right{margin-left:auto;display:flex;align-items:center;gap:14px}
.sms-indicator{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:6px 12px}
.sms-ind-label{font-size:.72rem;color:var(--text-muted);font-weight:600}
.sms-ind-val{font-size:.8rem;font-weight:700;color:var(--navy)}
.sms-ind-val.warn{color:#F59E0B}
.sms-ind-val.danger{color:#EF4444}
.notif-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;position:relative;display:flex;align-items:center;justify-content:center;font-size:1.1rem;transition:all .2s}
.notif-btn:hover{border-color:var(--mint);background:var(--mint-dim)}
.notif-badge{position:absolute;top:-4px;right:-4px;width:17px;height:17px;background:#EF4444;border-radius:50%;font-size:.6rem;font-weight:700;color:#fff;display:flex;align-items:center;justify-content:center;border:2px solid #fff}
.user-menu-btn{display:flex;align-items:center;gap:9px;padding:6px 12px;border-radius:10px;border:1.5px solid var(--border);background:var(--white);cursor:pointer;transition:all .2s}
.user-menu-btn:hover{border-color:var(--mint)}
.user-menu-avatar{width:28px;height:28px;border-radius:50%;background:var(--mint-dim);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--navy);font-family:'Poppins',sans-serif}
.user-menu-name{font-size:.82rem;font-weight:600;color:var(--text)}
.hamburger-btn{display:none;width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;cursor:pointer;flex-direction:column;align-items:center;justify-content:center;gap:4px}
.hamburger-btn span{width:16px;height:2px;background:var(--text);border-radius:2px;display:block}

/* ── CONTENT ── */
.content{margin-left:var(--sidebar-w);margin-top:var(--topbar-h);padding:28px;min-height:calc(100vh - var(--topbar-h))}

/* ── CARDS ── */
.card{background:var(--white);border-radius:14px;padding:22px;box-shadow:var(--card-shadow);border:1px solid var(--border);transition:box-shadow .2s}
.card:hover{box-shadow:var(--card-shadow-hover)}
.card-title{font-family:'Poppins',sans-serif;font-size:.9rem;font-weight:700;color:var(--navy);margin-bottom:4px}
.card-sub{font-size:.75rem;color:var(--text-muted)}

/* ── WIDGET GRID ── */
.widget-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px}
.widget{background:var(--white);border-radius:14px;padding:20px;box-shadow:var(--card-shadow);border:1px solid var(--border);transition:all .2s}
.widget:hover{box-shadow:var(--card-shadow-hover);transform:translateY(-2px)}
.widget-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px}
.widget-label{font-size:.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px}
.widget-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem}
.widget-icon.mint{background:var(--mint-dim)}
.widget-icon.gold{background:rgba(245,158,11,.12)}
.widget-icon.blue{background:rgba(59,130,246,.1)}
.widget-icon.red{background:rgba(239,68,68,.1)}
.widget-num{font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:var(--navy);line-height:1;margin-bottom:6px}
.widget-detail{font-size:.78rem;color:var(--text-muted)}
.widget-detail.warn{color:#F59E0B;font-weight:600}
.widget-detail.danger{color:#EF4444;font-weight:600}

/* Progress bar */
.progress-wrap{margin-top:10px}
.progress-bar{height:6px;background:#F3F4F6;border-radius:3px;overflow:hidden}
.progress-fill{height:100%;background:var(--mint);border-radius:3px;transition:width .8s ease}
.progress-fill.warn{background:#F59E0B}
.progress-fill.danger{background:#EF4444}
.progress-label{display:flex;justify-content:space-between;font-size:.7rem;color:var(--text-muted);margin-top:4px}

/* ── TABLE ── */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead tr{border-bottom:2px solid var(--bg)}
th{padding:11px 14px;font-size:.75rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;text-align:left;white-space:nowrap}
td{padding:12px 14px;font-size:.855rem;color:var(--text);border-bottom:1px solid var(--bg)}
tbody tr:hover{background:#FAFBFC}
tbody tr:last-child td{border-bottom:none}

/* Status badge */
.badge{display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:50px;font-size:.72rem;font-weight:700}
.badge::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor}
.badge.pending{background:#FEF3C7;color:#92400E}
.badge.confirmed{background:#D1FAE5;color:#065F46}
.badge.completed{background:#F3F4F6;color:#4B5563}
.badge.cancelled{background:#FEE2E2;color:#991B1B}

/* Action buttons */
.action-btn{padding:5px 12px;border-radius:7px;font-size:.75rem;font-weight:600;cursor:pointer;border:1.5px solid;transition:all .2s;text-decoration:none;display:inline-block}
.action-btn.view{border-color:#E5E7EB;color:var(--text-muted)}
.action-btn.view:hover{border-color:var(--navy);color:var(--navy)}
.action-btn.edit{border-color:rgba(100,255,218,.4);color:#0A192F;background:rgba(100,255,218,.1)}
.action-btn.edit:hover{background:var(--mint);border-color:var(--mint)}
.action-btn.cancel{border-color:rgba(239,68,68,.3);color:#EF4444}
.action-btn.cancel:hover{background:#FEE2E2}

/* ── CONTENT GRID ── */
.content-grid{display:grid;grid-template-columns:1fr 320px;gap:20px}
.content-grid.full{grid-template-columns:1fr}

/* ── SECTION HEADER ── */
.section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px}
.section-head h3{font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:var(--navy)}
.btn-add{display:inline-flex;align-items:center;gap:7px;padding:8px 18px;background:var(--mint);color:var(--navy);border:none;border-radius:9px;font-size:.83rem;font-weight:700;cursor:pointer;transition:all .2s;text-decoration:none;font-family:'Inter',sans-serif}
.btn-add:hover{background:#4df0ca;box-shadow:0 3px 12px var(--mint-glow);transform:translateY(-1px)}
.btn-outline{display:inline-flex;align-items:center;gap:7px;padding:8px 18px;background:transparent;color:var(--navy);border:1.5px solid var(--border);border-radius:9px;font-size:.83rem;font-weight:600;cursor:pointer;transition:all .2s;text-decoration:none;font-family:'Inter',sans-serif}
.btn-outline:hover{border-color:var(--navy);background:var(--navy);color:#fff}

/* ── ACTIVITY FEED ── */
.activity-item{display:flex;gap:12px;padding:11px 0;border-bottom:1px solid var(--bg)}
.activity-item:last-child{border-bottom:none}
.act-dot{width:8px;height:8px;border-radius:50%;background:var(--mint);flex-shrink:0;margin-top:6px}
.act-dot.yellow{background:#F59E0B}
.act-dot.red{background:#EF4444}
.act-text{font-size:.82rem;color:var(--text)}
.act-time{font-size:.72rem;color:var(--text-muted);margin-top:2px}

/* ── QUICK ACTIONS ── */
.quick-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:4px}
.quick-btn{padding:12px;border-radius:10px;background:var(--bg);border:1.5px solid var(--border);cursor:pointer;text-align:center;transition:all .2s;text-decoration:none;display:block}
.quick-btn:hover{border-color:var(--mint);background:var(--mint-dim);transform:translateY(-2px)}
.quick-btn-icon{font-size:1.3rem;margin-bottom:5px}
.quick-btn-label{font-size:.75rem;font-weight:600;color:var(--navy)}

/* ── STAR RATING ── */
.stars{color:#F59E0B;font-size:1rem;letter-spacing:2px}
.rating-big{font-family:'Poppins',sans-serif;font-size:2.5rem;font-weight:800;color:var(--navy);line-height:1}

/* ── UPGRADE MODAL ── */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:500;display:none;align-items:center;justify-content:center}
.modal-overlay.open{display:flex}
.modal-box{background:#fff;border-radius:20px;padding:36px;max-width:480px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);animation:modalIn .3s ease}
@keyframes modalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.modal-close{position:absolute;top:16px;right:16px;width:32px;height:32px;border-radius:8px;border:none;background:var(--bg);cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center}
.modal-box{position:relative}
.modal-icon{font-size:2.5rem;margin-bottom:12px}
.modal-title{font-family:'Poppins',sans-serif;font-size:1.2rem;font-weight:800;color:var(--navy);margin-bottom:8px}
.modal-desc{font-size:.88rem;color:var(--text-muted);margin-bottom:20px;line-height:1.6}
.modal-btn{display:block;width:100%;padding:13px;background:var(--mint);color:var(--navy);border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;text-align:center;text-decoration:none;transition:all .2s}
.modal-btn:hover{background:#4df0ca}

/* ── TOAST ── */
.toast-container{position:fixed;bottom:24px;right:24px;z-index:600;display:flex;flex-direction:column;gap:10px}
.toast{padding:14px 18px;border-radius:12px;background:#fff;box-shadow:0 4px 20px rgba(0,0,0,.15);display:flex;align-items:center;gap:12px;min-width:280px;border-left:4px solid;animation:toastIn .3s ease}
@keyframes toastIn{from{opacity:0;transform:translateX(20px)}to{opacity:1;transform:translateX(0)}}
.toast.success{border-color:var(--mint)}
.toast.error{border-color:#EF4444}
.toast-text{font-size:.85rem;font-weight:500}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
    .widget-grid{grid-template-columns:repeat(2,1fr)}
    .content-grid{grid-template-columns:1fr}
}
@media(max-width:768px){
    .sidebar{transform:translateX(-100%)}
    .sidebar.open{transform:translateX(0)}
    .topbar{left:0}
    .content{margin-left:0}
    .hamburger-btn{display:flex}
    .topbar-search{display:none}
    .widget-grid{grid-template-columns:1fr 1fr}
}
@media(max-width:480px){
    .widget-grid{grid-template-columns:1fr}
    .content{padding:16px}
}
</style>
@stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">🐾</div>
            <div class="sidebar-logo-text">VET<span>APP</span></div>
        </a>
        <div class="sidebar-clinic">{{ auth()->user()->clinic_name ?? auth()->user()->name }}</div>
        <span class="pkg-badge {{ auth()->user()->subscription_plan }}">
            @if(auth()->user()->isBronze()) 🥉 Bronz
            @elseif(auth()->user()->isSilver()) 🥈 Gümüş
            @else 🥇 Altın
            @endif
        </span>
    </div>

   <nav class="sidebar-nav">
    <div class="nav-section-label">Ana Menü</div>
    <a href="{{ route('dashboard.'.auth()->user()->subscription_plan) }}"
       class="nav-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
        <span class="nav-item-icon">📊</span> Dashboard
    </a>
    <a href="{{ route('appointments.index') }}"
       class="nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
        <span class="nav-item-icon">📅</span> Randevular
    </a>
    <a href="{{ route('pets.index') }}"
       class="nav-item {{ request()->routeIs('pets.*') ? 'active' : '' }}">
        <span class="nav-item-icon">🐾</span> Hastalar
    </a>
    <a href="{{ route('vaccines.index') }}"
       class="nav-item {{ request()->routeIs('vaccines.*') ? 'active' : '' }}">
        <span class="nav-item-icon">💉</span> Aşı Takibi
    </a>

    {{-- Gümüş & Üzeri --}}
    <div class="nav-section-label">Gümüş & Üzeri</div>

    @if(auth()->user()->canAccess('silver'))
        <a href="{{ route('weight.index') }}"
           class="nav-item {{ request()->routeIs('weight.*') ? 'active' : '' }}">
            <span class="nav-item-icon">📈</span> Kilo Takibi
        </a>
        <a href="{{ route('announcements.index') }}"
           class="nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
            <span class="nav-item-icon">📣</span> Duyurular
        </a>
        <a href="{{ route('waiting.index') }}"
           class="nav-item {{ request()->routeIs('waiting.*') ? 'active' : '' }}">
            <span class="nav-item-icon">⏳</span> Bekleme Listesi
        </a>
        <a href="{{ route('surveys.index') }}"
           class="nav-item {{ request()->routeIs('surveys.*') ? 'active' : '' }}">
            <span class="nav-item-icon">😊</span> Anketler
        </a>
    @else
        @foreach([
            ['icon'=>'📈','label'=>'Kilo Takibi'],
            ['icon'=>'📣','label'=>'Duyurular'],
            ['icon'=>'⏳','label'=>'Bekleme Listesi'],
            ['icon'=>'😊','label'=>'Anketler'],
        ] as $item)
        <a href="#" class="nav-item locked"
           onclick="openUpgradeModal('silver');return false;">
            <span class="nav-item-icon">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
            <span class="nav-item-lock">🔒</span>
        </a>
        @endforeach
    @endif

    {{-- Sadece Altın --}}
    <div class="nav-section-label">Altın</div>

    @if(auth()->user()->canAccess('gold'))
        <a href="{{ route('surgeries.index') }}"
           class="nav-item {{ request()->routeIs('surgeries.*') ? 'active' : '' }}">
            <span class="nav-item-icon">🔪</span> Ameliyat Takvimi
        </a>
        <a href="{{ route('doctors.index') }}"
           class="nav-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
            <span class="nav-item-icon">🩺</span> Doktor Yönetimi
        </a>
        <a href="{{ route('treatments.index') }}"
           class="nav-item {{ request()->routeIs('treatments.*') ? 'active' : '' }}">
            <span class="nav-item-icon">📋</span> Tedavi Planları
        </a>
        <a href="{{ route('reports.index') }}"
           class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <span class="nav-item-icon">📊</span> Gelişmiş Raporlar
        </a>
    @else
        @foreach([
            ['icon'=>'🔪','label'=>'Ameliyat Takvimi'],
            ['icon'=>'🩺','label'=>'Doktor Yönetimi'],
            ['icon'=>'📋','label'=>'Tedavi Planları'],
            ['icon'=>'📊','label'=>'Gelişmiş Raporlar'],
        ] as $item)
        <a href="#" class="nav-item locked"
           onclick="openUpgradeModal('gold');return false;">
            <span class="nav-item-icon">{{ $item['icon'] }}</span>
            {{ $item['label'] }}
            <span class="nav-item-lock">🔒</span>
        </a>
        @endforeach
    @endif
</nav>

    <div class="sidebar-bottom">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-plan">{{ auth()->user()->clinic_name }}</div>
            </div>
        </div>
        <a href="{{ route('settings.index') }}"
   class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
   <span class="nav-item-icon">⚙️</span> Ayarlar
</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="nav-item logout" style="width:100%">
                <span class="nav-item-icon">🚪</span> Çıkış Yap
            </button>
        </form>
    </div>
</aside>

{{-- TOPBAR --}}
<header class="topbar">
    <button class="hamburger-btn" id="hamburgerBtn">
        <span></span><span></span><span></span>
    </button>
    <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
    <div class="topbar-search">
        <input type="text" placeholder="Hasta veya randevu ara...">
    </div>
    <div class="topbar-right">
        @if(!auth()->user()->isGold())
        @php $sms = auth()->user()->smsUsage; @endphp
        @if($sms)
        <div class="sms-indicator">
            <span class="sms-ind-label">SMS</span>
            <span class="sms-ind-val {{ $sms->remaining() < 20 ? ($sms->remaining() == 0 ? 'danger' : 'warn') : '' }}">
                {{ $sms->remaining() }} / {{ $sms->total_sms }}
            </span>
        </div>
        @endif
        @endif
        <button class="notif-btn">🔔<div class="notif-badge">3</div></button>
        <div class="user-menu-btn">
            <div class="user-menu-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <span class="user-menu-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
            <span style="font-size:.7rem;color:var(--text-muted)">▾</span>
        </div>
    </div>
</header>

{{-- CONTENT --}}
<main class="content">
    @if(session('success'))
    <div class="toast-container" id="toastContainer">
        <div class="toast success">✅ <span class="toast-text">{{ session('success') }}</span></div>
    </div>
    @endif
    @yield('content')
</main>

{{-- UPGRADE MODAL --}}
<div class="modal-overlay" id="upgradeModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeUpgradeModal()">✕</button>
        <div class="modal-icon">🔒</div>
        <div class="modal-title" id="modalTitle">Bu özellik için paket yükseltme gerekli</div>
        <div class="modal-desc" id="modalDesc">Bu özelliğe erişmek için paketinizi yükseltmeniz gerekiyor.</div>
        <a href="{{ route('home') }}#packages" class="modal-btn">Paketleri İncele →</a>
    </div>
</div>

<script>
// ── HAMBURGer ─────────────────────────────────────────────
document.getElementById('hamburgerBtn').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('open');
});

// ── TOAST ─────────────────────────────────────────────────
const tc = document.getElementById('toastContainer');
if (tc) setTimeout(() => {
    tc.style.opacity = '0';
    tc.style.transition = 'opacity .5s';
    setTimeout(() => tc.remove(), 500);
}, 3500);

// ── UPGRADE MODAL ─────────────────────────────────────────
function openUpgradeModal(plan) {
    const titles = { silver: 'Gümüş Paket Gerekli', gold: 'Altın Paket Gerekli' };
    const descs  = {
        silver: 'Bu özellik Gümüş ve Altın paketlerde mevcuttur.',
        gold:   'Bu özellik yalnızca Altın pakette mevcuttur.'
    };
    document.getElementById('modalTitle').textContent = titles[plan];
    document.getElementById('modalDesc').textContent  = descs[plan];
    document.getElementById('upgradeModal').classList.add('open');
}
function closeUpgradeModal() {
    document.getElementById('upgradeModal').classList.remove('open');
}
document.getElementById('upgradeModal').addEventListener('click', function(e) {
    if (e.target === this) closeUpgradeModal();
});

// ── BİLDİRİM PANELİ ──────────────────────────────────────
const notifBtn = document.querySelector('.notif-btn');
let notifPanel = null;
notifBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    if (notifPanel) { notifPanel.remove(); notifPanel = null; return; }

    notifPanel = document.createElement('div');
    notifPanel.style.cssText = `
        position:fixed;top:68px;right:80px;width:320px;
        background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,.15);
        border:1px solid var(--border);z-index:999;overflow:hidden;
        animation:toastIn .2s ease;
    `;
    notifPanel.innerHTML = `
        <div style="padding:16px 18px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:.95rem;color:var(--navy)">🔔 Bildirimler</div>
            <span style="font-size:.72rem;background:#FEE2E2;color:#991B1B;padding:3px 8px;border-radius:50px;font-weight:700">3 yeni</span>
        </div>
        <div>
            ${[
                { icon:'📅', text:'Yarın 3 randevunuz var', time:'Az önce', color:'var(--mint-dim)' },
                { icon:'💉', text:'Buddy\'nin aşısı 3 gün sonra', time:'1 saat önce', color:'rgba(245,158,11,.1)' },
                { icon:'⏳', text:'Bekleme listesinde 2 yeni kişi', time:'2 saat önce', color:'rgba(239,68,68,.08)' },
            ].map(n => `
                <div style="padding:13px 18px;border-bottom:1px solid var(--border);display:flex;gap:12px;align-items:flex-start;cursor:pointer;transition:background .15s"
                    onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                    <div style="width:34px;height:34px;border-radius:10px;background:${n.color};display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">${n.icon}</div>
                    <div>
                        <div style="font-size:.83rem;font-weight:600;color:var(--text)">${n.text}</div>
                        <div style="font-size:.72rem;color:var(--text-muted);margin-top:2px">${n.time}</div>
                    </div>
                </div>
            `).join('')}
        </div>
        <div style="padding:12px 18px;text-align:center">
            <a href="#" style="font-size:.8rem;font-weight:700;color:var(--navy);text-decoration:none">Tümünü gör →</a>
        </div>
    `;
    document.body.appendChild(notifPanel);
});

// ── KULLANICI MENÜSÜ ──────────────────────────────────────
const userMenuBtn = document.querySelector('.user-menu-btn');
let userMenu = null;
userMenuBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    if (userMenu) { userMenu.remove(); userMenu = null; return; }

    userMenu = document.createElement('div');
    userMenu.style.cssText = `
        position:fixed;top:68px;right:16px;width:220px;
        background:#fff;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,.15);
        border:1px solid var(--border);z-index:999;overflow:hidden;
        animation:toastIn .2s ease;
    `;
    userMenu.innerHTML = `
        <div style="padding:14px 16px;border-bottom:1px solid var(--border);background:var(--bg)">
            <div style="font-weight:700;font-size:.88rem;color:var(--navy)">{{ auth()->user()->name }}</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">{{ auth()->user()->clinic_name }}</div>
            <span style="font-size:.68rem;padding:2px 8px;border-radius:50px;font-weight:700;margin-top:6px;display:inline-block;
                background:{{ auth()->user()->isGold() ? 'rgba(245,158,11,.15)' : (auth()->user()->isSilver() ? 'rgba(156,163,175,.15)' : 'rgba(205,127,50,.15)') }};
                color:{{ auth()->user()->isGold() ? '#F59E0B' : (auth()->user()->isSilver() ? '#9CA3AF' : '#CD7F32') }}">
                {{ auth()->user()->isGold() ? '🥇 Altın' : (auth()->user()->isSilver() ? '🥈 Gümüş' : '🥉 Bronz') }} Paket
            </span>
        </div>
        <div>
            <a href="#" style="display:flex;align-items:center;gap:10px;padding:11px 16px;font-size:.85rem;font-weight:500;color:var(--text);text-decoration:none;transition:background .15s"
                onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                ⚙️ <span>Hesap Ayarları</span>
            </a>
            <a href="{{ route('home') }}#packages" style="display:flex;align-items:center;gap:10px;padding:11px 16px;font-size:.85rem;font-weight:500;color:var(--text);text-decoration:none;transition:background .15s"
                onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                📦 <span>Paket Yükselt</span>
            </a>
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:10px;padding:11px 16px;font-size:.85rem;font-weight:500;color:var(--text);text-decoration:none;transition:background .15s"
                onmouseover="this.style.background='var(--bg)'" onmouseout="this.style.background=''">
                🌐 <span>Ana Siteye Git</span>
            </a>
            <div style="border-top:1px solid var(--border)">
                <form method="POST" action="{{ route('logout') }}" style="margin:0">
                    @csrf
                    <button type="submit" style="width:100%;display:flex;align-items:center;gap:10px;padding:11px 16px;font-size:.85rem;font-weight:500;color:#EF4444;background:none;border:none;cursor:pointer;text-align:left;transition:background .15s"
                        onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background=''">
                        🚪 <span>Çıkış Yap</span>
                    </button>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(userMenu);
});

// ── DIŞARI TIKLAYINCA KAPAT ───────────────────────────────
document.addEventListener('click', () => {
    if (notifPanel) { notifPanel.remove(); notifPanel = null; }
    if (userMenu)   { userMenu.remove();   userMenu = null; }
});

// ── RANDEVU EKLE MODAL ────────────────────────────────────
function openAppointmentModal() {
    const existing = document.getElementById('apptModal');
    if (existing) { existing.remove(); return; }

    // Hastalara route üzerinden erişemeyiz, basit bir yönlendirme yapalım
    window.location.href = '{{ route("appointments.index") }}';
}

// ── HIZLI İŞLEM BUTONLARI ────────────────────────────────
// Dashboard'daki hızlı işlem butonlarına tıklayınca ilgili sayfaya yönlendir
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.quick-btn').forEach(btn => {
        const label = btn.querySelector('.quick-btn-label')?.textContent?.trim();
        const icon  = btn.querySelector('.quick-btn-icon')?.textContent?.trim();

        const routes = {
            'Randevu':   '{{ route("appointments.index") }}',
            'Hasta Ekle':'{{ route("pets.index") }}',
            'Hasta':     '{{ route("pets.index") }}',
            'Geçmiş':    '{{ route("appointments.index") }}',
            'Harita':    'https://maps.google.com',
            'Bekleme':   '{{ route("waiting.index") }}',
            'Duyuru':    '{{ route("announcements.index") }}',
            @if(auth()->user()->canAccess('gold'))
            'Ameliyat':  '{{ route("surgeries.index") }}',
            'Raporlar':  '{{ route("reports.index") }}',
            'Doktorlar': '{{ route("doctors.index") }}',
            @endif
        };

        const url = routes[label];
        if (url) {
            btn.style.cursor = 'pointer';
            btn.addEventListener('click', () => {
                if (url.startsWith('http')) window.open(url, '_blank');
                else window.location.href = url;
            });
        }
    });

    // Dashboard'daki "+ Yeni Randevu" butonları
    document.querySelectorAll('.btn-add').forEach(btn => {
        if (btn.textContent.includes('Yeni Randevu') && btn.getAttribute('href') === '#') {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = '{{ route("appointments.index") }}';
            });
        }
    });
});
</script>
@stack('scripts')
</body>
</html>