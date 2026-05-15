@extends('layouts.dashboard')
@section('title','Duyurular')
@section('page-title','📣 Duyurular & WhatsApp')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="widget-grid" style="margin-bottom:24px">
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Toplam Müşteri</div><div class="widget-icon mint">👥</div></div>
        <div class="widget-num">{{ $totalPets }}</div>
        <div class="widget-detail">Kayıtlı hasta sahibi</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">Hayvan Türleri</div><div class="widget-icon blue">🐾</div></div>
        <div class="widget-num">{{ $speciesList->count() }}</div>
        <div class="widget-detail">Farklı tür</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">WhatsApp</div><div class="widget-icon mint">💬</div></div>
        <div class="widget-num" style="font-size:1.1rem;padding-top:6px">Ücretsiz</div>
        <div class="widget-detail" style="color:#10B981;font-weight:600">✓ Paketinize dahil</div>
    </div>
    <div class="widget">
        <div class="widget-header"><div class="widget-label">SMS</div><div class="widget-icon gold">💬</div></div>
        @php $sms = auth()->user()->smsUsage; @endphp
        <div class="widget-num">{{ $sms ? $sms->remaining() : 0 }}</div>
        <div class="widget-detail">Kalan SMS</div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-title" style="margin-bottom:6px">📋 Duyuru Rehberi</div>
        <div style="font-size:.83rem;color:var(--text-muted);margin-bottom:20px">Müşterilerinize toplu mesaj gönderin. WhatsApp mesajları ücretsiz, SMS paketinizden düşer.</div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px">
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:1.5px solid var(--border)">
                <div style="font-size:1.5rem;margin-bottom:8px">🎂</div>
                <div style="font-weight:700;font-size:.88rem;color:var(--navy);margin-bottom:4px">Doğum Günü</div>
                <div style="font-size:.75rem;color:var(--text-muted)">Bu hafta doğum günü olan hayvan sahiplerine kutlama</div>
            </div>
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:1.5px solid var(--border)">
                <div style="font-size:1.5rem;margin-bottom:8px">💉</div>
                <div style="font-weight:700;font-size:.88rem;color:var(--navy);margin-bottom:4px">Aşı Hatırlatma</div>
                <div style="font-size:.75rem;color:var(--text-muted)">Yaklaşan aşısı olan müşterilere hatırlatma</div>
            </div>
            <div style="padding:16px;background:var(--bg);border-radius:12px;border:1.5px solid var(--border)">
                <div style="font-size:1.5rem;margin-bottom:8px">📢</div>
                <div style="font-weight:700;font-size:.88rem;color:var(--navy);margin-bottom:4px">Genel Duyuru</div>
                <div style="font-size:.75rem;color:var(--text-muted)">Tatil, fiyat değişikliği gibi genel bilgilendirme</div>
            </div>
        </div>

        {{-- Gönderilen son duyurular placeholder --}}
        <div style="padding:30px 0;text-align:center;color:var(--text-muted)">
            <div style="font-size:2.5rem;margin-bottom:10px">📬</div>
            <div style="font-weight:600;font-size:.9rem">Henüz duyuru gönderilmedi</div>
            <div style="font-size:.8rem;margin-top:4px">Sağdaki formu kullanarak ilk duyurunuzu gönderin.</div>
        </div>
    </div>

    {{-- SAĞ: Duyuru Formu --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">📣 Duyuru Gönder</div>
            <form method="POST" action="{{ route('announcements.store') }}">
                @csrf

                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Gönderim Kanalı *</label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <label style="display:flex;align-items:center;gap:8px;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;font-size:.85rem;font-weight:600">
                            <input type="radio" name="channel" value="whatsapp" checked style="accent-color:var(--mint)"> 💬 WhatsApp
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;font-size:.85rem;font-weight:600">
                            <input type="radio" name="channel" value="sms" style="accent-color:var(--mint)"> 📱 SMS
                        </label>
                    </div>
                </div>

                <div style="margin-bottom:14px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Hedef Kitle *</label>
                    <select name="audience" id="audienceSelect" onchange="toggleSpecies(this.value)"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        <option value="all">Tüm müşteriler ({{ $totalPets }} kişi)</option>
                        <option value="species">Belirli hayvan türü sahipleri</option>
                    </select>
                </div>

                <div id="speciesField" style="margin-bottom:14px;display:none">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Hayvan Türü *</label>
                    <select name="species"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                        @foreach($speciesList as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:6px">Mesaj *</label>
                    <textarea name="message" required rows="5" maxlength="500" id="msgArea"
                        placeholder="Merhaba, kliniğimizden önemli bir duyurumuz var..."
                        oninput="updateCount(this)"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;resize:vertical;outline:none"></textarea>
                    <div style="text-align:right;font-size:.72rem;color:var(--text-muted);margin-top:3px">
                        <span id="charCount">0</span>/500
                    </div>
                </div>

                <button type="submit" class="btn-add" style="width:100%;justify-content:center">📣 Gönder</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleSpecies(val) {
    document.getElementById('speciesField').style.display = val === 'species' ? 'block' : 'none';
}
function updateCount(el) {
    document.getElementById('charCount').textContent = el.value.length;
}
</script>
@endpush

@endsection