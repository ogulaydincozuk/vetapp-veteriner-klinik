<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $clinic->clinic_name }} — Online Randevu</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
    --navy:#0A192F;--mint:#64FFDA;--bg:#F5F7FA;
    --border:#E5E7EB;--text:#1F2937;--muted:#6B7280;
}
body{font-family:'Inter',sans-serif;background:var(--bg);min-height:100vh}

/* HEADER */
.header{background:var(--navy);padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between}
.header-logo{font-family:'Poppins',sans-serif;font-weight:800;font-size:1.1rem;color:var(--mint)}
.header-badge{font-size:.75rem;font-weight:600;color:rgba(255,255,255,.4);background:rgba(255,255,255,.06);padding:4px 12px;border-radius:50px}

/* HERO */
.hero{background:linear-gradient(135deg,var(--navy) 0%,#1A3255 100%);padding:60px 24px;text-align:center}
.clinic-avatar{width:80px;height:80px;border-radius:20px;background:var(--mint);display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:800;font-size:2rem;color:var(--navy);margin:0 auto 20px}
.clinic-name{font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:#fff;margin-bottom:8px}
.clinic-sub{font-size:.95rem;color:rgba(255,255,255,.5);margin-bottom:20px}
.clinic-meta{display:flex;gap:20px;justify-content:center;flex-wrap:wrap}
.meta-item{font-size:.82rem;color:rgba(255,255,255,.5);display:flex;align-items:center;gap:6px}

/* MAIN */
.main{max-width:960px;margin:0 auto;padding:40px 24px}
.grid{display:grid;grid-template-columns:1fr 380px;gap:28px;align-items:start}

/* FORM CARD */
.card{background:#fff;border-radius:16px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid var(--border)}
.card-title{font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:var(--navy);margin-bottom:6px}
.card-desc{font-size:.83rem;color:var(--muted);margin-bottom:22px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-group{margin-bottom:14px}
label{font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px}
input,select,textarea{width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none;transition:border-color .2s}
input:focus,select:focus,textarea:focus{border-color:var(--mint)}
.btn-submit{width:100%;padding:14px;background:var(--navy);color:var(--mint);font-weight:700;font-size:.95rem;border:none;border-radius:12px;cursor:pointer;font-family:'Poppins',sans-serif;transition:opacity .2s;margin-top:6px}
.btn-submit:hover{opacity:.85}

/* SLOTS */
.slots-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px}
.slot{padding:9px 0;border:1.5px solid var(--border);border-radius:9px;font-size:.82rem;font-weight:600;text-align:center;cursor:pointer;transition:all .2s;color:var(--text)}
.slot:hover{border-color:var(--mint);color:var(--navy)}
.slot.selected{background:var(--navy);color:var(--mint);border-color:var(--navy)}
.slot.taken{background:var(--bg);color:#D1D5DB;cursor:not-allowed;border-color:var(--border)}

/* INFO CARD */
.info-card{padding:18px;border-radius:12px;background:var(--bg);border:1px solid var(--border);margin-bottom:14px}
.info-row{display:flex;gap:10px;align-items:flex-start;padding:8px 0;border-bottom:1px solid var(--border)}
.info-row:last-child{border-bottom:none}
.info-icon{font-size:1rem;flex-shrink:0;margin-top:1px}
.info-text{font-size:.83rem;color:var(--text)}
.info-label{font-size:.72rem;color:var(--muted)}

/* FULL NOTICE */
.full-notice{background:#FEE2E2;border:1px solid #FCA5A5;border-radius:12px;padding:20px;text-align:center}
.full-notice h3{font-family:'Poppins',sans-serif;font-weight:700;color:#991B1B;margin-bottom:6px}
.full-notice p{font-size:.85rem;color:#B91C1C}

/* ALERT */
.alert-success{background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 16px;color:#065F46;font-weight:600;font-size:.85rem;margin-bottom:20px}
.alert-error{background:#FEE2E2;border:1px solid #FCA5A5;border-radius:10px;padding:12px 16px;color:#991B1B;font-weight:600;font-size:.85rem;margin-bottom:20px}

/* FOOTER */
.footer{text-align:center;padding:32px 24px;font-size:.78rem;color:var(--muted);margin-top:40px;border-top:1px solid var(--border)}
.footer a{color:var(--navy);font-weight:600;text-decoration:none}

@media(max-width:768px){
    .grid{grid-template-columns:1fr}
    .form-row{grid-template-columns:1fr}
    .slots-grid{grid-template-columns:repeat(4,1fr)}
}
</style>
</head>
<body>

{{-- HEADER --}}
<header class="header">
    <div class="header-logo">🐾 VETAPP</div>
    <div class="header-badge">Online Randevu Sistemi</div>
</header>

{{-- HERO --}}
<div class="hero">
    <div class="clinic-avatar">{{ strtoupper(substr($clinic->clinic_name,0,1)) }}</div>
    <div class="clinic-name">{{ $clinic->clinic_name }}</div>
    <div class="clinic-sub">Online Randevu Sistemi</div>
    <div class="clinic-meta">
        @if($clinic->phone)
        <div class="meta-item">📞 {{ $clinic->phone }}</div>
        @endif
        @if($clinic->clinic_city)
        <div class="meta-item">📍 {{ $clinic->clinic_city }}</div>
        @endif
        <div class="meta-item">
            🕐 {{ $clinic->working_hours_start ?? '09:00' }} — {{ $clinic->working_hours_end ?? '18:00' }}
        </div>
    </div>
</div>

{{-- MAIN --}}
<div class="main">

    @if(session('error'))
    <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    @if($isFull)
    <div class="full-notice">
        <h3>📭 Bu ay randevu kapasitesi doldu</h3>
        <p>{{ $clinic->clinic_name }} bu ay için randevu kapasitesine ulaşmıştır. Lütfen telefon ile iletişime geçin.</p>
        @if($clinic->phone)
        <p style="margin-top:10px;font-weight:700">📞 {{ $clinic->phone }}</p>
        @endif
    </div>
    @else
    <div class="grid">

        {{-- SOL: Randevu Formu --}}
        <div class="card">
            <div class="card-title">📅 Randevu Oluştur</div>
            <div class="card-desc">Aşağıdaki formu doldurun, randevunuzu oluşturalım.</div>

            <form method="POST" action="{{ route('booking.store', $clinic->slug) }}" id="bookingForm">
                @csrf

                <div style="font-size:.78rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--border)">
                    👤 Sahip Bilgileri
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ad Soyad *</label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="Ayşe Kaya">
                    </div>
                    <div class="form-group">
                        <label>Telefon *</label>
                        <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" required placeholder="05321234567">
                    </div>
                </div>

                <div style="font-size:.78rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--border);margin-top:8px">
                    🐾 Hayvan Bilgileri
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Hayvan Adı *</label>
                        <input type="text" name="pet_name" value="{{ old('pet_name') }}" required placeholder="Buddy">
                    </div>
                    <div class="form-group">
                        <label>Hayvan Türü *</label>
                        <input type="text" name="species" value="{{ old('species') }}" required placeholder="Köpek, Kedi...">
                    </div>
                </div>

                <div style="font-size:.78rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;padding-bottom:8px;border-bottom:1px solid var(--border);margin-top:8px">
                    📅 Randevu Detayları
                </div>
                <div class="form-group">
                    <label>Ziyaret Nedeni *</label>
                    <select name="type" required>
                        <option value="">— Seçin —</option>
                        <option value="checkup" {{ old('type')==='checkup'?'selected':'' }}>🔍 Genel Kontrol</option>
                        <option value="vaccine" {{ old('type')==='vaccine'?'selected':'' }}>💉 Aşı</option>
                        <option value="surgery" {{ old('type')==='surgery'?'selected':'' }}>🔪 Ameliyat</option>
                        <option value="xray"    {{ old('type')==='xray'?'selected':'' }}>📸 Röntgen</option>
                        <option value="other"   {{ old('type')==='other'?'selected':'' }}>📋 Diğer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tarih *</label>
                    <input type="date" name="appointment_date" id="dateInput"
                        value="{{ old('appointment_date', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        required onchange="loadSlots(this.value)">
                </div>

                <div class="form-group">
                    <label>Saat Seç *</label>
                    <input type="hidden" name="appointment_time" id="timeInput" value="{{ old('appointment_time') }}" required>
                    <div class="slots-grid" id="slotsGrid">
                        @foreach($availableSlots as $slot)
                        <div class="slot {{ old('appointment_time') === $slot ? 'selected' : '' }}"
                             onclick="selectSlot('{{ $slot }}', this)">
                            {{ $slot }}
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>Ek Notlar</label>
                    <textarea name="notes" rows="2" placeholder="Varsa belirtmek istediğiniz bilgiler...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">📅 Randevu Oluştur</button>
            </form>
        </div>

        {{-- SAĞ: Klinik Bilgileri --}}
        <div style="display:flex;flex-direction:column;gap:16px">

            <div class="card">
                <div class="card-title" style="font-size:.95rem;margin-bottom:14px">🏥 Klinik Bilgileri</div>
                <div class="info-card" style="padding:0">
                    @if($clinic->phone)
                    <div class="info-row">
                        <div class="info-icon">📞</div>
                        <div><div class="info-text">{{ $clinic->phone }}</div><div class="info-label">Telefon</div></div>
                    </div>
                    @endif
                    @if($clinic->clinic_city)
                    <div class="info-row">
                        <div class="info-icon">📍</div>
                        <div><div class="info-text">{{ $clinic->clinic_address ?? $clinic->clinic_city }}</div><div class="info-label">Adres</div></div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-icon">🕐</div>
                        <div>
                            <div class="info-text">{{ $clinic->working_hours_start ?? '09:00' }} — {{ $clinic->working_hours_end ?? '18:00' }}</div>
                            <div class="info-label">
                                Pzt-Cum
                                @if($clinic->working_saturday) · Cmt @endif
                                @if($clinic->working_sunday) · Paz @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-title" style="font-size:.95rem;margin-bottom:14px">ℹ️ Randevu Bilgisi</div>
                @foreach([
                    ['✅','Onay beklemiyor','Randevunuz anında oluşturulur'],
                    ['💬','SMS ile bildirim','Randevu bilgisi telefonunuza gönderilir'],
                    ['🔄','İptal edebilirsiniz','Randevuya 2 saat kala iptal yapılabilir'],
                ] as $tip)
                <div style="display:flex;gap:12px;padding:9px 0;border-bottom:1px solid var(--bg)">
                    <div style="font-size:1.1rem;flex-shrink:0">{{ $tip[0] }}</div>
                    <div>
                        <div style="font-size:.83rem;font-weight:600;color:var(--text)">{{ $tip[1] }}</div>
                        <div style="font-size:.75rem;color:var(--muted)">{{ $tip[2] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
    @endif
</div>

{{-- FOOTER --}}
<div class="footer">
    <a href="{{ route('home') }}">🐾 VETAPP</a> ile güçlendirildi ·
    <a href="{{ route('login') }}">Klinik girişi</a>
</div>

<script>
function selectSlot(time, el) {
    document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('timeInput').value = time;
}

// Seçili tarihe göre dolu slotları işaretle
function loadSlots(date) {
    const slug = '{{ $clinic->slug }}';
    fetch(`/api/slots/${slug}?date=${date}`)
        .then(r => r.json())
        .then(data => {
            document.querySelectorAll('.slot').forEach(slot => {
                const time = slot.textContent.trim();
                if (data.taken && data.taken.includes(time)) {
                    slot.classList.add('taken');
                    slot.onclick = null;
                } else {
                    slot.classList.remove('taken');
                    slot.onclick = () => selectSlot(time, slot);
                }
            });
        })
        .catch(() => {}); // API yoksa sessizce geç
}

// Sayfa yüklenince bugünün slotlarını kontrol et
loadSlots(document.getElementById('dateInput').value);
</script>
</body>
</html>