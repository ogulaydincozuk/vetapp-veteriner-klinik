@extends('layouts.dashboard')
@section('title','Bekleme Listesi')
@section('page-title','⏳ Bekleme Listesi')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bekleyen</div><div class="widget-icon red">⏳</div></div>
        <div class="widget-num" style="{{ $waiting->count() > 0 ? 'color:#EF4444' : '' }}">{{ $waiting->count() }}</div>
        <div class="widget-detail {{ $waiting->count() > 0 ? 'danger' : '' }}">Randevu bekliyor</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">İletişime Geçildi</div><div class="widget-icon gold">📞</div></div>
        <div class="widget-num">{{ $contacted->count() }}</div>
        <div class="widget-detail">Arandı, yanıt bekleniyor</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Randevu Alındı</div><div class="widget-icon mint">✅</div></div>
        <div class="widget-num">{{ $booked->count() }}</div>
        <div class="widget-detail">Başarıyla randevu oluşturuldu</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Toplam</div><div class="widget-icon blue">📋</div></div>
        <div class="widget-num">{{ $waiting->count() + $contacted->count() + $booked->count() }}</div>
        <div class="widget-detail">Tüm kayıtlar</div>
    </div>
</div>

<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Bekleyenler --}}
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>⏳ Bekleyenler</h3>
                @if($waiting->isNotEmpty())
                <span style="background:#FEE2E2;color:#991B1B;padding:4px 10px;border-radius:50px;font-size:.72rem;font-weight:700">{{ $waiting->count() }} kişi</span>
                @endif
            </div>
            @if($waiting->isEmpty())
            <div style="text-align:center;padding:30px 0;color:var(--text-muted);font-size:.83rem">
                <div style="font-size:2.5rem;margin-bottom:8px">✅</div>
                Bekleme listesi boş!
            </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Sahip</th><th>Telefon</th><th>Hayvan</th><th>Tercih Tarihi</th><th>Sebep</th><th>İşlem</th></tr></thead>
                    <tbody>
                        @foreach($waiting as $item)
                        <tr>
                            <td><strong>{{ $item->owner_name }}</strong></td>
                            <td>{{ $item->owner_phone }}</td>
                            <td>{{ $item->pet_name }}</td>
                            <td>{{ $item->preferred_date ? $item->preferred_date->format('d.m.Y') : '—' }}</td>
                            <td style="color:var(--text-muted)">{{ $item->reason ?? '—' }}</td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    <form method="POST" action="{{ route('waiting.status', $item) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="contacted">
                                        <button type="submit" class="action-btn edit" style="border:none;cursor:pointer;font-size:.72rem">📞 Arandı</button>
                                    </form>
                                    <form method="POST" action="{{ route('waiting.status', $item) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="booked">
                                        <button type="submit" class="action-btn view" style="font-size:.72rem">✅ Randevu</button>
                                    </form>
                                    <form method="POST" action="{{ route('waiting.destroy', $item) }}"
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
            @endif
        </div>

        {{-- İletişime Geçilenler --}}
        @if($contacted->isNotEmpty())
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>📞 İletişime Geçilenler</h3>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Sahip</th><th>Telefon</th><th>Hayvan</th><th>İşlem</th></tr></thead>
                    <tbody>
                        @foreach($contacted as $item)
                        <tr>
                            <td><strong>{{ $item->owner_name }}</strong></td>
                            <td>{{ $item->owner_phone }}</td>
                            <td>{{ $item->pet_name }}</td>
                            <td>
                                <form method="POST" action="{{ route('waiting.status', $item) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="booked">
                                    <button type="submit" class="action-btn edit" style="border:none;cursor:pointer">✅ Randevu Alındı</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- SAĞ: Yeni Kayıt --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">➕ Listeye Ekle</div>
            <form method="POST" action="{{ route('waiting.store') }}">
                @csrf
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Sahip Adı *</label>
                    <input type="text" name="owner_name" required placeholder="Ayşe Kaya"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Telefon *</label>
                    <input type="text" name="owner_phone" required placeholder="05321234567"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Hayvan Adı *</label>
                    <input type="text" name="pet_name" required placeholder="Buddy"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Tercih Edilen Tarih</label>
                    <input type="date" name="preferred_date"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Sebep</label>
                    <input type="text" name="reason" placeholder="Kontrol, aşı, ameliyat..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">⏳ Listeye Ekle</button>
            </form>
        </div>
    </div>
</div>

@endsection