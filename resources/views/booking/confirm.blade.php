<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Randevu Onaylandı — {{ $clinic->clinic_name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#F5F7FA;min-height:100vh;display:flex;flex-direction:column}
.header{background:#0A192F;padding:0 24px;height:64px;display:flex;align-items:center}
.header-logo{font-family:'Poppins',sans-serif;font-weight:800;font-size:1.1rem;color:#64FFDA}
.main{flex:1;display:flex;align-items:center;justify-content:center;padding:40px 24px}
.box{background:#fff;border-radius:20px;padding:48px 40px;max-width:520px;width:100%;text-align:center;box-shadow:0 4px 24px rgba(0,0,0,.08)}
.check{width:72px;height:72px;border-radius:50%;background:#D1FAE5;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 24px}
h2{font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:#0A192F;margin-bottom:8px}
p{font-size:.88rem;color:#6B7280;margin-bottom:28px}
.detail-card{background:#F5F7FA;border-radius:12px;padding:20px;text-align:left;margin-bottom:24px}
.detail-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #E5E7EB;font-size:.85rem}
.detail-row:last-child{border-bottom:none}
.detail-label{color:#6B7280}
.detail-value{font-weight:600;color:#0A192F}
.btn-back{display:inline-block;padding:13px 32px;background:#0A192F;color:#64FFDA;font-weight:700;font-size:.92rem;border-radius:12px;text-decoration:none;font-family:'Poppins',sans-serif}
.footer{text-align:center;padding:20px;font-size:.75rem;color:#9CA3AF}
.footer a{color:#0A192F;font-weight:600;text-decoration:none}
</style>
</head>
<body>
<header class="header">
    <div class="header-logo">🐾 VETAPP</div>
</header>

<div class="main">
    <div class="box">
        <div class="check">✅</div>
        <h2>Randevunuz Oluşturuldu!</h2>
        <p>{{ $clinic->clinic_name }} için randevunuz başarıyla kaydedildi. Onay için kliniğin sizi aramasını bekleyin.</p>

        <div class="detail-card">
            @foreach([
                ['🏥 Klinik', $clinic->clinic_name],
                ['🐾 Hayvan', $appt->pet->pet_name . ' (' . $appt->pet->species . ')'],
                ['👤 Sahip', $appt->pet->owner_name],
                ['📅 Tarih', $appt->appointment_date->format('d.m.Y')],
                ['🕐 Saat', \Carbon\Carbon::parse($appt->appointment_time)->format('H:i')],
                ['📋 Tür', $appt->getTypeLabel()],
                ['⏳ Durum', 'Onay bekleniyor'],
            ] as [$label,$value])
            <div class="detail-row">
                <span class="detail-label">{{ $label }}</span>
                <span class="detail-value">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        @if($clinic->phone)
        <p style="font-size:.82rem;color:#6B7280;margin-bottom:20px">
            Sorularınız için: <strong>{{ $clinic->phone }}</strong>
        </p>
        @endif

        <a href="{{ route('booking.show', $clinic->slug) }}" class="btn-back">
            ← Yeni Randevu Al
        </a>
    </div>
</div>

<div class="footer">
    <a href="{{ route('home') }}">🐾 VETAPP</a> ile güçlendirildi
</div>
</body>
</html>