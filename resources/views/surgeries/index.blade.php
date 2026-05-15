@extends('layouts.dashboard')
@section('title','Ameliyat Takvimi')
@section('page-title','🔪 Ameliyat Takvimi')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Planlanan</div><div class="widget-icon blue">📅</div></div>
        <div class="widget-num">{{ $upcoming->count() }}</div>
        <div class="widget-detail">Yaklaşan ameliyat</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bu Hafta</div><div class="widget-icon red">🔪</div></div>
        <div class="widget-num">{{ $upcoming->filter(fn($s) => \Carbon\Carbon::parse($s->surgery_date)->isCurrentWeek())->count() }}</div>
        <div class="widget-detail">Bu haftaki ameliyat</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Tamamlanan</div><div class="widget-icon mint">✅</div></div>
        <div class="widget-num">{{ $past->where('status','completed')->count() }}</div>
        <div class="widget-detail">Bu sayfada</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Doktorlar</div><div class="widget-icon gold">🩺</div></div>
        <div class="widget-num">{{ $doctors->count() }}</div>
        <div class="widget-detail">Aktif doktor</div>
    </div>
</div>

<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Yaklaşan Ameliyatlar --}}
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>📅 Planlanan Ameliyatlar</h3>
                <a href="#" class="btn-add" onclick="document.getElementById('addForm').scrollIntoView({behavior:'smooth'});return false;">+ Ekle</a>
            </div>
            @if($upcoming->isEmpty())
            <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:10px">📭</div>
                <div style="font-weight:600">Planlanan ameliyat yok</div>
            </div>
            @else
            @foreach($upcoming as $surg)
            <div style="padding:16px;background:var(--bg);border-radius:12px;margin-bottom:10px;border-left:4px solid {{ \Carbon\Carbon::parse($surg->surgery_date)->isCurrentWeek() ? '#EF4444' : 'var(--mint)' }}">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px">
                    <div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--navy)">{{ $surg->surgery_name }}</div>
                        <div style="font-size:.82rem;color:var(--text-muted);margin-top:3px">
                            🐾 {{ $surg->pet->pet_name }} ({{ $surg->pet->species }}) ·
                            👤 {{ $surg->pet->owner_name }}
                        </div>
                        @if($surg->doctor_name)
                        <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">🩺 {{ $surg->doctor_name }}</div>
                        @endif
                        @if($surg->pre_notes)
                        <div style="font-size:.78rem;background:#fff;border-radius:6px;padding:6px 10px;margin-top:8px;color:var(--text-muted)">
                            📝 {{ $surg->pre_notes }}
                        </div>
                        @endif
                    </div>
                    <div style="text-align:right">
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:.95rem;color:var(--navy)">
                            {{ \Carbon\Carbon::parse($surg->surgery_date)->format('d.m.Y') }}
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted)">
                            {{ \Carbon\Carbon::parse($surg->surgery_date)->format('H:i') }}
                        </div>
                        <div style="margin-top:8px;display:flex;gap:6px;justify-content:flex-end;flex-wrap:wrap">
                            <form method="POST" action="{{ route('surgeries.status', $surg) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="action-btn edit" style="border:none;cursor:pointer;font-size:.72rem">✅ Tamamlandı</button>
                            </form>
                            <form method="POST" action="{{ route('surgeries.status', $surg) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="action-btn cancel" style="border:none;cursor:pointer" onclick="return confirm('İptal etmek istiyor musunuz?')">İptal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        {{-- Geçmiş Ameliyatlar --}}
        @if($past->isNotEmpty())
        <div class="card">
            <div class="section-head" style="margin-bottom:14px"><h3>🏁 Geçmiş Ameliyatlar</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Tarih</th><th>Ameliyat</th><th>Hayvan</th><th>Doktor</th><th>Durum</th></tr></thead>
                    <tbody>
                        @foreach($past as $surg)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($surg->surgery_date)->format('d.m.Y') }}</td>
                            <td><strong>{{ $surg->surgery_name }}</strong></td>
                            <td>{{ $surg->pet->pet_name }}</td>
                            <td>{{ $surg->doctor_name ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $surg->status === 'completed' ? 'confirmed' : 'cancelled' }}">
                                    {{ $surg->status === 'completed' ? 'Tamamlandı' : 'İptal' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px">{{ $past->links() }}</div>
        </div>
        @endif
    </div>

    {{-- SAĞ: Ameliyat Ekle --}}
    <div id="addForm">
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">🔪 Ameliyat Ekle</div>
            <form method="POST" action="{{ route('surgeries.store') }}">
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
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Ameliyat Adı *</label>
                    <input type="text" name="surgery_name" required placeholder="örn: Kısırlaştırma, Tümör"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Tarih *</label>
                        <input type="date" name="surgery_date" required value="{{ date('Y-m-d') }}"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Saat</label>
                        <input type="time" name="surgery_time" value="09:00"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                </div>
                @if($doctors->isNotEmpty())
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Doktor</label>
                    <select name="doctor_name" style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        <option value="">— Seçin —</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->name }}">{{ $doc->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Doktor Adı</label>
                    <input type="text" name="doctor_name" placeholder="Dr. Adı"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                @endif
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Ön Notlar</label>
                    <textarea name="pre_notes" rows="2" placeholder="Ameliyat öncesi notlar..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;resize:vertical;outline:none"></textarea>
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">🔪 Takvime Ekle</button>
            </form>
        </div>
    </div>
</div>

@endsection