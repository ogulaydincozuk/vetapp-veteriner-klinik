@extends('layouts.dashboard')
@section('title','Ana Sayfa')
@section('page-title','📊 Dashboard')

@section('content')

{{-- WİDGET'LAR --}}
<div class="widget-grid">
    <div class="widget">
        <div class="widget-header">
            <div class="widget-label">Bugünkü Randevular</div>
            <div class="widget-icon mint">📅</div>
        </div>
        <div class="widget-num">{{ $todayAppointments->count() }}</div>
        <div class="widget-detail">Toplam bugün için planlandı</div>
    </div>

    <div class="widget">
        <div class="widget-header">
            <div class="widget-label">Aylık Kullanım</div>
            <div class="widget-icon {{ $monthAppointments >= 40 ? 'red' : ($monthAppointments >= 30 ? 'gold' : 'mint') }}">📊</div>
        </div>
        <div class="widget-num">{{ $monthAppointments }}</div>
        <div class="progress-wrap">
            <div class="progress-bar">
                @php $pct = min(100, round($monthAppointments/50*100)); @endphp
                <div class="progress-fill {{ $pct >= 100 ? 'danger' : ($pct >= 80 ? 'warn' : '') }}" style="width:{{ $pct }}%"></div>
            </div>
            <div class="progress-label">
                <span>{{ $monthAppointments }} / 50 randevu</span>
                @if($pct >= 80)
                    <span style="color:{{ $pct>=100?'#EF4444':'#F59E0B' }};font-weight:700">{{ $pct >= 100 ? '⚠ Limit doldu!' : '⚠ Limite yakın' }}</span>
                @else
                    <span>%{{ $pct }}</span>
                @endif
            </div>
        </div>
        @if($pct >= 100)
        <a href="{{ route('home') }}#packages" style="display:inline-block;margin-top:10px;font-size:.75rem;font-weight:700;color:#EF4444;text-decoration:none;">→ Gümüş pakete geç</a>
        @endif
    </div>

    <div class="widget">
        <div class="widget-header">
            <div class="widget-label">Kayıtlı Hayvanlar</div>
            <div class="widget-icon blue">🐾</div>
        </div>
        <div class="widget-num">{{ $totalPets }}</div>
        <div class="widget-detail">Toplam kayıtlı hasta</div>
    </div>

    <div class="widget">
        <div class="widget-header">
            <div class="widget-label">SMS Bakiyesi</div>
            <div class="widget-icon {{ isset($smsUsage) && $smsUsage->remaining()<20 ? 'red' : 'mint' }}">💬</div>
        </div>
        <div class="widget-num">{{ $smsUsage->remaining() }}</div>
        @if($smsUsage->total_sms > 0)
        <div class="progress-wrap">
            <div class="progress-bar">
                @php $sp = $smsUsage->percentage(); @endphp
                <div class="progress-fill {{ $sp>=100?'danger':($sp>=80?'warn':'') }}" style="width:{{ $sp }}%"></div>
            </div>
            <div class="progress-label"><span>{{ $smsUsage->used_sms }} kullanıldı</span><span>{{ $smsUsage->total_sms }} toplam</span></div>
        </div>
        @if($smsUsage->remaining() == 0)
        <a href="{{ route('home') }}#sms" style="display:inline-block;margin-top:10px;font-size:.75rem;font-weight:700;color:#EF4444;text-decoration:none;">→ SMS satın al</a>
        @endif
        @else
        <div class="widget-detail warn">SMS paketi yok — <a href="{{ route('home') }}#sms" style="color:inherit;font-weight:700">satın al</a></div>
        @endif
    </div>
</div>

{{-- ANA İÇERİK --}}
<div class="content-grid">
    {{-- Bugünün Randevuları --}}
    <div class="card">
        <div class="section-head">
            <h3>📋 Bugünün Randevuları</h3>
            <a href="#" class="btn-add">+ Yeni Randevu</a>
        </div>
        @if($todayAppointments->isEmpty())
        <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
            <div style="font-size:3rem;margin-bottom:12px">📭</div>
            <div style="font-weight:600;margin-bottom:6px">Bugün randevu yok</div>
            <div style="font-size:.83rem">Yeni randevu eklemek için butona tıklayın.</div>
        </div>
        @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Saat</th>
                        <th>Hayvan</th>
                        <th>Sahip</th>
                        <th>Tür</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayAppointments as $appt)
                    <tr>
                        <td><strong>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}</strong></td>
                        <td>
                            <div style="font-weight:600">{{ $appt->pet->pet_name }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted)">{{ $appt->pet->species }}</div>
                        </td>
                        <td>{{ $appt->pet->owner_name }}</td>
                        <td>{{ $appt->getTypeLabel() }}</td>
                        <td><span class="badge {{ $appt->status }}">{{ $appt->getStatusLabel() }}</span></td>
                        <td style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="#" class="action-btn view">Gör</a>
                            <a href="#" class="action-btn edit">Düzenle</a>
                            <a href="#" class="action-btn cancel">İptal</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Sağ panel --}}
    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- Hızlı işlemler --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:12px">⚡ Hızlı İşlemler</div>
            <div class="quick-grid">
                <a href="#" class="quick-btn"><div class="quick-btn-icon">➕</div><div class="quick-btn-label">Yeni Randevu</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">🐾</div><div class="quick-btn-label">Hasta Ekle</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">📋</div><div class="quick-btn-label">Geçmiş</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">🗺️</div><div class="quick-btn-label">Harita</div></a>
            </div>
        </div>

        {{-- Son aktiviteler --}}
        <div class="card" style="flex:1">
            <div class="card-title" style="margin-bottom:12px">🕐 Son Aktiviteler</div>
            @forelse($recentActivity as $act)
            <div class="activity-item">
                <div class="act-dot {{ $act->status === 'cancelled' ? 'red' : ($act->status === 'pending' ? 'yellow' : '') }}"></div>
                <div>
                    <div class="act-text">
                        <strong>{{ $act->pet->pet_name }}</strong> — {{ $act->getTypeLabel() }}
                        <span class="badge {{ $act->status }}" style="margin-left:5px;font-size:.65rem">{{ $act->getStatusLabel() }}</span>
                    </div>
                    <div class="act-time">{{ $act->appointment_date->format('d.m.Y') }} · {{ \Carbon\Carbon::parse($act->appointment_time)->format('H:i') }}</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px 0;color:var(--text-muted);font-size:.83rem">Henüz aktivite yok</div>
            @endforelse
        </div>
    </div>
</div>

@endsection