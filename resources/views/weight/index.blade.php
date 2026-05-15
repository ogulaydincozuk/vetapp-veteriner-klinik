@extends('layouts.dashboard')
@section('title','Kilo Takibi')
@section('page-title','📈 Kilo Takibi')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Hasta seç --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:14px">🐾 Hasta Seç</div>
            <form method="GET" action="{{ route('weight.index') }}" style="display:flex;gap:10px;flex-wrap:wrap">
                <select name="pet_id" onchange="this.form.submit()"
                    style="flex:1;min-width:200px;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                    <option value="">— Hasta seçin —</option>
                    @foreach(\App\Models\Pet::where('user_id',auth()->id())->orderBy('pet_name')->get() as $p)
                    <option value="{{ $p->id }}" {{ request('pet_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->pet_name }} ({{ $p->owner_name }})
                    </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedPet)
        {{-- Seçili hasta bilgisi --}}
        <div class="card" style="background:var(--navy);border-color:var(--mint)">
            <div style="display:flex;align-items:center;gap:14px">
                <div style="font-size:2rem">🐾</div>
                <div>
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:1.1rem">{{ $selectedPet->pet_name }}</div>
                    <div style="color:rgba(255,255,255,.6);font-size:.82rem">{{ $selectedPet->species }} · {{ $selectedPet->owner_name }}</div>
                </div>
                @if($selectedPet->weight)
                <div style="margin-left:auto;text-align:right">
                    <div style="font-family:'Poppins',sans-serif;font-size:1.6rem;font-weight:800;color:var(--mint)">{{ $selectedPet->weight }} kg</div>
                    <div style="font-size:.75rem;color:rgba(255,255,255,.5)">Son kayıt</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Kilo Grafiği --}}
        @if($weightRecords->count() > 1)
        <div class="card">
            <div class="section-head" style="margin-bottom:16px">
                <h3>📊 Kilo Değişim Grafiği</h3>
                <span style="font-size:.75rem;color:var(--text-muted)">{{ $weightRecords->count() }} ölçüm</span>
            </div>
            <div style="display:flex;align-items:flex-end;gap:8px;height:140px;padding:10px 0">
                @php
                    $maxW = $weightRecords->max('weight');
                    $minW = $weightRecords->min('weight');
                    $range = max($maxW - $minW, 0.1);
                @endphp
                @foreach($weightRecords as $rec)
                @php $h = max(20, round(($rec->weight - $minW) / $range * 100) + 20); @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px">
                    <div style="font-size:.65rem;color:var(--text-muted);font-weight:600">{{ $rec->weight }}</div>
                    <div style="width:100%;height:{{ $h }}px;background:var(--mint);border-radius:5px 5px 0 0;opacity:.8;min-height:20px"
                        title="{{ $rec->recorded_at->format('d.m.Y') }}: {{ $rec->weight }} kg"></div>
                    <div style="font-size:.62rem;color:var(--text-muted)">{{ $rec->recorded_at->format('d.m') }}</div>
                </div>
                @endforeach
            </div>
            @php
                $first = $weightRecords->first();
                $last  = $weightRecords->last();
                $diff  = round($last->weight - $first->weight, 2);
            @endphp
            <div style="margin-top:12px;padding:10px 14px;background:var(--bg);border-radius:8px;font-size:.83rem;color:var(--text-muted)">
                Toplam değişim:
                <strong style="color:{{ $diff > 0 ? '#EF4444' : ($diff < 0 ? '#10B981' : 'var(--navy)') }}">
                    {{ $diff > 0 ? '+' : '' }}{{ $diff }} kg
                </strong>
                ({{ $first->recorded_at->format('d.m.Y') }} → {{ $last->recorded_at->format('d.m.Y') }})
            </div>
        </div>
        @endif

        {{-- Kilo Kayıtları Tablosu --}}
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>📋 Ölçüm Geçmişi</h3>
            </div>
            @if($weightRecords->isEmpty())
            <div style="text-align:center;padding:30px 0;color:var(--text-muted);font-size:.83rem">Henüz ölçüm yok</div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Tarih</th><th>Kilo</th><th>Değişim</th><th>Not</th></tr></thead>
                    <tbody>
                        @foreach($weightRecords->reverse() as $i => $rec)
                        @php $prev = $weightRecords->reverse()->values()->get($i+1); @endphp
                        <tr>
                            <td>{{ $rec->recorded_at->format('d.m.Y') }}</td>
                            <td><strong>{{ $rec->weight }} kg</strong></td>
                            <td>
                                @if($prev)
                                @php $d = round($rec->weight - $prev->weight, 2); @endphp
                                <span style="font-weight:700;color:{{ $d > 0 ? '#EF4444' : ($d < 0 ? '#10B981' : 'var(--text-muted)') }}">
                                    {{ $d > 0 ? '▲ +' : ($d < 0 ? '▼ ' : '— ') }}{{ abs($d) }} kg
                                </span>
                                @else
                                <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td style="color:var(--text-muted)">{{ $rec->notes ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif

        {{-- Son Ölçümler --}}
        @if(!$selectedPet)
        <div class="card">
            <div class="section-head" style="margin-bottom:14px"><h3>🕐 Son Ölçümler</h3></div>
            @forelse($recentWeights as $rec)
            <div class="activity-item">
                <div class="act-dot"></div>
                <div style="flex:1;display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <div class="act-text"><strong>{{ $rec->pet->pet_name }}</strong> — {{ $rec->pet->owner_name }}</div>
                        <div class="act-time">{{ $rec->recorded_at->format('d.m.Y') }}</div>
                    </div>
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1.1rem;color:var(--navy)">{{ $rec->weight }} kg</div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:30px 0;color:var(--text-muted);font-size:.83rem">Henüz ölçüm yok</div>
            @endforelse
        </div>
        @endif
    </div>

    {{-- SAĞ: Ölçüm Ekle --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">⚖️ Yeni Ölçüm Ekle</div>
            <form method="POST" action="{{ route('weight.store') }}">
                @csrf
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Hasta *</label>
                    <select name="pet_id" required style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        <option value="">— Seçin —</option>
                        @foreach(\App\Models\Pet::where('user_id',auth()->id())->orderBy('pet_name')->get() as $p)
                        <option value="{{ $p->id }}" {{ (request('pet_id') == $p->id || (isset($selectedPet) && $selectedPet->id == $p->id)) ? 'selected' : '' }}>
                            {{ $p->pet_name }} ({{ $p->owner_name }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Kilo (kg) *</label>
                    <input type="number" name="weight" step="0.1" min="0" required placeholder="4.5"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Tarih *</label>
                    <input type="date" name="recorded_at" required value="{{ date('Y-m-d') }}"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Not</label>
                    <input type="text" name="notes" placeholder="İsteğe bağlı..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">⚖️ Kaydet</button>
            </form>
        </div>
    </div>
</div>

@endsection