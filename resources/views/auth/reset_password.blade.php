<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquaWatt — Réinitialisation</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'DM Sans',sans-serif;background:#04060f;color:#e8eeff;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:20px}
    .container{width:100%;max-width:420px;padding:35px;border-radius:16px;background:rgba(9,12,26,0.7);backdrop-filter:blur(20px);border:1px solid rgba(61,127,255,0.25);box-shadow:0 0 40px rgba(61,127,255,0.15)}
    h2{text-align:center;font-family:'Syne',sans-serif;font-size:22px;margin-bottom:8px;color:#3d7fff}
    p.sub{text-align:center;font-size:13px;color:#7d8590;margin-bottom:24px}
    .input-group{margin-bottom:14px}
    input{width:100%;padding:12px;border-radius:8px;border:1px solid rgba(255,255,255,0.05);background:#111628;color:white;outline:none;transition:all .25s;font-family:inherit}
    input:focus{border-color:#3d7fff;box-shadow:0 0 10px rgba(61,127,255,0.3)}
    .error-msg{color:#ff4d4d;font-size:11px;margin-top:4px;display:block}
    button{width:100%;background:linear-gradient(135deg,#3d7fff,#00cfff);border:none;padding:12px;color:white;border-radius:8px;cursor:pointer;font-weight:600;margin-top:6px;font-family:inherit;transition:all .25s}
    button:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(61,127,255,0.3)}
  </style>
</head>
<body>
<div class="container">
  <h2>AquaWatt</h2>
  <p class="sub">Choisissez un nouveau mot de passe.</p>

  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="input-group">
      <input type="email" name="email" placeholder="Email" value="{{ $email ?? old('email') }}">
      @error('email')<span class="error-msg">{{ $message }}</span>@enderror
    </div>

    <div class="input-group">
      <input type="password" name="password" placeholder="Nouveau mot de passe">
      @error('password')<span class="error-msg">{{ $message }}</span>@enderror
    </div>

    <div class="input-group">
      <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe">
    </div>

    <button type="submit">Réinitialiser</button>
  </form>
</div>
</body>
</html>