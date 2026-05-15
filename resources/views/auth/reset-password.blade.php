<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Şifre Sıfırla — VETAPP</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;background:#0A192F;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.box{background:#fff;border-radius:20px;padding:44px 40px;width:100%;max-width:420px}
.logo{font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:#0A192F;margin-bottom:8px}
h2{font-family:'Poppins',sans-serif;font-size:1.2rem;font-weight:700;color:#0A192F;margin-bottom:6px}
p{font-size:.85rem;color:#6B7280;margin-bottom:24px}
label{font-size:.8rem;font-weight:600;color:#1F2937;display:block;margin-bottom:5px}
input{width:100%;padding:12px 14px;border:1.5px solid #E5E7EB;border-radius:10px;font-size:.87rem;font-family:'Inter',sans-serif;outline:none;margin-bottom:14px}
input:focus{border-color:#64FFDA}
.btn{width:100%;padding:13px;background:#0A192F;color:#64FFDA;font-weight:700;font-size:.92rem;border:none;border-radius:12px;cursor:pointer;font-family:'Poppins',sans-serif}
.error{background:#FEE2E2;border:1px solid #FCA5A5;border-radius:10px;padding:12px 16px;color:#991B1B;font-weight:600;font-size:.85rem;margin-bottom:16px}
</style>
</head>
<body>
<div class="box">
    <div class="logo">🐾 VETAPP</div>
    <h2>Yeni Şifre Belirle</h2>
    <p>Hesabınız için güvenli bir şifre oluşturun.</p>

    @if($errors->any())
    <div class="error">⚠️ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label>E-posta</label>
        <input type="email" name="email" value="{{ old('email',$email) }}" required>
        <label>Yeni Şifre *</label>
        <input type="password" name="password" required placeholder="En az 8 karakter">
        <label>Yeni Şifre (Tekrar) *</label>
        <input type="password" name="password_confirmation" required>
        <button type="submit" class="btn">🔒 Şifremi Sıfırla</button>
    </form>
</div>
</body>
</html>