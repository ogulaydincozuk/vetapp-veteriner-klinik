@extends('layouts.dashboard')
@section('title','Dashboard — Altın')
@section('page-title','📊 Dashboard')

@section('content')

{{-- SATIR 1 --}}
<div class="widget-grid">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bugünkü Randevular</div><div class="widget-icon mint">📅</div></div>
        <div class="widget-num">{{ $todayAppointments->count() }}</div>
        <div class="widget-detail">Sınırsız randevu</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bu Ayki Toplam</div><div class="widget-icon gold">📊</div></div>
        <div class="widget-num">{{ $monthAppointments }}</div>
        <div class="widget-detail" style="color:#10B981;font-weight:600">✓ Sınırsız kullanım</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Kayıtlı Hayvanlar</div><div class="widget-icon blue">🐾</div></div>
        <div class="widget-num">{{ $totalPets }}</div>
        <div class="widget-detail">Toplam hasta</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">SMS Bakiyesi</div><div class="widget-icon mint">💬</div></div>
        <div class="widget-num" style="font-size:1.4rem;padding-top:4px">Sınırsız</div>
        <div class="widget-detail" style="color:#10B981;font-weight:600">✓ Altın paket dahil</div>
    </div>
</div>

{{-- SATIR 2 --}}
<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Memnuniyet</div><div class="widget-icon gold">⭐</div></div>
        <div style="display:flex;align-items:baseline;gap:8px">
            <div class="rating-big">{{ number_format($avgRating,1) }}</div>
            <div style="font-size:.8rem;color:var(--text-muted)">/ 5.0</div>
        </div>
        <div class="widget-detail">Son 30 gün</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Ameliyat — Bu Hafta</div><div class="widget-icon red">🔪</div></div>
        <div class="widget-num">{{ $upcomingSurgeries->count() }}</div>
        <div class="widget-detail">Planlanan ameliyat</div>
        @if($upcomingSurgeries->isNotEmpty())<a href="#" style="display:inline-block;margin-top:6px;font-size:.75rem;font-weight:700;color:var(--navy);text-decoration:none">→ Takvime git</a>@endif
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bekleme Listesi</div><div class="widget-icon blue">⏳</div></div>
        <div class="widget-num">{{ $waitingCount }}</div>
        <div class="widget-detail">Bekleyen müşteri</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Doğum Günleri</div><div class="widget-icon mint">🎂</div></div>
        <div class="widget-num">{{ $birthdayPets }}</div>
        <div class="widget-detail">Bu hafta</div>
    </div>
</div>

{{-- ANA İÇERİK --}}
<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Randevular tablosu --}}
        <div class="card">
            <div class="section-head">
                <h3>📋 Bugünün Randevuları</h3>
                <a href="#" class="btn-add">+ Yeni Randevu</a>
            </div>
            @if($todayAppointments->isEmpty())
            <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:12px">📭</div>
                <div style="font-weight:600">Bugün randevu yok</div>
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

        {{-- Randevu Grafiği --}}
        <div class="card">
            <div class="section-head"><h3>📈 Son 6 Ay Randevu Trendi</h3></div>
               <div style="display:flex;align-items:flex-end;gap:10px;height:120px;padding-top:10px">
                @php
                    // ChartData boşsa veya hatalıysa çalışması için güvenli kontrol
                    $maxCount = 1;
                    $safeData = [];
                    
                    if(!empty($chartData) && is_array($chartData)) {
                        foreach($chartData as $item) {
                            if(!isset($item['count']) || !isset($item['label'])) continue;
                            
                            // Count değerini güvenli şekilde integer yap
                            $countValue = $item['count'];
                            if(is_array($countValue)) {
                                $countValue = (int)($countValue[0] ?? 0);
                            } else {
                                $countValue = (int)$countValue;
                            }
                            
                            $safeData[] = [
                                'label' => $item['label'],
                                'count' => $countValue
                            ];
                            
                            if($countValue > $maxCount) $maxCount = $countValue;
                        }
                    }
                    
                    // Eğer hala güvenli veri yoksa örnek veri göster
                    if(empty($safeData)) {
                        $safeData = [
                            ['label' => 'Oca', 'count' => 0],
                            ['label' => 'Şub', 'count' => 0],
                            ['label' => 'Mar', 'count' => 0],
                            ['label' => 'Nis', 'count' => 0],
                            ['label' => 'May', 'count' => 0],
                            ['label' => 'Haz', 'count' => 0]
                        ];
                        $maxCount = 1;
                    }
                @endphp
                
                @foreach($safeData as $bar)
                @php
                    $barHeight = max(8, round(($bar['count'] / $maxCount) * 100));
                @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px">
                    <div style="font-size:.72rem;color:var(--text-muted);font-weight:600">{{ $bar['count'] }}</div>
                    <div style="width:100%;height:{{$barHeight}}px;background:var(--mint);border-radius:6px 6px 0 0;opacity:.85;transition:all .3s" title="{{ $bar['label'] }}: {{ $bar['count'] }} randevu"></div>
                    <div style="font-size:.72rem;color:var(--text-muted)">{{ $bar['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:18px">

        {{-- Yaklaşan ameliyatlar --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:12px">🔪 Yaklaşan Ameliyatlar</div>
            @forelse($upcomingSurgeries as $surg)
            <div class="activity-item">
                <div class="act-dot" style="background:#EF4444"></div>
                <div>
                    <div class="act-text"><strong>{{ $surg->pet->pet_name }}</strong> — {{ $surg->surgery_name }}</div>
                    <div class="act-time">{{ \Carbon\Carbon::parse($surg->surgery_date)->format('d.m.Y H:i') }} · {{ $surg->doctor_name ?? 'Doktor atanmadı' }}</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:16px 0;color:var(--text-muted);font-size:.83rem">Bu hafta ameliyat yok</div>
            @endforelse
            <a href="#" class="btn-outline" style="margin-top:10px;width:100%;justify-content:center;font-size:.78rem">Tüm Takvimi Gör</a>
        </div>

        {{-- Son anketler --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:12px">⭐ Son Anketler</div>
            @forelse($recentSurveys as $sv)
            <div class="activity-item">
                <div class="act-dot {{ $sv->rating<=2?'red':($sv->rating==3?'yellow':'') }}"></div>
                <div>
                    <div class="act-text">
                        <strong>{{ $sv->pet->pet_name ?? '—' }}</strong>
                        <span class="stars" style="font-size:.8rem;margin-left:6px">{{ str_repeat('★',$sv->rating) }}{{ str_repeat('☆',5-$sv->rating) }}</span>
                        @if($sv->rating<=2)<span style="font-size:.7rem;color:#EF4444;font-weight:700;margin-left:4px">⚠</span>@endif
                    </div>
                    @if($sv->comment)<div class="act-time">"{{ Str::limit($sv->comment,50) }}"</div>@endif
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:16px 0;color:var(--text-muted);font-size:.83rem">Henüz anket yok</div>
            @endforelse
        </div>

        {{-- Hızlı işlemler --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:12px">⚡ Hızlı İşlemler</div>
            <div class="quick-grid">
                <a href="#" class="quick-btn"><div class="quick-btn-icon">➕</div><div class="quick-btn-label">Randevu</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">🔪</div><div class="quick-btn-label">Ameliyat</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">📊</div><div class="quick-btn-label">Raporlar</div></a>
                <a href="#" class="quick-btn"><div class="quick-btn-icon">🩺</div><div class="quick-btn-label">Doktorlar</div></a>
            </div>
        </div>
    </div>
</div>

@endsection