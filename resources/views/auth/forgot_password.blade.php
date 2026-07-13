<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquaWatt — Mot de passe oublié</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'DM Sans',sans-serif;background:#04060f;color:#e8eeff;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:20px}
    .container{width:100%;max-width:420px;padding:35px;border-radius:16px;background:rgba(9,12,26,0.7);backdrop-filter:blur(20px);border:1px solid rgba(61,127,255,0.25);box-shadow:0 0 40px rgba(61,127,255,0.15)}
    h2{text-align:center;font-family:'Syne',sans-serif;font-size:22px;margin-bottom:8px;color:#3d7fff}
    p.sub{text-align:center;font-size:13px;color:#7d8590;margin-bottom:24px}
    input{width:100%;padding:12px;border-radius:8px;border:1px solid rgba(255,255,255,0.05);background:#111628;color:white;outline:none;transition:all .25s;font-family:inherit;margin-bottom:4px}
    input:focus{border-color:#3d7fff;box-shadow:0 0 10px rgba(61,127,255,0.3)}
    .error-msg{color:#ff4d4d;font-size:11px;margin-bottom:12px;display:block}
    .success-msg{color:#4ade80;font-size:12px;text-align:center;margin-bottom:12px}
    button{width:100%;background:linear-gradient(135deg,#3d7fff,#00cfff);border:none;padding:12px;color:white;border-radius:8px;cursor:pointer;font-weight:600;margin-top:10px;font-family:inherit;transition:all .25s}
    button:hover{transform:translateY(-2px);box-shadow:0 10px 20px rgba(61,127,255,0.3)}
    .back{text-align:center;font-size:12px;margin-top:16px}
    .back a{color:#6699ff;text-decoration:none}
  </style>
</head>
<body>
<div class="container">
  <h2>AquaWatt</h2>
  <p class="sub">Entrez votre email pour recevoir un lien de réinitialisation.</p>

  @if(session('success'))
    <div class="success-msg">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
    @error('email')<span class="error-msg">{{ $message }}</span>@enderror
    <button type="submit">Envoyer le lien</button>
  </form>

  <div class="back"><a href="{{ route('login') }}">← Retour à la connexion</a></div>
</div>
</body>
</html>