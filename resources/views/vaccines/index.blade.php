@extends('layouts.dashboard')
@section('title','Aşı Takibi')
@section('page-title','💉 Aşı Takibi')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">
    ✅ {{ session('success') }}
</div>
@endif

{{-- ÖZET WİDGET'LAR --}}
<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Gecikmiş Aşılar</div><div class="widget-icon red">⚠️</div></div>
        <div class="widget-num" style="{{ $overdueVaccines->isNotEmpty() ? 'color:#EF4444' : '' }}">{{ $overdueVaccines->count() }}</div>
        <div class="widget-detail {{ $overdueVaccines->isNotEmpty() ? 'danger' : '' }}">
            {{ $overdueVaccines->isNotEmpty() ? 'Acil hatırlatma gerekli!' : 'Gecikmiş aşı yok ✓' }}
        </div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Bu Ay Yaklaşan</div><div class="widget-icon gold">⏰</div></div>
        <div class="widget-num">{{ $upcomingVaccines->count() }}</div>
        <div class="widget-detail">30 gün içinde</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Toplam Kayıt</div><div class="widget-icon mint">💉</div></div>
        <div class="widget-num">{{ $allVaccines->total() }}</div>
        <div class="widget-detail">Tüm aşı kayıtları</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Kayıtlı Hasta</div><div class="widget-icon blue">🐾</div></div>
        <div class="widget-num">{{ $pets->count() }}</div>
        <div class="widget-detail">Aşı eklenebilir hasta</div>
    </div>
</div>

<div class="content-grid">
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Gecikmiş Aşılar --}}
        @if($overdueVaccines->isNotEmpty())
        <div class="card" style="border-color:#FCA5A5">
            <div class="section-head" style="margin-bottom:14px">
                <h3 style="color:#EF4444">⚠️ Gecikmiş Aşılar</h3>
                <span style="background:#FEE2E2;color:#991B1B;padding:4px 10px;border-radius:50px;font-size:.72rem;font-weight:700">{{ $overdueVaccines->count() }} adet</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Hayvan</th><th>Sahip</th><th>Aşı</th><th>Yapılması Gereken</th><th>Gecikme</th></tr></thead>
                    <tbody>
                        @foreach($overdueVaccines as $vac)
                        <tr>
                            <td><div style="font-weight:600">{{ $vac->pet->pet_name }}</div><div style="font-size:.72rem;color:var(--text-muted)">{{ $vac->pet->species }}</div></td>
                            <td>{{ $vac->pet->owner_name }}</td>
                            <td>{{ $vac->vaccine_name }}</td>
                            <td style="color:#EF4444;font-weight:600">{{ $vac->next_date->format('d.m.Y') }}</td>
                            <td><span style="background:#FEE2E2;color:#991B1B;padding:3px 8px;border-radius:50px;font-size:.72rem;font-weight:700">{{ $vac->next_date->diffForHumans() }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Yaklaşan Aşılar --}}
        @if($upcomingVaccines->isNotEmpty())
        <div class="card" style="border-color:#FDE68A">
            <div class="section-head" style="margin-bottom:14px">
                <h3 style="color:#92400E">⏰ Bu Ay Yaklaşan Aşılar</h3>
                <span style="background:#FEF3C7;color:#92400E;padding:4px 10px;border-radius:50px;font-size:.72rem;font-weight:700">{{ $upcomingVaccines->count() }} adet</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Hayvan</th><th>Sahip</th><th>Aşı</th><th>Tarih</th><th>Kalan Süre</th></tr></thead>
                    <tbody>
                        @foreach($upcomingVaccines as $vac)
                        <tr>
                            <td><a href="{{ route('pets.show', $vac->pet) }}" style="font-weight:600;color:var(--navy);text-decoration:none">{{ $vac->pet->pet_name }}</a><div style="font-size:.72rem;color:var(--text-muted)">{{ $vac->pet->species }}</div></td>
                            <td>{{ $vac->pet->owner_name }}</td>
                            <td>{{ $vac->vaccine_name }}</td>
                            <td style="font-weight:600">{{ $vac->next_date->format('d.m.Y') }}</td>
                            <td><span style="background:#D1FAE5;color:#065F46;padding:3px 8px;border-radius:50px;font-size:.72rem;font-weight:700">{{ $vac->next_date->diffForHumans() }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Tüm Aşı Kayıtları --}}
        <div class="card">
            <div class="section-head" style="margin-bottom:14px">
                <h3>💉 Tüm Aşı Kayıtları</h3>
            </div>
            @if($allVaccines->isEmpty())
            <div style="text-align:center;padding:40px 0;color:var(--text-muted)">
                <div style="font-size:3rem;margin-bottom:12px">💉</div>
                <div style="font-weight:600">Henüz aşı kaydı yok</div>
            </div>
            @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Hayvan</th><th>Aşı Adı</th><th>Yapıldığı Tarih</th><th>Sonraki Tarih</th><th>Yapan</th></tr></thead>
                    <tbody>
                        @foreach($allVaccines as $vac)
                        <tr>
                            <td><a href="{{ route('pets.show', $vac->pet) }}" style="font-weight:600;color:var(--navy);text-decoration:none">{{ $vac->pet->pet_name }}</a><div style="font-size:.72rem;color:var(--text-muted)">{{ $vac->pet->species }}</div></td>
                            <td>{{ $vac->vaccine_name }}</td>
                            <td>{{ $vac->vaccine_date->format('d.m.Y') }}</td>
                            <td>
                                @if($vac->next_date)
                                <span style="font-weight:600;color:{{ $vac->next_date->isPast() ? '#EF4444' : ($vac->next_date->diffInDays()<30 ? '#F59E0B' : '#10B981') }}">
                                    {{ $vac->next_date->format('d.m.Y') }}
                                </span>
                                @else
                                <span style="color:var(--text-muted)">—</span>
                                @endif
                            </td>
                            <td>{{ $vac->administered_by ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top:16px">{{ $allVaccines->links() }}</div>
            @endif
        </div>
    </div>

    {{-- SAĞ: Yeni Aşı Formu --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">💉 Yeni Aşı Ekle</div>
            <form method="POST" action="{{ route('vaccines.store') }}">
                @csrf
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Hasta Seç *</label>
                    <select name="pet_id" required style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        <option value="">— Hasta seçin —</option>
                        @foreach($pets as $pet)
                        <option value="{{ $pet->id }}">{{ $pet->pet_name }} ({{ $pet->owner_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Aşı Adı *</label>
                    <input type="text" name="vaccine_name" required placeholder="örn: Karma Aşı, Kuduz"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Yapıldığı Tarih *</label>
                    <input type="date" name="vaccine_date" required value="{{ date('Y-m-d') }}"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Sonraki Aşı Tarihi</label>
                    <input type="date" name="next_date"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Yapan Kişi</label>
                    <input type="text" name="administered_by" placeholder="Dr. Adı"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">
                    💉 Aşıyı Kaydet
                </button>
            </form>
        </div>
    </div>
</div>

@endsection