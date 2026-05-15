@extends('layouts.dashboard')
@section('title','Dashboard — Gümüş')
@section('page-title','📊 Dashboard')

@section('content')

{{-- SATIR 1 --}}
<div class="widget-grid">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bugünkü Randevular</div><div class="widget-icon mint">📅</div></div>
        <div class="widget-num">{{ $todayAppointments->count() }}</div>
        <div class="widget-detail">Bugün planlandı</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Aylık Kullanım</div><div class="widget-icon gold">📊</div></div>
        <div class="widget-num">{{ $monthAppointments }}</div>
        <div class="progress-wrap">
            @php $pct = min(100,round($monthAppointments/150*100)); @endphp
            <div class="progress-bar"><div class="progress-fill {{ $pct>=100?'danger':($pct>=80?'warn':'') }}" style="width:{{$pct}}%"></div></div>
            <div class="progress-label"><span>{{$monthAppointments}} / 150</span><span>%{{$pct}}</span></div>
        </div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Kayıtlı Hayvanlar</div><div class="widget-icon blue">🐾</div></div>
        <div class="widget-num">{{ $totalPets }}</div>
        <div class="widget-detail">Toplam hasta</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">SMS Bakiyesi</div><div class="widget-icon {{ $smsUsage->remaining()<20?'red':'mint' }}">💬</div></div>
        <div class="widget-num">{{ $smsUsage->remaining() }}</div>
        @if($smsUsage->total_sms > 0)
        <div class="progress-wrap">
            @php $sp=$smsUsage->percentage(); @endphp
            <div class="progress-bar"><div class="progress-fill {{$sp>=100?'danger':($sp>=80?'warn':'')}}" style="width:{{$sp}}%"></div></div>
            <div class="progress-label"><span>{{$smsUsage->used_sms}} kullanıldı</span><span>{{$smsUsage->total_sms}} toplam</span></div>
        </div>
        @else
        <div class="widget-detail warn">SMS paketi yok</div>
        @endif
    </div>
</div>

{{-- SATIR 2 — Silver extras --}}
<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Memnuniyet</div><div class="widget-icon gold">⭐</div></div>
        <div style="display:flex;align-items:baseline;gap:8px">
            <div class="rating-big">{{ number_format($avgRating,1) }}</div>
            <div style="font-size:.8rem;color:var(--text-muted)">/ 5.0</div>
        </div>
        <div class="widget-detail">Son 30 gün ortalaması</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bekleme Listesi</div><div class="widget-icon blue">⏳</div></div>
        <div class="widget-num">{{ $waitingCount }}</div>
        <div class="widget-detail">Randevu bekleyen müşteri</div>
        @if($waitingCount > 0)<a href="#" style="display:inline-block;margin-top:8px;font-size:.75rem;font-weight:700;color:var(--navy);text-decoration:none">→ Listeye git</a>@endif
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Doğum Günleri</div><div class="widget-icon mint">🎂</div></div>
        <div class="widget-num">{{ $birthdayPets }}</div>
        <div class="widget-detail">Bu hafta doğum günü olan hayvan</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Hızlı Duyuru</div><div class="widget-icon gold">📣</div></div>
        <div style="font-size:.83rem;color:var(--text-muted);margin-bottom:12px">WhatsApp veya SMS ile toplu mesaj gönderin</div>
        <a href="#" class="btn-add" style="font-size:.78rem;padding:7px 14px">📣 Duyuru Gönder</a>
    </div>
</div>

{{-- ANA İÇERİK --}}
<div class="content-grid">
    <div class="card">
        <div class="section-head">
            <h3>📋 Bugünün Randevuları</h3>
            <a href="#" class="btn-add">+ Yeni Randevu</a>
        </div>
        @if($todayAppointments->isEmpty())
        <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
            <div style="font-size:3rem;margin-bottom:12px">📭</div>
            <div style="font-weight:600;margin-bottom:6px">Bugün randevu yok</div>
        </div>
        @else
        <div class="table-wrap">
            <table>
                <thead><tr><th>Saat</th><th>Hayvan</th><th>Sahip</th><th>Tür</th><th>Durum</th><th>İşlem</th></tr></thead>
                <tbody>
                    @foreach($todayAppointments as $appt)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}</strong></td>
                        <td><div style="font-weight:600">{{ $appt->pet->pet_name }}</div><div style="font-size:.75rem;color:var(--text-muted)">{{ $appt->pet->species }}</div></td>
                        <td>{{ $appt->pet->owner_name }}</td>
                        <td>{{ $appt->getTypeLabel() }}</td>
                        <td><span class="badge {{ $appt->status }}">{{ $appt->getStatusLabel() }}</span></td>
                        <td style="display:flex;gap:6px"><a href="#" class="action-btn view">Gör</a><a href="#" class="action-btn edit">Düzenle</a><a href="#" class="action-btn cancel">İptal</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div style="display:flex;flex-direction:column;gap:18px">
        <div class="card">
            <div class="card-title" style="margin-bottom:12px">⭐ Son Anketler</div>
            @forelse($recentSurveys as $sv)
            <div class="activity-item">
                <div class="act-dot {{ $sv->rating <= 2 ? 'red' : ($sv->rating == 3 ? 'yellow' : '') }}"></div>
                <div>
                    <div class="act-text">
                        <strong>{{ $sv->pet->pet_name ?? '—' }}</strong>
                        <span class="stars" style="font-size:.8rem;margin-left:6px">{{ str_repeat('★',$sv->rating) }}{{ str_repeat('☆',5-$sv->rating) }}</span>
                        @if($sv->rating <= 2)<span style="font-size:.7rem;color:#EF4444;font-weight:700;margin-left:4px">⚠ Düşük puan</span>@endif
                    </div>
                    @if($sv->comment)<div class="act-time">"{{ Str::limit($sv->comment,60) }}"</div>@endif
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:16px 0;color:var(--text-muted);font-size:.83rem">Henüz anket yok</div>
            @endforelse
        </div>

        <div class="card">
            <div class="card-title" style="margin-bottom:12px">⚡ Hızlı İşlemler</div>
            <div class="quick-grid">
                <a href="#" class="quick-btn"><div class="quick-btn-icon">➕</div><div class="quick-btn-label">Randevu</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">🐾</div><div class="quick-btn-label">Hasta</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">⏳</div><div class="quick-btn-label">Bekleme</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">📣</div><div class="quick-btn-label">Duyuru</div></a>
            </div>
        </div>
    </div>
</div>

@endsection