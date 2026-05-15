@extends('layouts.app')

@section('title', 'VETAPP — Veteriner Klinikleri İçin Akıllı Randevu Sistemi')

@section('content')

{{-- HERO --}}
<section class="hero" id="home">
    <div class="hero-grid">
        <div>
            <div class="hero-badge">
                <div class="hero-badge-dot"></div>
                Türkiye'nin #1 Veteriner Platformu
            </div>
            <h1>Veteriner Kliniğiniz İçin <em>Akıllı Randevu</em> Sistemi</h1>
            <p class="hero-sub">Aşı hatırlatmalarından dijital hasta kartına, online ödemeden ameliyat takvimine kadar her şey tek platformda.</p>
            <div class="hero-actions">
                <a href="#packages" class="btn-hero-primary">Hemen Başlayın →</a>
                <a href="#packages" class="btn-hero-outline">Paketleri İncele</a>
            </div>
            <div class="hero-stats">
                <div><div class="stat-num">500+</div><div class="stat-label">Aktif Klinik</div></div>
                <div><div class="stat-num">50K+</div><div class="stat-label">Randevu / Ay</div></div>
                <div><div class="stat-num">%98</div><div class="stat-label">Memnuniyet</div></div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="dashboard-mock">
                <div class="mock-header">
                    <div class="mock-dots">
                        <div class="mock-dot"></div><div class="mock-dot"></div><div class="mock-dot"></div>
                    </div>
                    <div class="mock-title">Klinik Paneli — Bugün</div>
                    <div class="mock-logo">VETAPP</div>
                </div>
                <div class="mock-body">
                    <div class="mock-sidebar">
                        <div class="mock-nav-item active">📅 Randevular</div>
                        <div class="mock-nav-item">🐾 Hastalar</div>
                        <div class="mock-nav-item">💉 Aşı Kartı</div>
                        <div class="mock-nav-item">📊 Raporlar</div>
                        <div class="mock-nav-item">⚙️ Ayarlar</div>
                    </div>
                    <div class="mock-content">
                        <div class="mock-stats-row">
                            <div class="mock-stat"><div class="mock-stat-num">12</div><div class="mock-stat-lbl">Bugün</div></div>
                            <div class="mock-stat"><div class="mock-stat-num">3</div><div class="mock-stat-lbl">Bekliyor</div></div>
                            <div class="mock-stat"><div class="mock-stat-num">47</div><div class="mock-stat-lbl">Bu Ay</div></div>
                        </div>
                        <div class="mock-card">
                            <div class="mock-card-title">Günün Randevuları</div>
                            <div class="mock-appt-item">
                                <div class="mock-avatar">🐕</div>
                                <div class="mock-appt-info"><div class="mock-appt-name">Buddy — Labrador</div><div class="mock-appt-time">09:30 · Aşı</div></div>
                                <div class="mock-badge green">Onaylı</div>
                            </div>
                            <div class="mock-appt-item">
                                <div class="mock-avatar">🐈</div>
                                <div class="mock-appt-info"><div class="mock-appt-name">Mimi — Kedi</div><div class="mock-appt-time">10:15 · Kontrol</div></div>
                                <div class="mock-badge yellow">Bekliyor</div>
                            </div>
                            <div class="mock-appt-item">
                                <div class="mock-avatar">🐇</div>
                                <div class="mock-appt-info"><div class="mock-appt-name">Pamuk — Tavşan</div><div class="mock-appt-time">11:00 · Röntgen</div></div>
                                <div class="mock-badge green">Onaylı</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- PAKETLER --}}
<section class="packages" id="packages">
    <div class="section-header reveal">
        <div class="section-tag">Fiyatlandırma</div>
        <h2 class="section-title">Size Özel Çözümler</h2>
        <p class="section-sub">Kliniğinizin büyüklüğüne göre 3 farklı paket — şeffaf fiyatlar, gizli maliyet yok.</p>
    </div>
    <div class="packages-grid">
        @foreach($packages as $pkg)
            <div class="pkg-card {{ $pkg['featured'] ? 'featured' : '' }} reveal">
                @if($pkg['featured'])
                    <div class="popular-badge">⭐ En Popüler</div>
                @endif
                <div class="pkg-icon">{{ $pkg['icon'] }}</div>
                <div class="pkg-name">{{ $pkg['name'] }}</div>
                <div class="pkg-price">
                    {{ $pkg['price'] }}
                    <span style="font-size:1rem;font-weight:500;color:{{ $pkg['featured'] ? 'rgba(255,255,255,.4)' : 'var(--text-muted)' }}">TL</span>
                </div>
                <div class="pkg-per">/ ay · KDV dahil</div>
                <div class="pkg-info">
                    <div class="pkg-info-item">📅 {{ $pkg['limit'] }}</div>
                    <div class="pkg-info-item">💬 {{ $pkg['sms'] }}</div>
                </div>
                <ul class="pkg-features">
                    @foreach($pkg['features'] as $feature)
                        <li><div class="feat-check">✓</div>{{ $feature }}</li>
                    @endforeach
                </ul>
                <a href="#contact" class="btn-pkg {{ $pkg['featured'] ? 'btn-pkg-mint' : 'btn-pkg-outline' }}">
                    {{ $pkg['name'] }}'i Seç
                </a>
            </div>
        @endforeach
    </div>
    <div class="whatsapp-note reveal">
        💚 <strong>Tüm paketlerde WhatsApp bildirimleri ÜCRETSİZ!</strong> Müşterilerinize anında hatırlatma gönderin.
    </div>
</section>

{{-- SMS PAKETLERİ --}}
<section class="sms-section" id="sms">
    <div style="max-width:1200px;margin:0 auto">
        <div class="section-header reveal">
            <div class="section-tag">SMS Eklentisi</div>
            <h2 class="section-title">SMS Paketleri</h2>
            <p class="section-sub">Bronz ve Gümüş paketler için esnek SMS seçenekleri.</p>
        </div>
        <div class="sms-grid reveal">
            @foreach($smsPackages as $sms)
                <div class="sms-card">
                    <div class="sms-amount">{{ $sms['amount'] }}</div>
                    <div class="sms-label">SMS</div>
                    <div class="sms-price">{{ $sms['price'] }} TL</div>
                </div>
            @endforeach
        </div>
        <div class="sms-note reveal">
            ⭐ <strong>Altın pakette</strong> SMS'ler tamamen <strong>sınırsız ve ücretsizdir!</strong>
        </div>
    </div>
</section>

{{-- ÖZELLİKLER --}}
<section class="features-section" id="features">
    <div class="section-header reveal">
        <div class="section-tag">Özellikler</div>
        <h2 class="section-title">VETAPP ile Neler Yapacaksınız?</h2>
        <p class="section-sub">Veteriner kliniklerinin ihtiyaç duyduğu her şey, eksiksiz ve hazır.</p>
    </div>
    <div class="features-grid">
        @foreach($features as $feat)
            <div class="feat-card reveal">
                <span class="feat-emoji">{{ $feat['emoji'] }}</span>
                <div class="feat-title">{{ $feat['title'] }}</div>
                <div class="feat-desc">{{ $feat['desc'] }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- NEDEN VETAPP --}}
<section class="why-section" id="why">
    <div class="section-header reveal" style="position:relative;z-index:1">
        <div class="section-tag">Neden Biz</div>
        <h2 class="section-title" style="color:var(--white)">Neden VETAPP?</h2>
        <p class="section-sub" style="color:rgba(255,255,255,.5)">Veteriner sektörünü yakından tanıyoruz. Her özellik gerçek ihtiyaçlardan doğdu.</p>
    </div>
    <div class="why-grid">
        @foreach($whyUs as $item)
            <div class="why-card reveal">
                <div class="why-icon">{{ $item['icon'] }}</div>
                <div class="why-title">{{ $item['title'] }}</div>
                <div class="why-desc">{{ $item['desc'] }}</div>
            </div>
        @endforeach
    </div>
</section>

{{-- İLETİŞİM --}}
<section class="contact-section" id="contact">
    <div class="contact-grid">
        <div class="contact-info reveal" style="padding-top:10px">
            <div class="section-tag" style="display:inline-block;margin-bottom:16px">Demo İste</div>
            <div class="contact-info-title">Hemen Demo İsteyin, Ücretsiz Deneyin</div>
            <p class="contact-info-sub">VETAPP'ı kliniğinizde 14 gün boyunca ücretsiz deneyin. Kredi kartı gerekmez, anında erişim.</p>
            <div class="contact-item">
                <div class="contact-item-icon">📞</div>
                <div class="contact-item-text"><label>Telefon</label><span>+90 (212) 555-00-00</span></div>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon">✉️</div>
                <div class="contact-item-text"><label>E-posta</label><span>info@vetapp.com</span></div>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon">💬</div>
                <div class="contact-item-text"><label>WhatsApp</label><span>+90 (532) 555-00-00</span></div>
            </div>
        </div>
        <div class="contact-form reveal">
            <form method="POST" action="{{ route('demo.store') }}" id="demoForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Adınız Soyadınız</label>
                    <input type="text" name="name" class="form-input" placeholder="Dr. Ayşe Kaya" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Klinik Adı</label>
                    <input type="text" name="clinic" class="form-input" placeholder="Pati Veteriner Kliniği" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Telefon</label>
                    <input type="tel" name="phone" class="form-input" placeholder="+90 (5__) ___-__-__" required>
                </div>
                <div class="form-group">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-input" placeholder="dr.ayse@klinik.com" required>
                </div>
                <button type="submit" class="btn-submit">Ücretsiz Demo Talep Et →</button>
            </form>

            @if(session('success'))
                <div style="margin-top:16px;padding:14px;background:rgba(100,255,218,.1);border:1px solid var(--mint);border-radius:10px;color:var(--navy);font-weight:600;font-size:.9rem">
                    ✓ {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</section>

@endsection