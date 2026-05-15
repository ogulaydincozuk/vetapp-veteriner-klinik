@extends('layouts.dashboard')
@section('title','Randevular')
@section('page-title','📅 Randevular')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">
    ✅ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#FEE2E2;border:1px solid #FCA5A5;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#991B1B;font-weight:600;font-size:.88rem">
    ⚠️ {{ session('error') }}
</div>
@endif

{{-- ÜST ÖZET --}}
<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bugün</div><div class="widget-icon mint">📅</div></div>
        <div class="widget-num">{{ $todayAppointments->count() }}</div>
        <div class="widget-detail">Bugünkü randevular</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Yaklaşan</div><div class="widget-icon gold">⏰</div></div>
        <div class="widget-num">{{ $upcomingAppointments->count() }}</div>
        <div class="widget-detail">Önümüzdeki randevular</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bekleyen</div><div class="widget-icon blue">🔄</div></div>
        <div class="widget-num">{{ $appointments->where('status','pending')->count() }}</div>
        <div class="widget-detail">Onay bekleyen</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Tamamlanan</div><div class="widget-icon mint">✅</div></div>
        <div class="widget-num">{{ $appointments->where('status','completed')->count() }}</div>
        <div class="widget-detail">Bu sayfada</div>
    </div>
</div>

<div class="content-grid">

    {{-- SOL: Randevu Listesi --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Bugünün Randevuları --}}
        @if($todayAppointments->isNotEmpty())
        <div class="card">
            <div class="section-head">
                <h3>🌅 Bugünün Randevuları</h3>
                <span style="font-size:.78rem;background:#D1FAE5;color:#065F46;padding:4px 10px;border-radius:50px;font-weight:700">{{ Carbon\Carbon::today()->format('d.m.Y') }}</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Saat</th><th>Hayvan</th><th>Sahip</th><th>Tür</th><th>Durum</th><th>İşlem</th></tr></thead>
                    <tbody>
                        @foreach($todayAppointments as $appt)
                        <tr>
                            <td><strong>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}</strong></td>
                            <td>
                                <div style="font-weight:600">{{ $appt->pet->pet_name }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted)">{{ $appt->pet->species }}</div>
                            </td>
                            <td>
                                <div>{{ $appt->pet->owner_name }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted)">{{ $appt->pet->owner_phone }}</div>
                            </td>
                            <td>{{ $appt->getTypeLabel() }}</td>
                            <td>
                                <form method="POST" action="{{ route('appointments.status', $appt) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        style="border:1.5px solid var(--border);border-radius:7px;padding:4px 8px;font-size:.75rem;font-weight:600;cursor:pointer;background:#fff;outline:none">
                                        <option value="pending"   {{ $appt->status==='pending'   ? 'selected' : '' }}>⏳ Bekliyor</option>
                                        <option value="confirmed" {{ $appt->status==='confirmed' ? 'selected' : '' }}>✅ Onaylı</option>
                                        <option value="completed" {{ $appt->status==='completed' ? 'selected' : '' }}>🏁 Tamamlandı</option>
                                        <option value="cancelled" {{ $appt->status==='cancelled' ? 'selected' : '' }}>❌ İptal</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px">
                                    <a href="{{ route('pets.show', $appt->pet) }}" class="action-btn view">Hasta</a>
                                    <form method="POST" action="{{ route('appointments.destroy', $appt) }}"
                                        onsubmit="return confirm('Bu randevuyu silmek istediğinize emin misiniz?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn cancel" style="border:none;cursor:pointer">Sil</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Tüm Randevular --}}
        <div class="card">
            <div class="section-head">
                <h3>📋 Tüm Randevular</h3>
            </div>
            @if($appointments->isEmpty())
            <div style="text-align:center;padding:50px 0;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:12px">📭</div>
                <div style="font-weight:600;margin-bottom:6px">Henüz randevu yok</div>
                <div style="font-size:.83rem">Sağdaki formu kullanarak ilk randevunuzu ekleyin.</div>
            </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Tarih</th><th>Saat</th><th>Hayvan</th><th>Sahip</th><th>Tür</th><th>Durum</th><th>İşlem</th></tr></thead>
                    <tbody>
                        @foreach($appointments as $appt)
                        <tr>
                            <td>{{ $appt->appointment_date->format('d.m.Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($appt->appointment_time)->format('H:i') }}</td>
                            <td>
                                <div style="font-weight:600">{{ $appt->pet->pet_name }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted)">{{ $appt->pet->species }}</div>
                            </td>
                            <td>{{ $appt->pet->owner_name }}</td>
                            <td>{{ $appt->getTypeLabel() }}</td>
                            <td><span class="badge {{ $appt->status }}">{{ $appt->getStatusLabel() }}</span></td>
                            <td>
                                <div style="display:flex;gap:6px">
                                    <a href="{{ route('pets.show', $appt->pet) }}" class="action-btn view">Hasta</a>
                                    <form method="POST" action="{{ route('appointments.destroy', $appt) }}"
                                        onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn cancel" style="border:none;cursor:pointer">Sil</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px">{{ $appointments->links() }}</div>
            @endif
        </div>
    </div>

    {{-- SAĞ: Yeni Randevu Formu --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">➕ Yeni Randevu Ekle</div>
            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <div class="form-group" style="margin-bottom:14px">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Hasta Seç *</label>
                    @if($pets->isEmpty())
                        <div style="font-size:.82rem;color:var(--text-muted);background:var(--bg);padding:10px;border-radius:8px">
                            Önce hasta eklemeniz gerekiyor.
                            <a href="{{ route('pets.index') }}" style="color:var(--navy);font-weight:700">→ Hasta Ekle</a>
                        </div>
                    @else
                    <select name="pet_id" required style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none">
                        <option value="">— Hasta seçin —</option>
                        @foreach($pets as $pet)
                        <option value="{{ $pet->id }}">{{ $pet->pet_name }} ({{ $pet->owner_name }})</option>
                        @endforeach
                    </select>
                    @endif
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px">
                    <div>
                        <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Tarih *</label>
                        <input type="date" name="appointment_date" required
                            min="{{ date('Y-m-d') }}"
                            value="{{ date('Y-m-d') }}"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Saat *</label>
                        <input type="time" name="appointment_time" required
                            value="09:00"
                            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none">
                    </div>
                </div>

                <div style="margin-bottom:14px">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Ziyaret Türü *</label>
                    <select name="type" required style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none">
                        <option value="checkup">🔍 Kontrol</option>
                        <option value="vaccine">💉 Aşı</option>
                        <option value="surgery">🔪 Ameliyat</option>
                        <option value="xray">📸 Röntgen</option>
                        <option value="other">📋 Diğer</option>
                    </select>
                </div>

                @if($doctors->isNotEmpty())
                <div style="margin-bottom:14px">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Doktor</label>
                    <select name="doctor_id" style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none">
                        <option value="">— Doktor seçin —</option>
                        @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div style="margin-bottom:16px">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Notlar</label>
                    <textarea name="notes" rows="3" placeholder="Randevu ile ilgili notlar..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none;resize:vertical"></textarea>
                </div>

                <button type="submit" class="btn-add" style="width:100%;justify-content:center">
                    📅 Randevu Oluştur
                </button>
            </form>
        </div>
    </div>
</div>

@endsection