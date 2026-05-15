@extends('layouts.dashboard')
@section('title','Doktor Yönetimi')
@section('page-title','🩺 Doktor Yönetimi')

@section('content')

@if(session('success'))
<div style="background:#D1FAE5;border:1px solid #6EE7B7;border-radius:10px;padding:12px 18px;margin-bottom:20px;color:#065F46;font-weight:600;font-size:.88rem">✅ {{ session('success') }}</div>
@endif

<div class="content-grid">
    <div>
        @if($doctors->isEmpty())
        <div class="card" style="text-align:center;padding:60px 0">
            <div style="font-size:4rem;margin-bottom:16px">🩺</div>
            <div style="font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:var(--navy);margin-bottom:8px">Henüz doktor eklenmedi</div>
            <div style="font-size:.88rem;color:var(--text-muted)">Sağdaki formu kullanarak doktor ekleyin.</div>
        </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
            @foreach($doctors as $doc)
            <div class="card" style="padding:22px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px">
                    <div style="width:52px;height:52px;border-radius:14px;background:{{ $doc->is_active ? 'var(--mint-dim)' : 'var(--bg)' }};display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0">🩺</div>
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;color:var(--navy)">{{ $doc->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted)">{{ $doc->specialty ?? 'Genel Veteriner' }}</div>
                        <span style="font-size:.68rem;padding:2px 8px;border-radius:50px;font-weight:700;background:{{ $doc->is_active ? '#D1FAE5' : '#F3F4F6' }};color:{{ $doc->is_active ? '#065F46' : '#6B7280' }}">
                            {{ $doc->is_active ? '● Aktif' : '○ Pasif' }}
                        </span>
                    </div>
                </div>
                @if($doc->phone)
                <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:4px">📞 {{ $doc->phone }}</div>
                @endif
                @if($doc->email)
                <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:12px">✉️ {{ $doc->email }}</div>
                @endif
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:14px">
                    📅 {{ $doc->appointments_count }} randevu
                </div>
                <div style="display:flex;gap:8px">
                    @if($doc->is_active)
                    <form method="POST" action="{{ route('doctors.destroy', $doc) }}" style="flex:1">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-outline" style="width:100%;font-size:.78rem;padding:8px;justify-content:center"
                            onclick="return confirm('Doktoru pasif yapmak istiyor musunuz?')">
                            Pasif Yap
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('doctors.update', $doc) }}" style="flex:1">
                        @csrf @method('PATCH')
                        <input type="hidden" name="name" value="{{ $doc->name }}">
                        <input type="hidden" name="is_active" value="1">
                        <button type="submit" class="btn-add" style="width:100%;font-size:.78rem;padding:8px;justify-content:center">Aktif Et</button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- SAĞ: Doktor Ekle --}}
    <div>
        <div class="card" style="position:sticky;top:84px">
            <div class="card-title" style="margin-bottom:18px">🩺 Doktor Ekle</div>
            <form method="POST" action="{{ route('doctors.store') }}">
                @csrf
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Ad Soyad *</label>
                    <input type="text" name="name" required placeholder="Dr. Mehmet Yılmaz"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Uzmanlık</label>
                    <input type="text" name="specialty" placeholder="Ortopedi, Dahiliye..."
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:12px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">Telefon</label>
                    <input type="text" name="phone" placeholder="05321234567"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <div style="margin-bottom:16px">
                    <label style="font-size:.8rem;font-weight:600;color:var(--text);display:block;margin-bottom:5px">E-posta</label>
                    <input type="email" name="email" placeholder="dr@klinik.com"
                        style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none">
                </div>
                <button type="submit" class="btn-add" style="width:100%;justify-content:center">🩺 Doktor Ekle</button>
            </form>
        </div>
    </div>
</div>

@endsection