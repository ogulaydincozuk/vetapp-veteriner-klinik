@extends('layouts.dashboard')
@section('title','Raporlar')
@section('page-title','📊 Gelişmiş Raporlar')

@section('content')

{{-- Dönem Filtresi --}}
<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap">
    @foreach(['week'=>'Bu Hafta','month'=>'Bu Ay','3months'=>'3 Ay','6months'=>'6 Ay','year'=>'Bu Yıl'] as $key=>$label)
    <a href="{{ route('reports.index', ['period'=>$key]) }}"
        style="padding:8px 18px;border-radius:50px;font-size:.82rem;font-weight:600;text-decoration:none;transition:all .2s;
        background:{{ $period===$key ? 'var(--navy)' : 'var(--white)' }};
        color:{{ $period===$key ? 'var(--mint)' : 'var(--text-muted)' }};
        border:1.5px solid {{ $period===$key ? 'var(--navy)' : 'var(--border)' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ÖZET WİDGET'LAR --}}
<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Toplam Randevu</div><div class="widget-icon mint">📅</div></div>
        <div class="widget-num">{{ $totalAppointments }}</div>
        <div class="widget-detail">Seçili dönemde</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Tamamlanan</div><div class="widget-icon mint">✅</div></div>
        <div class="widget-num">{{ $completedAppointments }}</div>
        @php $rate = $totalAppointments > 0 ? round($completedAppointments/$totalAppointments*100) : 0; @endphp
        <div class="widget-detail">%{{ $rate }} tamamlanma oranı</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">İptal</div><div class="widget-icon red">❌</div></div>
        <div class="widget-num">{{ $cancelledAppointments }}</div>
        @php $cr = $totalAppointments > 0 ? round($cancelledAppointments/$totalAppointments*100) : 0; @endphp
        <div class="widget-detail">%{{ $cr }} iptal oranı</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Memnuniyet</div><div class="widget-icon gold">⭐</div></div>
        <div style="display:flex;align-items:baseline;gap:5px">
            <div class="rating-big" style="font-size:2rem">{{ number_format($avgRating,1) }}</div>
            <div style="font-size:.78rem;color:var(--text-muted)">/5 ({{ $totalSurveys }} anket)</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px">

    {{-- Aylık Trend --}}
    <div class="card">
        <div class="section-head" style="margin-bottom:16px"><h3>📈 Son 6 Ay Randevu Trendi</h3></div>
        <div style="display:flex;align-items:flex-end;gap:12px;height:140px">
            @php
             $counts = array_map('intval', array_column($monthlyTrend, 'count'));
             $maxC = max(array_merge($counts, [1]));
            @endphp
            @foreach($monthlyTrend as $bar)
           @php $h = max(12, round((int)$bar['count'] / $maxC * 100)); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px">
                <div style="font-size:.72rem;color:var(--text-muted);font-weight:600">{{ $bar['count'] }}</div>
                <div style="width:100%;height:{{$h}}px;background:var(--mint);border-radius:5px 5px 0 0;opacity:.85"></div>
                <div style="font-size:.68rem;color:var(--text-muted)">{{ $bar['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Hayvan Türü Dağılımı --}}
    <div class="card">
        <div class="section-head" style="margin-bottom:16px"><h3>🐾 Hayvan Türleri</h3></div>
        @php $totalPetsCount = $speciesDist->sum('count'); @endphp
        @foreach($speciesDist->take(6) as $sp)
        @php $pct = $totalPetsCount > 0 ? round($sp->count/$totalPetsCount*100) : 0; @endphp
        <div style="margin-bottom:10px">
            <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px">
                <span style="font-weight:600">{{ $sp->species }}</span>
                <span style="color:var(--text-muted)">{{ $sp->count }} (%{{ $pct }})</span>
            </div>
            <div style="height:8px;background:var(--bg);border-radius:4px;overflow:hidden">
                <div style="height:100%;width:{{$pct}}%;background:var(--mint);border-radius:4px"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- En Çok Gelen Hastalar --}}
    <div class="card">
        <div class="section-head" style="margin-bottom:14px"><h3>🏆 En Çok Gelen Hastalar</h3></div>
        @forelse($topPets->take(8) as $i => $tp)
        <div style="display:flex;align-items:center;gap:12px;padding:9px 0;border-bottom:1px solid var(--bg)">
            <div style="width:26px;height:26px;border-radius:50%;background:{{ $i<3 ? 'var(--navy)' : 'var(--bg)' }};display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:{{ $i<3 ? 'var(--mint)' : 'var(--text-muted)' }};flex-shrink:0">{{ $i+1 }}</div>
            <div style="flex:1">
                <div style="font-weight:600;font-size:.88rem">{{ $tp->pet->pet_name ?? '—' }}</div>
                <div style="font-size:.73rem;color:var(--text-muted)">{{ $tp->pet->owner_name ?? '' }}</div>
            </div>
            <div style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--navy)">{{ $tp->visit_count }} ziyaret</div>
        </div>
        @empty
        <div style="text-align:center;padding:20px 0;color:var(--text-muted);font-size:.83rem">Veri yok</div>
        @endforelse
    </div>

    {{-- Ziyaret Türleri & Doktor Performansı --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
            <div class="section-head" style="margin-bottom:14px"><h3>📋 Ziyaret Türleri</h3></div>
            @php
                $typeLabels = ['checkup'=>'Kontrol','vaccine'=>'Aşı','surgery'=>'Ameliyat','xray'=>'Röntgen','other'=>'Diğer'];
                $typeTotal = $typeDist->sum();
            @endphp
            @foreach($typeLabels as $key => $label)
            @php $cnt = $typeDist[$key] ?? 0; $pct = $typeTotal > 0 ? round($cnt/$typeTotal*100) : 0; @endphp
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                <div style="font-size:.8rem;color:var(--text-muted);width:70px">{{ $label }}</div>
                <div style="flex:1;height:8px;background:var(--bg);border-radius:4px;overflow:hidden">
                    <div style="height:100%;width:{{$pct}}%;background:var(--mint);border-radius:4px"></div>
                </div>
                <div style="font-size:.75rem;color:var(--text-muted);width:50px;text-align:right">{{ $cnt }} (%{{ $pct }})</div>
            </div>
            @endforeach
        </div>

     @if($doctorStats->isNotEmpty())
        <div class="card">
            <div class="section-head" style="margin-bottom:14px"><h3>🩺 Doktor Performansı</h3></div>
            @foreach($doctorStats as $doc)
            <div style="display:flex;align-items:center;gap:12px;padding:9px 0;border-bottom:1px solid var(--bg)">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--mint-dim);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">🩺</div>
                <div style="flex:1">
                    <div style="font-weight:600;font-size:.88rem">{{ $doc->name }}</div>
                    <div style="font-size:.73rem;color:var(--text-muted)">{{ $doc->specialty ?? 'Genel Veteriner' }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--navy)">{{ $doc->total_appointments }}</div>
                    <div style="font-size:.7rem;color:var(--text-muted)">randevu</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@endsection