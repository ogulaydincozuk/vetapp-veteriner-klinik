<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kayıt Ol — VETAPP</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#F5F7FA;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.container{display:grid;grid-template-columns:1fr 1fr;max-width:960px;width:100%;background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.1)}
.left{background:#0A192F;padding:48px 40px;display:flex;flex-direction:column;justify-content:center}
.logo{font-family:'Poppins',sans-serif;font-size:1.8rem;font-weight:800;color:#64FFDA;margin-bottom:32px}
.left h2{font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:12px}
.left p{color:rgba(255,255,255,.5);font-size:.88rem;line-height:1.6;margin-bottom:28px}
.plan-card{padding:14px 16px;border-radius:12px;margin-bottom:10px;cursor:pointer;border:1.5px solid transparent;transition:all .2s}
.plan-card.selected{border-color:#64FFDA;background:rgba(100,255,218,.08)}
.plan-name{font-weight:700;font-size:.9rem;color:#fff}
.plan-price{font-size:.78rem;color:rgba(255,255,255,.4);margin-top:2px}
.right{padding:48px 40px;overflow-y:auto;max-height:100vh}
.right h2{font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:700;color:#0A192F;margin-bottom:6px}
.right p{font-size:.85rem;color:#6B7280;margin-bottom:28px}
.form-group{margin-bottom:14px}
label{font-size:.8rem;font-weight:600;color:#1F2937;display:block;margin-bottom:5px}
input,select{width:100%;padding:11px 14px;border:1.5px solid #E5E7EB;border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:#1F2937;outline:none;transition:border-color .2s;background:#fff}
input:focus,select:focus{border-color:#64FFDA}
.slug-wrap{position:relative}
.slug-prefix{position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:.83rem;font-weight:600;color:#9CA3AF}
.slug-wrap input{padding-left:90px}
.slug-status{font-size:.73rem;margin-top:4px;font-weight:600}
.slug-status.ok{color:#10B981}
.slug-status.no{color:#EF4444}
.btn{width:100%;padding:13px;background:#64FFDA;color:#0A192F;font-weight:700;font-size:.95rem;border:none;border-radius:12px;cursor:pointer;font-family:'Poppins',sans-serif;transition:opacity .2s;margin-top:8px}
.btn:hover{opacity:.85}
.divider{text-align:center;font-size:.82rem;color:#9CA3AF;margin:16px 0}
.login-link{display:block;text-align:center;font-size:.85rem;color:#0A192F;font-weight:600;text-decoration:none}
.error-msg{background:#FEE2E2;border:1px solid #FCA5A5;border-radius:8px;padding:10px 14px;font-size:.83rem;color:#991B1B;font-weight:600;margin-bottom:16px}
@media(max-width:720px){.container{grid-template-columns:1fr}.left{display:none}}
</style>
</head>
<body>

<div class="container">
    {{-- Sol panel: paket seçimi --}}
    <div class="left">
        <div class="logo">🐾 VETAPP</div>
        <h2>Paketi Seç</h2>
        <p>İhtiyacınıza uygun paketi seçin. İstediğiniz zaman yükseltebilirsiniz.</p>

        @foreach([
            ['bronze','🥉 Bronz','1.000 TL/ay · 50 randevu/ay'],
            ['silver','🥈 Gümüş','2.500 TL/ay · 150 randevu/ay'],
            ['gold',  '🥇 Altın', '4.800 TL/ay · Sınırsız'],
        ] as [$val,$name,$price])
        <div class="plan-card {{ old('plan','bronze') === $val ? 'selected' : '' }}"
             onclick="selectPlan('{{ $val }}', this)">
            <div class="plan-name">{{ $name }}</div>
            <div class="plan-price">{{ $price }}</div>
        </div>
        @endforeach

        <input type="hidden" id="planInput" name="plan" value="{{ old('plan','bronze') }}">
    </div>

    {{-- Sağ panel: form --}}
    <div class="right">
        <h2>Hesap Oluştur</h2>
        <p>Kliniğinizi dakikalar içinde kurun.</p>

        @if($errors->any())
        <div class="error-msg">⚠️ {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" id="registerForm">
            @csrf
            <input type="hidden" name="plan" id="planField" value="{{ old('plan','bronze') }}">

            <div class="form-group">
                <label>Ad Soyad *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Dr. Ayşe Kaya">
            </div>
            <div class="form-group">
                <label>Klinik Adı *</label>
                <input type="text" name="clinic_name" value="{{ old('clinic_name') }}" required
                    placeholder="Pati Veteriner Kliniği" id="clinicNameInput">
            </div>
            <div class="form-group">
                <label>Klinik Adresi (URL) *</label>
                <div class="slug-wrap">
                    <span class="slug-prefix">vetapp.tr/</span>
                    <input type="text" name="slug" id="slugInput" value="{{ old('slug') }}"
                        required placeholder="pati-veteriner" autocomplete="off">
                </div>
                <div class="slug-status" id="slugStatus"></div>
            </div>
            <div class="form-group">
                <label>E-posta *</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="dr@klinik.com">
            </div>
            <div class="form-group">
                <label>Telefon *</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="05321234567">
            </div>
            <div class="form-group">
                <label>Şifre *</label>
                <input type="password" name="password" required placeholder="En az 8 karakter">
            </div>
            <div class="form-group">
                <label>Şifre Tekrar *</label>
                <input type="password" name="password_confirmation" required placeholder="Şifreyi tekrar girin">
            </div>

            <button type="submit" class="btn">🚀 Kliniği Oluştur</button>
        </form>

        <div class="divider">Zaten hesabınız var mı?</div>
        <a href="{{ route('login') }}" class="login-link">→ Giriş Yap</a>
    </div>
</div>

<script>
function selectPlan(val, el) {
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('planField').value = val;
}

// Klinik adından otomatik slug üret
document.getElementById('clinicNameInput').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/ğ/g,'g').replace(/ü/g,'u').replace(/ş/g,'s')
        .replace(/ı/g,'i').replace(/ö/g,'o').replace(/ç/g,'c')
        .replace(/[^a-z0-9]+/g,'-')
        .replace(/^-|-$/g,'');
    document.getElementById('slugInput').value = slug;
    checkSlug(slug);
});

// Slug değişince kontrol et
let slugTimer;
document.getElementById('slugInput').addEventListener('input', function() {
    clearTimeout(slugTimer);
    slugTimer = setTimeout(() => checkSlug(this.value), 500);
});

function checkSlug(slug) {
    const status = document.getElementById('slugStatus');
    if (!slug || slug.length < 3) {
        status.textContent = '';
        return;
    }
    fetch(`{{ route('register.slug-check') }}?slug=${encodeURIComponent(slug)}`)
        .then(r => r.json())
        .then(data => {
            if (data.available) {
                status.textContent = '✓ vetapp.tr/' + data.slug + ' müsait!';
                status.className = 'slug-status ok';
            } else {
                status.textContent = '✗ Bu adres alınmış. Başka bir tane deneyin.';
                status.className = 'slug-status no';
            }
        });
}
</script>
</body>
</html>