@extends('layouts.dashboard')
@section('title','Anketler')
@section('page-title','😊 Memnuniyet Anketleri')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Genel Ortalama</div><div class="widget-icon gold">⭐</div></div>
        <div style="display:flex;align-items:baseline;gap:6px">
            <div class="rating-big">{{ number_format($avgRating,1) }}</div>
            <div style="font-size:.8rem;color:var(--text-muted)">/ 5.0</div>
        </div>
        <div class="widget-detail">Tüm zamanlar</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Toplam Anket</div><div class="widget-icon mint">📝</div></div>
        <div class="widget-num">{{ $totalSurveys }}</div>
        <div class="widget-detail">Alınan yanıt</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Düşük Puanlar</div><div class="widget-icon red">⚠️</div></div>
        <div class="widget-num" style="{{ $lowRatings > 0 ? 'color:#EF4444' : '' }}">{{ $lowRatings }}</div>
        <div class="widget-detail {{ $lowRatings > 0 ? 'danger' : '' }}">2 yıldız ve altı</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Memnun Müşteri</div><div class="widget-icon mint">😊</div></div>
        @php $happy = $totalSurveys > 0 ? round(($totalSurveys - $lowRatings) / $totalSurveys * 100) : 0; @endphp
        <div class="widget-num">%{{ $happy }}</div>
        <div class="widget-detail">4-5 yıldız oranı</div>
    </div>
</div>

<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Puan Dağılımı --}}
        @if($totalSurveys > 0)
        <div class="card">
            <div class="card-title" style="margin-bottom:16px">📊 Puan Dağılımı</div>
            @foreach($ratingDist as $star => $data)
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <div style="font-size:.85rem;font-weight:700;color:var(--navy);width:24px">{{ $star }}★</div>
                <div style="flex:1;height:10px;background:var(--bg);border-radius:5px;overflow:hidden">
                    <div style="height:100%;width:{{ $data['pct'] }}%;background:{{ $star >= 4 ? 'var(--mint)' : ($star == 3 ? '#F59E0B' : '#EF4444') }};border-radius:5px;transition:width .8s ease"></div>
                </div>
                <div style="font-size:.78rem;color:var(--text-muted);width:60px">{{ $data['count'] }} ({{ $data['pct'] }}%)</div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Anket Listesi --}}
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>📋 Tüm Anketler</h3>
            </div>
            @forelse($surveys as $sv)
            <div style="padding:14px 0;border-bottom:1px solid var(--bg)">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px">
                    <div>
                        <span style="font-weight:700;font-size:.9rem">{{ $sv->pet->pet_name ?? '—' }}</span>
                        <span style="font-size:.78rem;color:var(--text-muted);margin-left:8px">{{ $sv->pet->owner_name ?? '' }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="font-size:1rem;letter-spacing:1px">
                            @for($i=1;$i<=5;$i++)
                                <span style="color:{{ $i<=$sv->rating ? '#F59E0B' : '#E5E7EB' }}">★</span>
                            @endfor
                        </span>
                        @if($sv->rating <= 2)
                        <span style="background:#FEE2E2;color:#991B1B;padding:2px 8px;border-radius:50px;font-size:.68rem;font-weight:700">⚠ Düşük</span>
                        @endif
                    </div>
                </div>
                @if($sv->comment)
                <div style="font-size:.83rem;color:var(--text-muted);font-style:italic">"{{ $sv->comment }}"</div>
                @endif
                <div style="font-size:.72rem;color:var(--text-muted);margin-top:4px">{{ $sv->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:10px">📭</div>
                <div style="font-weight:600">Henüz anket yok</div>
            </div>
            @endforelse
            @if($surveys->hasPages())
            <div style="margin-top:16px">{{ $surveys->links() }}</div>
            @endif
        </div>
    </div>

    {{-- SAĞ: Yeni Anket --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">📝 Anket Ekle</div>
            <form method="POST" action="{{ route('surveys.store') }}">
                @csrf
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Hasta *</label>
                    <select name="pet_id" required style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        <option value="">— Seçin —</option>
                        @foreach($pets as $pet)
                        <option value="{{ $pet->id }}">{{ $pet->pet_name }} ({{ $pet->owner_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:8px">Puan *</label>
                    <div style="display:flex;gap:8px">
                        @for($i=1;$i<=5;$i++)
                        <label style="flex:1;text-align:center;cursor:pointer">
                            <input type="radio" name="rating" value="{{ $i }}" {{ $i==5?'checked':'' }} style="display:none" class="star-radio">
                            <div class="star-btn" data-val="{{ $i }}"
                                style="padding:10px 0;border:1.5px solid var(--border);border-radius:10px;font-size:1.2rem;transition:all .2s;background:{{ $i==5?'#FEF3C7':'#fff' }};border-color:{{ $i==5?'#F59E0B':'var(--border)' }}">
                                ★
                            </div>
                        </label>
                        @endfor
                    </div>
                </div>
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Yorum</label>
                    <textarea name="comment" rows="3" placeholder="Müşteri yorumu..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;resize:vertical;outline:none"></textarea>
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">😊 Kaydet</button>
            </form>
        </div>
    </div>
</div>

@endsection