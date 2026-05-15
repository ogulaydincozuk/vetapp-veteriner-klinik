@extends('layouts.dashboard')
@section('title','Tedavi Planları')
@section('page-title','📋 Tedavi Planları')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Aktif Plan</div><div class="widget-icon mint">📋</div></div>
        <div class="widget-num">{{ $active->count() }}</div>
        <div class="widget-detail">Devam eden tedavi</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Tamamlanan</div><div class="widget-icon gold">✅</div></div>
        <div class="widget-num">{{ $completed->where('status','completed')->count() }}</div>
        <div class="widget-detail">Başarıyla tamamlandı</div>
    </div>
</div>

<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Aktif Planlar --}}
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>🟢 Aktif Tedavi Planları</h3>
            </div>
            @if($active->isEmpty())
            <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:10px">📋</div>
                <div style="font-weight:600">Aktif tedavi planı yok</div>
            </div>
            @else
            @foreach($active as $plan)
            <div style="padding:16px;background:var(--bg);border-radius:12px;margin-bottom:10px;border-left:4px solid var(--mint)">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px">
                    <div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--navy)">{{ $plan->title }}</div>
                        <div style="font-size:.82rem;color:var(--text-muted);margin-top:3px">
                            🐾 {{ $plan->pet->pet_name }} · 👤 {{ $plan->pet->owner_name }}
                        </div>
                        @if($plan->doctor_name)
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">🩺 {{ $plan->doctor_name }}</div>
                        @endif
                        @if($plan->description)
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:6px;background:#fff;border-radius:6px;padding:6px 10px">
                            {{ $plan->description }}
                        </div>
                        @endif
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:.78rem;color:var(--text-muted)">
                            Başlangıç: <strong>{{ $plan->start_date->format('d.m.Y') }}</strong>
                        </div>
                        @if($plan->end_date)
                        <div style="font-size:.78rem;color:var(--text-muted)">
                            Bitiş: <strong>{{ $plan->end_date->format('d.m.Y') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        {{-- Geçmiş Planlar --}}
        @if($completed->isNotEmpty())
        <div class="card">
            <div class="section-head" style="margin-bottom:14px"><h3>🏁 Geçmiş Planlar</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Başlık</th><th>Hasta</th><th>Tarih</th><th>Durum</th></tr></thead>
                    <tbody>
                        @foreach($completed as $plan)
                        <tr>
                            <td><strong>{{ $plan->title }}</strong></td>
                            <td>{{ $plan->pet->pet_name }}</td>
                            <td>{{ $plan->start_date->format('d.m.Y') }}</td>
                            <td>
                                <span class="badge {{ $plan->status === 'completed' ? 'confirmed' : 'cancelled' }}">
                                    {{ $plan->getStatusLabel() }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px">{{ $completed->links() }}</div>
        </div>
        @endif
    </div>

    {{-- SAĞ: Plan Ekle --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">📋 Yeni Tedavi Planı</div>
            <form method="POST" action="{{ route('treatments.store') }}">
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
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Plan Başlığı *</label>
                    <input type="text" name="title" required placeholder="örn: 4 Haftalık Antibiyotik"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Açıklama</label>
                    <textarea name="description" rows="3"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;resize:vertical;outline:none"></textarea>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Başlangıç *</label>
                        <input type="date" name="start_date" required value="{{ date('Y-m-d') }}"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Bitiş</label>
                        <input type="date" name="end_date"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                </div>
                @if($doctors->isNotEmpty())
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Doktor</label>
                    <select name="doctor_name" style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        <option value="">— Seçin —</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->name }}">{{ $doc->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">📋 Planı Oluştur</button>
            </form>
        </div>
    </div>
</div>

@endsection