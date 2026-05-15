@extends('layouts.dashboard')
@section('title','Hastalar')
@section('page-title','🐾 Hastalar')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">
    ✅ {{ session('success') }}
</div>
@endif

<div class="content-grid">

    {{-- SOL: Hasta Listesi --}}
    <div>
        {{-- Arama --}}
        <div class="card" style="margin-bottom:20px">
            <form method="GET" action="{{ route('pets.index') }}" style="display:flex;gap:10px">
                <input type="text" name="search" value="{{ $search }}" placeholder="🔍 Hayvan adı, sahip veya tür ile ara..."
                    style="flex:1;padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:.88rem;font-family:'Inter',sans-serif;outline:none">
                <button type="submit" class="btn-add">Ara</button>
                @if($search)
                <a href="{{ route('pets.index') }}" class="btn-outline">Temizle</a>
                @endif
            </form>
        </div>

        @if($pets->isEmpty())
        <div class="card" style="text-align:center;padding:60px 0">
            <div style="font-size:4rem;margin-bottom:16px">🐾</div>
            <div style="font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:var(--navy);margin-bottom:8px">
                {{ $search ? 'Sonuç bulunamadı' : 'Henüz hasta yok' }}
            </div>
            <div style="font-size:.88rem;color:var(--text-muted)">
                {{ $search ? '"'.$search.'" için sonuç yok.' : 'Sağdaki formu kullanarak ilk hastanızı ekleyin.' }}
            </div>
        </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
            @foreach($pets as $pet)
            <div class="card" style="padding:20px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px">
                    <div style="width:48px;height:48px;border-radius:12px;background:var(--mint-dim);display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0">
                        {{ $pet->species === 'Kedi' ? '🐈' : ($pet->species === 'Köpek' ? '🐕' : '🐾') }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--navy);font-size:.95rem">{{ $pet->pet_name }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted)">{{ $pet->species }}{{ $pet->breed ? ' · '.$pet->breed : '' }}</div>
                    </div>
                </div>
                <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:4px">👤 {{ $pet->owner_name }}</div>
                <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:12px">📞 {{ $pet->owner_phone }}</div>
                @if($pet->weight)
                <div style="font-size:.78rem;background:var(--bg);border-radius:7px;padding:5px 10px;display:inline-block;margin-bottom:10px">⚖️ {{ $pet->weight }} kg</div>
                @endif
                <a href="{{ route('pets.show', $pet) }}" class="btn-add" style="width:100%;justify-content:center;font-size:.8rem;padding:9px">
                    Detayları Gör →
                </a>
            </div>
            @endforeach
        </div>
        <div style="margin-top:20px">{{ $pets->withQueryString()->links() }}</div>
        @endif
    </div>

    {{-- SAĞ: Yeni Hasta Formu --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">➕ Yeni Hasta Ekle</div>
            <form method="POST" action="{{ route('pets.store') }}">
                @csrf

                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Hayvan Adı *</label>
                    <input type="text" name="pet_name" required placeholder="Buddy"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;color:var(--text);background:#fff;outline:none">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Tür *</label>
                        <input type="text" name="species" required placeholder="Köpek"
                            style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Cins</label>
                        <input type="text" name="breed" placeholder="Labrador"
                            style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Cinsiyet</label>
                        <select name="gender" style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;background:#fff;outline:none">
                            <option value="unknown">Bilinmiyor</option>
                            <option value="male">Erkek</option>
                            <option value="female">Dişi</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Kilo (kg)</label>
                        <input type="number" name="weight" step="0.1" min="0" placeholder="4.5"
                            style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                    </div>
                </div>

                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Doğum Tarihi</label>
                    <input type="date" name="birth_date"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>

                <div style="border-top:1px solid var(--border);padding-top:12px;margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Sahip Adı *</label>
                    <input type="text" name="owner_name" required placeholder="Ayşe Kaya"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>

                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Sahip Telefonu *</label>
                    <input type="text" name="owner_phone" required placeholder="05321234567"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>

                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Notlar</label>
                    <textarea name="notes" rows="2" placeholder="Özel notlar..."
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;resize:vertical;outline:none"></textarea>
                </div>

                <button type="submit" class="btn-add" style="width:100%;justify-content:center">
                    🐾 Hastayı Kaydet
                </button>
            </form>
        </div>
    </div>
</div>

@endsection