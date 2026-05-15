<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Giriş Yap — VETAPP</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Inter',sans-serif;min-height:100vh;display:grid;grid-template-columns:1fr 1fr;background:#0A192F}
/* SOL PANEL */
.left-panel{background:#0A192F;padding:48px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}
.left-panel::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 30% 50%,rgba(100,255,218,.07) 0%,transparent 70%)}
.left-logo{display:flex;align-items:center;gap:10px;text-decoration:none;position:relative;z-index:1}
.left-logo-icon{width:40px;height:40px;background:#64FFDA;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px}
.left-logo-text{font-family:'Poppins',sans-serif;font-weight:800;font-size:1.6rem;color:#fff}
.left-logo-text span{color:#64FFDA}
.left-center{position:relative;z-index:1}
.left-tagline{font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:#fff;line-height:1.2;margin-bottom:16px}
.left-tagline em{font-style:normal;color:#64FFDA}
.left-sub{color:rgba(255,255,255,.55);font-size:.95rem;line-height:1.7;max-width:380px}
.left-stats{display:flex;gap:32px;margin-top:40px}
.ls-item{}
.ls-num{font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:800;color:#64FFDA}
.ls-lbl{font-size:.75rem;color:rgba(255,255,255,.4);margin-top:3px}
.left-bottom{position:relative;z-index:1}
.left-bottom p{font-size:.78rem;color:rgba(255,255,255,.25)}
/* SAĞ PANEL */
.right-panel{background:#F5F7FA;display:flex;align-items:center;justify-content:center;padding:48px 40px}
.login-box{width:100%;max-width:420px}
.login-title{font-family:'Poppins',sans-serif;font-size:1.75rem;font-weight:800;color:#0A192F;margin-bottom:6px}
.login-sub{color:#6B7280;font-size:.9rem;margin-bottom:32px}
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:.82rem;font-weight:600;color:#374151;margin-bottom:7px}
.form-input{width:100%;padding:13px 16px;border:1.5px solid #E5E7EB;border-radius:10px;font-size:.9rem;font-family:'Inter',sans-serif;color:#1F2937;background:#fff;outline:none;transition:border-color .2s,box-shadow .2s}
.form-input:focus{border-color:#64FFDA;box-shadow:0 0 0 3px rgba(100,255,218,.15)}
.form-input.error{border-color:#EF4444}
.form-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.remember{display:flex;align-items:center;gap:8px;font-size:.83rem;color:#6B7280;cursor:pointer}
.remember input{accent-color:#64FFDA;width:15px;height:15px}
.forgot{font-size:.83rem;color:#0A192F;font-weight:600;text-decoration:none;transition:color .2s}
.forgot:hover{color:#64FFDA}
.btn-login{width:100%;padding:14px;background:#64FFDA;color:#0A192F;border:none;border-radius:10px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .25s;font-family:'Inter',sans-serif;margin-top:8px;letter-spacing:.2px}
.btn-login:hover{background:#4df0ca;box-shadow:0 4px 20px rgba(100,255,218,.4);transform:translateY(-1px)}
.btn-login:active{transform:translateY(0)}
.alert-error{background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;padding:12px 16px;margin-bottom:20px;font-size:.85rem;color:#991B1B;display:flex;align-items:flex-start;gap:10px}
.back-link{text-align:center;margin-top:24px}
.back-link a{font-size:.83rem;color:#6B7280;text-decoration:none;transition:color .2s;display:inline-flex;align-items:center;gap:5px}
.back-link a:hover{color:#0A192F}
@media(max-width:768px){
    body{grid-template-columns:1fr}
    .left-panel{display:none}
    .right-panel{background:#0A192F;align-items:flex-start;padding:40px 24px}
    .login-box{background:#F5F7FA;border-radius:20px;padding:32px 28px}
}
</style>
</head>
<body>

{{-- SOL PANEL --}}
<div class="left-panel">
    <a href="{{ route('home') }}" class="left-logo">
        <div class="left-logo-icon">🐾</div>
        <div class="left-logo-text">VET<span>APP</span></div>
    </a>
    <div class="left-center">
        <div class="left-tagline">Kliniğinizi <em>akıllı</em> yönetmenin zamanı geldi.</div>
        <p class="left-sub">Randevularınızı, hastalarınızı ve aşı takibini tek platformdan yönetin. Her şey kontrolünüzde.</p>
        <div class="left-stats">
            <div class="ls-item"><div class="ls-num">500+</div><div class="ls-lbl">Aktif Klinik</div></div>
            <div class="ls-item"><div class="ls-num">50K+</div><div class="ls-lbl">Randevu/Ay</div></div>
            <div class="ls-item"><div class="ls-num">%98</div><div class="ls-lbl">Memnuniyet</div></div>
        </div>
    </div>
    <div class="left-bottom">
        <p>© {{ date('Y') }} VETAPP. Tüm hakları saklıdır.</p>
    </div>
</div>

{{-- SAĞ PANEL --}}
<div class="right-panel">
    <div class="login-box">
        <div class="login-title">Tekrar hoş geldiniz 👋</div>
        <div class="login-sub">Klinik panelinize erişmek için giriş yapın.</div>

        @if($errors->any())
            <div class="alert-error">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-error">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">E-posta Adresi</label>
                <input type="email" name="email" class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                    placeholder="dr.ayse@klinik.com" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Şifre</label>
                
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <div class="form-row">
                <label class="remember">
                    <input type="checkbox" name="remember"> Beni hatırla
                </label>
               
                <div style="text-align:right;margin-top:-8px;margin-bottom:14px">
    <a href="{{ route('password.request') }}" style="font-size:.78rem;color:var(--text-muted);text-decoration:none">
        Şifremi unuttum →
    </a>
</div>
            </div>
            <button type="submit" class="btn-login">Giriş Yap →</button>
        </form>

        <div class="back-link">
            <a href="{{ route('home') }}">← Ana sayfaya dön</a>
        </div>
    </div>
</div>

</body>
</html>