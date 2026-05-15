@extends('layouts.dashboard')
@section('title','Ayarlar')
@section('page-title','⚙️ Ayarlar')

@section('content')

{{-- Başarı mesajları --}}
@foreach(['success_clinic'=>'Klinik bilgileri güncellendi.','success_hours'=>'Çalışma saatleri güncellendi.','success_notifications'=>'Bildirim ayarları güncellendi.','success_password'=>'Şifre başarıyla değiştirildi.'] as $key=>$msg)
@if(session($key))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:16px;color:#065F46;font-weight:600;font-size:.88rem">
    ✅ {{ session($key) }}
</div>
@endif
@endforeach

{{-- Hata mesajları --}}
@if($errors->any())
<div style="background:#FEE2E2;border:1px solid #FCA5A5;border-radius:10px;padding:12px 18px;margin-bottom:16px;color:#991B1B;font-weight:600;font-size:.88rem">
    ⚠️ {{ $errors->first() }}
</div>
@endif

{{-- Tab menüsü --}}
<div style="display:flex;gap:4px;background:#fff;border-radius:12px;padding:6px;box-shadow:var(--card-shadow);border:1px solid var(--border);margin-bottom:24px;width:fit-content" id="tabBar">
    @foreach([
        ['id'=>'clinic',        'icon'=>'🏥', 'label'=>'Klinik Bilgileri'],
        ['id'=>'hours',         'icon'=>'🕐', 'label'=>'Çalışma Saatleri'],
        ['id'=>'notifications', 'icon'=>'🔔', 'label'=>'Bildirimler'],
        ['id'=>'password',      'icon'=>'🔒', 'label'=>'Şifre Değiştir'],
        ['id'=>'subscription',  'icon'=>'📦', 'label'=>'Paket Bilgisi'],
    ] as $tab)
    <button onclick="switchTab('{{ $tab['id'] }}')" id="tab-{{ $tab['id'] }}"
        style="padding:9px 18px;border-radius:8px;border:none;cursor:pointer;font-size:.82rem;font-weight:600;font-family:'Inter',sans-serif;transition:all .2s;white-space:nowrap">
        {{ $tab['icon'] }} {{ $tab['label'] }}
    </button>
    @endforeach
</div>

{{-- ── KLİNİK BİLGİLERİ ─────────────────────────────── --}}
<div class="tab-content" id="tab-content-clinic">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">
        <div class="card">
            <div class="card-title" style="margin-bottom:4px">🏥 Klinik Bilgileri</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px">Kliniğinizin temel bilgilerini güncelleyin.</div>
            <form method="POST" action="{{ route('settings.clinic') }}">
                @csrf
                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Ad Soyad *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none;transition:border-color .2s"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Klinik Adı *</label>
                    <input type="text" name="clinic_name" value="{{ old('clinic_name', $user->clinic_name) }}" required
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Şehir</label>
                        <input type="text" name="clinic_city" value="{{ old('clinic_city', $user->clinic_city) }}" placeholder="İstanbul"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Website</label>
                        <input type="url" name="clinic_website" value="{{ old('clinic_website', $user->clinic_website) }}" placeholder="https://"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>
                <div style="margin-bottom:18px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Adres</label>
                    <textarea name="clinic_address" rows="2" placeholder="Klinik adresi..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;resize:vertical;outline:none"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">{{ old('clinic_address', $user->clinic_address) }}</textarea>
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">💾 Kaydet</button>
            </form>
        </div>

        {{-- Profil Kartı --}}
        <div style="display:flex;flex-direction:column;gap:16px">
            <div class="card" style="background:var(--navy)">
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px">
                    <div style="width:60px;height:60px;border-radius:16px;background:var(--mint);display:flex;align-items:center;justify-content:center;font-family:'Poppins',sans-serif;font-weight:800;font-size:1.5rem;color:var(--navy);flex-shrink:0">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1.05rem;color:#fff">{{ $user->name }}</div>
                        <div style="font-size:.8rem;color:rgba(255,255,255,.5);margin-top:2px">{{ $user->clinic_name }}</div>
                        <span class="pkg-badge {{ $user->subscription_plan }}" style="margin-top:6px;display:inline-flex">
                            @if($user->isGold()) 🥇 Altın @elseif($user->isSilver()) 🥈 Gümüş @else 🥉 Bronz @endif
                        </span>
                    </div>
                </div>
                <div style="border-top:1px solid rgba(255,255,255,.08);padding-top:14px">
                    @foreach([
                        ['📧','E-posta',$user->email],
                        ['📞','Telefon',$user->phone ?? 'Eklenmemiş'],
                        ['🏙️','Şehir',$user->clinic_city ?? 'Eklenmemiş'],
                        ['🌐','Website',$user->clinic_website ?? 'Eklenmemiş'],
                    ] as $info)
                    <div style="display:flex;gap:10px;padding:7px 0;border-bottom:1px solid rgba(255,255,255,.05)">
                        <span style="font-size:.85rem">{{ $info[0] }}</span>
                        <span style="font-size:.78rem;color:rgba(255,255,255,.4);width:60px">{{ $info[1] }}</span>
                        <span style="font-size:.78rem;color:rgba(255,255,255,.7);flex:1;word-break:break-all">{{ $info[2] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <div style="font-size:.8rem;font-weight:700;color:var(--navy);margin-bottom:10px">📊 Hesap İstatistikleri</div>
                @php
                    $totalAppt = \App\Models\Appointment::where('user_id',$user->id)->count();
                    $totalPets = \App\Models\Pet::where('user_id',$user->id)->count();
                    $totalVacc = \App\Models\Vaccine::whereHas('pet',fn($q)=>$q->where('user_id',$user->id))->count();
                @endphp
                @foreach([
                    ['📅','Toplam Randevu',$totalAppt],
                    ['🐾','Kayıtlı Hasta',$totalPets],
                    ['💉','Aşı Kaydı',$totalVacc],
                ] as $stat)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--bg)">
                    <span style="font-size:.83rem;color:var(--text-muted)">{{ $stat[0] }} {{ $stat[1] }}</span>
                    <span style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--navy)">{{ $stat[2] }}</span>
                </div>
                @endforeach
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:10px">
                    Üyelik: {{ $user->created_at->format('d.m.Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ÇALIŞMA SAATLERİ ─────────────────────────────── --}}
<div class="tab-content" id="tab-content-hours" style="display:none">
    <div style="max-width:600px">
        <div class="card">
            <div class="card-title" style="margin-bottom:4px">🕐 Çalışma Saatleri</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px">Kliniğinizin çalışma gün ve saatlerini ayarlayın.</div>
            <form method="POST" action="{{ route('settings.hours') }}">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Açılış Saati</label>
                        <input type="time" name="working_hours_start" value="{{ $user->working_hours_start ?? '09:00' }}"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Kapanış Saati</label>
                        <input type="time" name="working_hours_end" value="{{ $user->working_hours_end ?? '18:00' }}"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                            onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                    </div>
                </div>

                <div style="margin-bottom:20px">
                    <div style="font-size:.8rem;font-weight:600;color:var(--text);margin-bottom:12px">Çalışılan Günler</div>
                    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:8px">
                        @foreach(['Pzt','Sal','Çar','Per','Cum','Cmt','Paz'] as $i=>$day)
                        @php
                            $isWeekend = $i >= 5;
                            $checked = $isWeekend
                                ? ($i === 5 ? $user->working_saturday : $user->working_sunday)
                                : true;
                            $fieldName = $i === 5 ? 'working_saturday' : ($i === 6 ? 'working_sunday' : null);
                        @endphp
                        <label style="display:flex;flex-direction:column;align-items:center;gap:6px;cursor:{{ $isWeekend ? 'pointer' : 'default' }}">
                            @if($fieldName)
                            <input type="checkbox" name="{{ $fieldName }}" value="1" {{ $checked ? 'checked' : '' }}
                                style="display:none" class="day-checkbox" data-day="{{ $i }}">
                            @endif
                            <div class="day-btn" data-day="{{ $i }}"
                                style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;transition:all .2s;
                                background:{{ ($checked || !$isWeekend) ? 'var(--navy)' : 'var(--bg)' }};
                                color:{{ ($checked || !$isWeekend) ? 'var(--mint)' : 'var(--text-muted)' }};
                                border:1.5px solid {{ ($checked || !$isWeekend) ? 'var(--navy)' : 'var(--border)' }}">
                                {{ $day }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div style="padding:14px;background:var(--bg);border-radius:10px;margin-bottom:20px">
                    <div style="font-size:.82rem;color:var(--text-muted)">
                        🕐 <strong>Mevcut:</strong>
                        {{ $user->working_hours_start ?? '09:00' }} — {{ $user->working_hours_end ?? '18:00' }},
                        Hft içi
                        {{ $user->working_saturday ? '+ Cumartesi' : '' }}
                        {{ $user->working_sunday ? '+ Pazar' : '' }}
                    </div>
                </div>

                <button type="submit" class="btn-add" style="width:100%;justify-content:center">💾 Saatleri Kaydet</button>
            </form>
        </div>
    </div>
</div>

{{-- ── BİLDİRİM AYARLARI ───────────────────────────── --}}
<div class="tab-content" id="tab-content-notifications" style="display:none">
    <div style="max-width:600px">
        <div class="card">
            <div class="card-title" style="margin-bottom:4px">🔔 Bildirim Ayarları</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px">Müşterilerinize hangi kanallardan bildirim gönderileceğini ayarlayın.</div>
            <form method="POST" action="{{ route('settings.notifications') }}">
                @csrf

                <div style="font-size:.82rem;font-weight:700;color:var(--navy);margin-bottom:12px;text-transform:uppercase;letter-spacing:.5px">Bildirim Kanalları</div>

                @foreach([
                    ['notify_whatsapp','notify_whatsapp','💬 WhatsApp Bildirimleri','Randevu hatırlatmaları WhatsApp ile gönderilir — ÜCRETSİZ'],
                    ['notify_sms','notify_sms','📱 SMS Bildirimleri','SMS paketinizden düşer'],
                ] as $n)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:var(--bg);border-radius:12px;margin-bottom:10px">
                    <div>
                        <div style="font-weight:600;font-size:.88rem">{{ $n[2] }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">{{ $n[3] }}</div>
                    </div>
                    <label style="position:relative;width:44px;height:24px;cursor:pointer">
                        <input type="checkbox" name="{{ $n[1] }}" value="1" {{ $user->{$n[0]} ? 'checked' : '' }}
                            style="display:none" class="toggle-input" id="{{ $n[1] }}">
                        <div class="toggle-track" style="width:44px;height:24px;border-radius:12px;background:{{ $user->{$n[0]} ? 'var(--mint)' : '#D1D5DB' }};transition:background .2s;position:relative">
                            <div style="position:absolute;top:3px;left:{{ $user->{$n[0]} ? '23px' : '3px' }};width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2)"></div>
                        </div>
                    </label>
                </div>
                @endforeach

                <div style="font-size:.82rem;font-weight:700;color:var(--navy);margin:20px 0 12px;text-transform:uppercase;letter-spacing:.5px">Hatırlatma Türleri</div>

                @foreach([
                    ['notify_appointment_reminder','notify_appointment_reminder','📅 Randevu Hatırlatması','Randevudan önce otomatik hatırlatma gönder'],
                    ['notify_vaccine_reminder','notify_vaccine_reminder','💉 Aşı Hatırlatması','Yaklaşan aşılar için sahiplere hatırlatma gönder'],
                ] as $n)
                <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 16px;background:var(--bg);border-radius:12px;margin-bottom:10px">
                    <div>
                        <div style="font-weight:600;font-size:.88rem">{{ $n[2] }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">{{ $n[3] }}</div>
                    </div>
                    <label style="position:relative;width:44px;height:24px;cursor:pointer">
                        <input type="checkbox" name="{{ $n[1] }}" value="1" {{ $user->{$n[0]} ? 'checked' : '' }}
                            style="display:none" class="toggle-input">
                        <div class="toggle-track" style="width:44px;height:24px;border-radius:12px;background:{{ $user->{$n[0]} ? 'var(--mint)' : '#D1D5DB' }};transition:background .2s;position:relative">
                            <div style="position:absolute;top:3px;left:{{ $user->{$n[0]} ? '23px' : '3px' }};width:18px;height:18px;border-radius:50%;background:#fff;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2)"></div>
                        </div>
                    </label>
                </div>
                @endforeach

                <div style="margin:20px 0 18px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">⏰ Kaç Saat Önce Hatırlatılsın?</label>
                    <select name="reminder_hours_before"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        @foreach([1=>'1 saat önce',2=>'2 saat önce',6=>'6 saat önce',12=>'12 saat önce',24=>'24 saat önce',48=>'2 gün önce'] as $val=>$label)
                        <option value="{{ $val }}" {{ ($user->reminder_hours_before ?? 24) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-add" style="width:100%;justify-content:center">💾 Bildirimleri Kaydet</button>
            </form>
        </div>
    </div>
</div>

{{-- ── ŞİFRE DEĞİŞTİR ──────────────────────────────── --}}
<div class="tab-content" id="tab-content-password" style="display:none">
    <div style="max-width:480px">
        <div class="card">
            <div class="card-title" style="margin-bottom:4px">🔒 Şifre Değiştir</div>
            <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:20px">Güvenliğiniz için güçlü bir şifre kullanın.</div>
            <form method="POST" action="{{ route('settings.password') }}">
                @csrf
                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Mevcut Şifre *</label>
                    <input type="password" name="current_password" required
                        style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('current_password') ? '#EF4444' : 'var(--border)' }};border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                    @error('current_password')
                    <div style="font-size:.75rem;color:#EF4444;margin-top:4px">{{ $message }}</div>
                    @enderror
                </div>
                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Yeni Şifre *</label>
                    <input type="password" name="password" required id="newPass"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'"
                        oninput="checkStrength(this.value)">
                    <div id="strengthBar" style="height:4px;border-radius:2px;margin-top:6px;background:var(--bg);overflow:hidden">
                        <div id="strengthFill" style="height:100%;width:0%;border-radius:2px;transition:all .3s"></div>
                    </div>
                    <div id="strengthLabel" style="font-size:.72rem;color:var(--text-muted);margin-top:3px"></div>
                </div>
                <div style="margin-bottom:20px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Yeni Şifre (Tekrar) *</label>
                    <input type="password" name="password_confirmation" required
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none"
                        onfocus="this.style.borderColor='var(--mint)'" onblur="this.style.borderColor='var(--border)'">
                </div>
                <div style="padding:12px 14px;background:#FEF3C7;border-radius:8px;font-size:.8rem;color:#92400E;margin-bottom:18px">
                    ⚠️ Şifreniz en az 8 karakter olmalıdır.
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">🔒 Şifreyi Değiştir</button>
            </form>
        </div>
    </div>
</div>

{{-- ── PAKET BİLGİSİ ────────────────────────────────── --}}
<div class="tab-content" id="tab-content-subscription" style="display:none">
    <div style="max-width:700px">
        <div class="card" style="background:var(--navy);margin-bottom:20px">
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
                <div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px">Aktif Paketiniz</div>
                    <div style="font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:800;color:var(--mint)">
                        @if($user->isGold()) 🥇 Altın Paket
                        @elseif($user->isSilver()) 🥈 Gümüş Paket
                        @else 🥉 Bronz Paket
                        @endif
                    </div>
                    <div style="font-size:.85rem;color:rgba(255,255,255,.5);margin-top:6px">
                        @if($user->isGold()) Aylık 4.800 TL · Sınırsız randevu
                        @elseif($user->isSilver()) Aylık 2.500 TL · 150 randevu/ay
                        @else Aylık 1.000 TL · 50 randevu/ay
                        @endif
                    </div>
                </div>
                @if(!$user->isGold())
                <a href="{{ route('home') }}#packages" class="btn-add" style="font-size:.85rem">⬆️ Paketi Yükselt</a>
                @endif
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px">
            @php
                $features = [
                    'bronze' => ['Online randevu','Çoklu hayvan yönetimi','Randevu geçmişi','WhatsApp bildirimleri','Harita entegrasyonu'],
                    'silver' => ['Bronz\'daki her şey','Kilo takip grafiği','Dijital aşı kartı','Bekleme listesi','Memnuniyet anketi','Toplu duyuru'],
                    'gold'   => ['Gümüş\'teki her şey','Ameliyat takvimi','Çoklu doktor','Tedavi planları','Gelişmiş raporlar','Sınırsız SMS'],
                ];
            @endphp
            @foreach($features as $plan => $items)
            <div style="padding:18px;border-radius:12px;background:{{ $user->subscription_plan === $plan ? 'var(--navy)' : 'var(--bg)' }};border:1.5px solid {{ $user->subscription_plan === $plan ? 'var(--mint)' : 'var(--border)' }}">
                <div style="font-weight:700;font-size:.88rem;color:{{ $user->subscription_plan === $plan ? 'var(--mint)' : 'var(--text-muted)' }};margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px">
                    {{ $plan === 'bronze' ? '🥉 Bronz' : ($plan === 'silver' ? '🥈 Gümüş' : '🥇 Altın') }}
                    @if($user->subscription_plan === $plan) <span style="font-size:.65rem;background:var(--mint);color:var(--navy);padding:2px 6px;border-radius:50px;margin-left:4px">AKTİF</span> @endif
                </div>
                @foreach($items as $feat)
                <div style="font-size:.78rem;color:{{ $user->subscription_plan === $plan ? 'rgba(255,255,255,.7)' : 'var(--text-muted)' }};padding:4px 0;display:flex;align-items:center;gap:6px">
                    <span style="color:{{ $user->canAccess($plan) ? 'var(--mint)' : '#D1D5DB' }}">{{ $user->canAccess($plan) ? '✓' : '○' }}</span>
                    {{ $feat }}
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        @if(!$user->isGold())
        <div style="padding:20px;background:rgba(100,255,218,.08);border:1px solid rgba(100,255,218,.2);border-radius:12px;text-align:center">
            <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:var(--navy);margin-bottom:8px">Daha fazlası için paketi yükseltin</div>
            <div style="font-size:.85rem;color:var(--text-muted);margin-bottom:14px">Daha fazla özelliğe erişmek için paketinizi yükseltebilirsiniz.</div>
            <a href="{{ route('home') }}#packages" class="btn-add" style="display:inline-flex">⬆️ Paket Karşılaştır</a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Tab sistemi
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.getElementById('tab-content-' + tabId).style.display = 'block';
    document.querySelectorAll('#tabBar button').forEach(btn => {
        btn.style.background = 'transparent';
        btn.style.color = 'var(--text-muted)';
    });
    const activeBtn = document.getElementById('tab-' + tabId);
    activeBtn.style.background = 'var(--navy)';
    activeBtn.style.color = 'var(--mint)';
}

// Toggle switch interaktivite
document.querySelectorAll('.toggle-input').forEach(input => {
    input.addEventListener('change', function() {
        const track = this.nextElementSibling;
        const knob  = track.querySelector('div');
        track.style.background = this.checked ? 'var(--mint)' : '#D1D5DB';
        knob.style.left = this.checked ? '23px' : '3px';
    });
});

// Şifre güç göstergesi
function checkStrength(val) {
    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#EF4444','#F59E0B','#10B981','#064E3B'];
    const labels = ['Çok zayıf','Orta','Güçlü','Çok güçlü'];
    const fill   = document.getElementById('strengthFill');
    const label  = document.getElementById('strengthLabel');
    if (val.length === 0) { fill.style.width='0%'; label.textContent=''; return; }
    fill.style.width = (score * 25) + '%';
    fill.style.background = colors[score-1] || '#EF4444';
    label.textContent = labels[score-1] || 'Çok zayıf';
    label.style.color = colors[score-1] || '#EF4444';
}

// Cumartesi/Pazar toggle
document.querySelectorAll('.day-btn').forEach(btn => {
    const day = parseInt(btn.dataset.day);
    if (day < 5) return; // Haftaiçi değiştirilemez
    btn.style.cursor = 'pointer';
    btn.addEventListener('click', () => {
        const fieldName = day === 5 ? 'working_saturday' : 'working_sunday';
        const cb = document.querySelector(`input[name="${fieldName}"]`);
        if (!cb) return;
        cb.checked = !cb.checked;
        btn.style.background = cb.checked ? 'var(--navy)' : 'var(--bg)';
        btn.style.color = cb.checked ? 'var(--mint)' : 'var(--text-muted)';
        btn.style.borderColor = cb.checked ? 'var(--navy)' : 'var(--border)';
    });
});

// Sayfa yüklenince aktif tab
const activeTab = '{{ session("tab", "clinic") }}';
switchTab(activeTab);

// Başarı mesajına göre doğru tabı aç
@if(session('success_hours'))       switchTab('hours'); @endif
@if(session('success_notifications')) switchTab('notifications'); @endif
@if(session('success_password'))    switchTab('password'); @endif
</script>
@endpush

@endsection