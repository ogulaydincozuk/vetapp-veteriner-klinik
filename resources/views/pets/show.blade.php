@extends('layouts.dashboard')
@section('title', $pet->pet_name . ' — Hasta Detayı')
@section('page-title','🐾 Hasta Detayı')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">
    ✅ {{ session('success') }}
</div>
@endif

{{-- ÜST BİLGİ --}}
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
        <div style="width:72px;height:72px;border-radius:16px;background:var(--mint-dim);display:flex;align-items:center;justify-content:center;font-size:2.2rem;flex-shrink:0">
            {{ $pet->species === 'Kedi' ? '🐈' : ($pet->species === 'Köpek' ? '🐕' : '🐾') }}
        </div>
        <div style="flex:1">
            <div style="font-family:'Poppins',sans-serif;font-size:1.4rem;font-weight:800;color:var(--navy)">{{ $pet->pet_name }}</div>
            <div style="color:var(--text-muted);font-size:.9rem;margin-top:2px">
                {{ $pet->species }}{{ $pet->breed ? ' · '.$pet->breed : '' }}
                · {{ $pet->gender === 'male' ? '♂ Erkek' : ($pet->gender === 'female' ? '♀ Dişi' : 'Cinsiyet bilinmiyor') }}
                @if($pet->birth_date) · {{ $pet->birth_date->age }} yaşında @endif
            </div>
            <div style="margin-top:8px;display:flex;gap:16px;flex-wrap:wrap">
                <span style="font-size:.82rem;color:var(--text-muted)">👤 {{ $pet->owner_name }}</span>
                <span style="font-size:.82rem;color:var(--text-muted)">📞 {{ $pet->owner_phone }}</span>
                @if($pet->weight)<span style="font-size:.82rem;color:var(--text-muted)">⚖️ {{ $pet->weight }} kg</span>@endif
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <a href="{{ route('appointments.index') }}" class="btn-add" style="font-size:.82rem;padding:9px 16px">
                📅 Randevu Ekle
            </a>
            <a href="{{ route('pets.index') }}" class="btn-outline" style="font-size:.82rem;padding:9px 16px">
                ← Geri
            </a>
        </div>
    </div>
    @if($pet->notes)
    <div style="margin-top:14px;padding:12px 16px;background:var(--bg);border-radius:10px;font-size:.85rem;color:var(--text-muted)">
        📝 {{ $pet->notes }}
    </div>
    @endif
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- Randevu Geçmişi --}}
    <div class="card">
        <div class="section-head" style="margin-bottom:14px">
            <h3>📅 Randevu Geçmişi</h3>
            <span style="font-size:.75rem;color:var(--text-muted)">{{ $appointments->count() }} kayıt</span>
        </div>
        @forelse($appointments as $appt)
        <div style="display:flex;gap:12px;padding:10px 0;border-bottom:1px solid var(--bg)">
            <div style="width:44px;height:44px;border-radius:10px;background:var(--bg);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0">
                {{ $appt->type === 'vaccine' ? '💉' : ($appt->type === 'surgery' ? '🔪' : ($appt->type === 'xray' ? '📸' : '🔍')) }}
            </div>
            <div style="flex:1">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div style="font-weight:600;font-size:.88rem">{{ $appt->getTypeLabel() }}</div>
                    <span class="badge {{ $appt->status }}" style="font-size:.68rem">{{ $appt->getStatusLabel() }}</span>
                </div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">
                    {{ $appt->appointment_date->format('d.m.Y') }} · {{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}
                </div>
                @if($appt->notes)
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:3px">{{ $appt->notes }}</div>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px 0;color:var(--text-muted);font-size:.83rem">Randevu geçmişi yok</div>
        @endforelse
    </div>

    {{-- Aşı Kartı --}}
    <div class="card">
        <div class="section-head" style="margin-bottom:14px">
            <h3>💉 Aşı Kartı</h3>
            <span style="font-size:.75rem;color:var(--text-muted)">{{ $vaccines->count() }} aşı</span>
        </div>

        {{-- Yeni Aşı Ekle --}}
        <form method="POST" action="{{ route('vaccines.store') }}" style="margin-bottom:16px;padding:14px;background:var(--bg);border-radius:10px">
            @csrf
            <input type="hidden" name="pet_id" value="{{ $pet->id }}">
            <div style="font-size:.8rem;font-weight:700;color:var(--navy);margin-bottom:10px">+ Aşı Ekle</div>
            <div style="margin-bottom:8px">
                <input type="text" name="vaccine_name" required placeholder="Aşı adı (örn: Karma Aşı)"
                    style="width:100%;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:.83rem;font-family:'Inter',sans-serif;outline:none">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px">
                <input type="date" name="vaccine_date" required
                    style="padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:.83rem;font-family:'Inter',sans-serif;outline:none">
                <input type="date" name="next_date" placeholder="Sonraki tarih"
                    style="padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:.83rem;font-family:'Inter',sans-serif;outline:none">
            </div>
            <button type="submit" class="btn-add" style="width:100%;justify-content:center;font-size:.78rem;padding:8px">
                💉 Aşıyı Kaydet
            </button>
        </form>

        @forelse($vaccines as $vac)
        <div style="padding:10px 0;border-bottom:1px solid var(--bg)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div>
                    <div style="font-weight:600;font-size:.88rem">{{ $vac->vaccine_name }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px">
                        Yapıldı: {{ $vac->vaccine_date->format('d.m.Y') }}
                    </div>
                </div>
                @if($vac->next_date)
                <div style="text-align:right">
                    <div style="font-size:.72rem;font-weight:700;
                        color:{{ $vac->next_date->isPast() ? '#EF4444' : ($vac->next_date->diffInDays() < 30 ? '#F59E0B' : '#10B981') }}">
                        {{ $vac->next_date->isPast() ? '⚠ Gecikmiş' : '⏰ Sonraki' }}
                    </div>
                    <div style="font-size:.75rem;color:var(--text-muted)">{{ $vac->next_date->format('d.m.Y') }}</div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:20px 0;color:var(--text-muted);font-size:.83rem">Aşı kaydı yok</div>
        @endforelse
    </div>
</div>

@endsection