<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VETAPP — Veteriner Klinikleri İçin Akıllı Randevu Sistemi')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vetapp.css') }}">
    @stack('styles')
</head>
<body>

{{-- NAVİGASYON --}}
<nav id="navbar">
    <a class="nav-logo" href="{{ route('home') }}">
        <div class="nav-logo-icon">🐾</div>
        VET<span>APP</span>
    </a>
    <ul class="nav-links">
        <li><a href="#features">Özellikler</a></li>
        <li><a href="#packages">Paketler</a></li>
        <li><a href="#why">Neden Biz</a></li>
        <li><a href="#contact">İletişim</a></li>
    </ul>
    <div class="nav-actions">
    @auth
        <span style="color:rgba(255,255,255,.7);font-size:.85rem;font-weight:500;padding:8px 4px">
            🏥 {{ auth()->user()->clinic_name ?? auth()->user()->name }}
        </span>
        <a href="{{ route('dashboard.'.auth()->user()->subscription_plan) }}" class="btn-ghost">
            Dashboard
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;display:inline">
            @csrf
            <button type="submit" class="btn-primary" style="cursor:pointer;border:none">
                Çıkış Yap
            </button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn-ghost">Giriş Yap</a>
        <a href="{{ route('register') }}" class="btn-primary">Ücretsiz Dene</a>
    @endauth
</div>
    <button class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- SAYFA İÇERİĞİ --}}
@yield('content')

{{-- FOOTER --}}
<footer>
    <div class="footer-top">
        <div>
            <div class="footer-brand-logo">
                <div class="nav-logo-icon" style="width:28px;height:28px;font-size:14px">🐾</div>
                VET<span>APP</span>
            </div>
            <p class="footer-desc">Veteriner klinikleri için akıllı randevu ve yönetim sistemi. Türkiye'nin en kapsamlı veteriner platformu.</p>
            <div class="footer-socials">
                <a href="#" class="social-icon">📸</a>
                <a href="#" class="social-icon">📘</a>
                <a href="#" class="social-icon">💼</a>
                <a href="#" class="social-icon">▶️</a>
            </div>
        </div>
        <div>
            <div class="footer-col-title">Ürün</div>
            <ul class="footer-links">
                <li><a href="#features">Özellikler</a></li>
                <li><a href="#packages">Paketler</a></li>
                <li><a href="#why">Neden Biz</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-col-title">Şirket</div>
            <ul class="footer-links">
                <li><a href="#">Hakkımızda</a></li>
                <li><a href="#">Kariyer</a></li>
                <li><a href="#">Basın</a></li>
                <li><a href="#contact">İletişim</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-col-title">Destek</div>
            <ul class="footer-links">
                <li><a href="#">Yardım Merkezi</a></li>
                <li><a href="#">Gizlilik Politikası</a></li>
                <li><a href="#">Kullanım Koşulları</a></li>
                <li><a href="#">KVKK</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-copy">© {{ date('Y') }} VETAPP. Tüm hakları saklıdır.</div>
        <div class="payment-icons">
            <div class="pay-icon">VISA</div>
            <div class="pay-icon">MC</div>
            <div class="pay-icon">PayPal</div>
            <div class="pay-icon">Havale</div>
        </div>
    </div>
</footer>

<script>
    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    }, { threshold: .08, rootMargin: '0px 0px -30px 0px' });
    reveals.forEach(el => obs.observe(el));
    document.querySelectorAll('.packages-grid,.features-grid,.why-grid,.sms-grid').forEach(g => {
        g.querySelectorAll('.reveal').forEach((c, i) => { c.style.transitionDelay = i * 80 + 'ms'; });
    });

    // Navbar shadow on scroll
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').style.boxShadow = window.scrollY > 50 ? '0 4px 30px rgba(0,0,0,.3)' : 'none';
    });

    // Hamburger menü
    const hb = document.getElementById('hamburger');
    const nl = document.querySelector('.nav-links');
    let mo = false;
    hb.addEventListener('click', () => {
        mo = !mo;
        nl.style.cssText = mo
            ? 'display:flex;flex-direction:column;position:absolute;top:68px;left:0;right:0;background:rgba(10,25,47,.98);padding:20px 5%;gap:18px;border-bottom:1px solid rgba(100,255,218,.1)'
            : 'display:none';
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const id = a.getAttribute('href');
            if (id === '#') return;
            const el = document.querySelector(id);
            if (el) {
                e.preventDefault();
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (mo) { mo = false; nl.style.display = 'none'; }
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>