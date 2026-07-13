<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
    <link rel="icon" href="{{ asset('image.png') }}">
    <title>AquaWatt — Connexion & Inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{
            font-family:'DM Sans',sans-serif;
            background:#04060f;
            color:#e8eeff;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            overflow:hidden;
            padding: 20px;
        }
        body::before, body::after{
            content:''; position:absolute; filter:blur(80px); z-index: -1;
        }
        body::before{
            width:500px; height:500px; top:-100px; left:-100px;
            background:radial-gradient(circle, rgba(61,127,255,0.2), transparent);
            animation:float 8s ease-in-out infinite alternate;
        }
        body::after{
            width:400px; height:400px; bottom:-100px; right:-100px;
            background:radial-gradient(circle, rgba(0,207,255,0.15), transparent);
            animation:float 10s ease-in-out infinite alternate-reverse;
        }
        @keyframes float{ from{transform:translate(0,0)} to{transform:translate(40px,-40px)} }
        .container{
            width:100%;
            max-width:450px;
              position:relative;
              z-index:2;
            padding:35px;
            border-radius:16px;
            background:rgba(9,12,26,0.7);
            backdrop-filter:blur(20px);
            border:1px solid rgba(61,127,255,0.25);
            box-shadow:0 0 40px rgba(61,127,255,0.15);
            animation:fadeIn .8s ease;
        }
        @keyframes fadeIn{ from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:none} }
        h2{ text-align:center; font-family:'Syne',sans-serif; font-size:24px; margin-bottom:20px; color:#3d7fff; }
        .tabs{ display:flex; margin-bottom:20px; border-bottom:1px solid rgba(255,255,255,0.05); }
        .tabs button{
            flex:1; padding:12px; background:none; border:none; color:#aaa;
            cursor:pointer; transition:all .3s; font-family:inherit; font-weight: 500;
        }
        .tabs button.active{ color:white; border-bottom:2px solid #3d7fff; }
        #loginForm, #registerForm { display:none; animation:fadeIn .4s ease; }
        #loginForm.active { display:flex; flex-direction:column; gap:14px; }
        #registerForm.active { display:grid; grid-template-columns: 1fr 1fr; gap:12px; }
        .input-group { display: flex; flex-direction: column; }
        .input-group.full { grid-column: span 2; }
        input {
            width:100%; padding:12px; border-radius:8px; border:1px solid rgba(255,255,255,0.05);
            background:#111628; color:white; outline:none; transition:all .25s; font-family:inherit;
        }
        input:focus { border-color:#3d7fff; box-shadow:0 0 10px rgba(61,127,255,0.3); }
        .error-msg { color: #ff4d4d; font-size: 11px; margin-top: 4px; display: block; }
        button.submit {
            background:linear-gradient(135deg,#3d7fff,#00cfff);
            border:none; padding:12px; color:white; border-radius:8px;
            cursor:pointer; font-weight:600; transition:all .25s; margin-top: 10px;
        }
        button.submit:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(61,127,255,0.3); }
        button.submit.full { grid-column: span 2; }
        .small{ text-align:center; font-size:12px; color:#aaa; margin-top:20px; }
        @media (max-width:500px){
            #registerForm.active { grid-template-columns: 1fr; }
            .input-group.full { grid-column: span 1; }
            button.submit.full { grid-column: span 1; }
        }
        /* RINGS */
.ring-wrap{position:absolute;pointer-events:none;z-index:1}
.ring{border-radius:50%;position:relative}
.ring-a{animation:ringSpin 14s linear infinite}
.ring-b{animation:ringSpin 9s linear infinite reverse}
.ring-c{animation:ringSpin 22s linear infinite}
.ring-d{animation:ringSpin 6s linear infinite reverse}
@keyframes ringSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}

.rdot{position:absolute;border-radius:50%}
.rdot-blue{width:8px;height:8px;background:#6699ff;box-shadow:0 0 12px #6699ff,0 0 24px #3d7fff;top:-4px;left:calc(50% - 4px)}
.rdot-cyan{width:6px;height:6px;background:#00cfff;box-shadow:0 0 10px #00cfff,0 0 20px #00cfff;bottom:-3px;right:calc(50% - 3px)}
.rdot-purple{width:6px;height:6px;background:#a78bfa;box-shadow:0 0 10px #a78bfa,0 0 20px #7c3aed;top:-3px;left:calc(50% - 3px)}
.rdot-amber{width:5px;height:5px;background:#fbbf24;box-shadow:0 0 8px #fbbf24,0 0 16px #f59e0b;bottom:-2.5px;right:calc(50% - 2.5px)}
    </style>
</head>
<body>
    <!-- Ring 1 — large, top right, blue/cyan -->
<div class="ring-wrap" style="top:8%;right:5%">
  <div class="ring ring-a" style="width:220px;height:220px;border:1px solid rgba(61,127,255,.35)">
    <div class="rdot rdot-blue"></div>
    <div class="ring ring-b" style="width:140px;height:140px;border:1px dashed rgba(0,207,255,.25);position:absolute;top:40px;left:40px">
      <div class="rdot rdot-cyan"></div>
    </div>
  </div>
</div>

<!-- Ring 2 — medium, bottom left, purple/amber -->
<div class="ring-wrap" style="bottom:14%;left:3%;opacity:.75">
  <div class="ring ring-c" style="width:150px;height:150px;border:1px solid rgba(167,139,250,.3)">
    <div class="rdot rdot-purple"></div>
    <div class="ring ring-d" style="width:88px;height:88px;border:1px dashed rgba(251,191,36,.25);position:absolute;top:31px;left:31px">
      <div class="rdot rdot-amber"></div>
    </div>
  </div>
</div>

<!-- Ring 3 — tiny accent, mid left -->
<div class="ring-wrap" style="top:40%;left:5%;opacity:.5">
  <div class="ring ring-b" style="width:70px;height:70px;border:1px solid rgba(0,207,255,.25)">
    <div class="rdot rdot-cyan" style="width:5px;height:5px;top:-2.5px;left:calc(50% - 2.5px)"></div>
  </div>
</div>

<!-- Ring 4 — tiny accent, top left -->
<div class="ring-wrap" style="top:20%;left:8%;opacity:.35">
  <div class="ring ring-a" style="width:45px;height:45px;border:1px dashed rgba(167,139,250,.4);animation-duration:8s">
    <div class="rdot rdot-purple" style="width:4px;height:4px;top:-2px;left:calc(50% - 2px)"></div>
  </div>
</div>

<div class="container">
        <h2> 
            <a href="{{ route('landing') }}" class="logo"  style="text-decoration: none;color:cornflowerblue">  AquaWatt </a>
        </h2>

@php
    if ($errors->register->any()) {
        $showRegister = true;
    } elseif ($errors->login->any()) {
        $showRegister = false;
    } else {
        $showRegister = request('tab') === 'register';
    }
    $showLogin = !$showRegister;
@endphp

    <div class="tabs">
        <button id="loginTab"    class="{{ $showLogin    ? 'active' : '' }}">Connexion</button>
        <button id="registerTab" class="{{ $showRegister ? 'active' : '' }}">Inscription</button>
    </div>

    <form id="loginForm" class="{{ $showLogin ? 'active' : '' }}" method="POST" action="{{ route('authentification') }}">
        @csrf
        <div class="input-group">
            <input type="text" name="gmail" placeholder="Email" value="{{ old('gmail') }}">
            @if($errors->login->has('gmail'))
                <span class="error-msg">{{ $errors->login->first('gmail') }}</span>
            @endif
        </div>
        <div class="input-group">
            <input type="password" name="pass" placeholder="Mot de passe">
            @if($errors->login->has('pass'))
                <span class="error-msg">{{ $errors->login->first('pass') }}</span>
            @endif
        </div>
        <button type="submit" class="submit">Se connecter</button>
            <p style="text-align:center;font-size:12px;margin-top:8px">
            <a href="{{ route('password.request') }}" style="color:#6699ff;text-decoration:none">Mot de passe oublié ?</a>
            </p>
    </form>

    <form id="registerForm" class="{{ $showRegister ? 'active' : '' }}" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group">
            <input type="text" name="name" placeholder="Nom complet" value="{{ old('name') }}">
            @if($errors->register->has('name'))
                <span class="error-msg">{{ $errors->register->first('name') }}</span>
            @endif
        </div>

        <div class="input-group">
            <input type="tel" id="phone" name="phone" placeholder="+212..." value="{{ old('phone') }}">
            @if($errors->register->has('phone'))
                <span class="error-msg">{{ $errors->register->first('phone') }}</span>
            @endif
        </div>

        <div class="input-group full">
            <input type="text" name="email" placeholder="Email" value="{{ old('email') }}">
            @if($errors->register->has('email'))
                <span class="error-msg">{{ $errors->register->first('email') }}</span>
            @endif
        </div>

        <div class="input-group full">
            <input type="text" name="adresse" placeholder="Adresse" value="{{ old('adresse') }}">
            @if($errors->register->has('adresse'))
                <span class="error-msg">{{ $errors->register->first('adresse') }}</span>
            @endif
        </div>

        <div class="input-group">
            <input type="text" name="cin" placeholder="CIN" value="{{ old('cin') }}">
            @if($errors->register->has('cin'))
                <span class="error-msg">{{ $errors->register->first('cin') }}</span>
            @endif
        </div>

        <div class="input-group">
            <input type="text" name="contrat_num" placeholder="N° Contrat" value="{{ old('contrat_num') }}">
            @if($errors->register->has('contrat_num'))
                <span class="error-msg">{{ $errors->register->first('contrat_num') }}</span>
            @endif
        </div>
        

        <div class="input-group full">
            <input type="password" name="password" placeholder="Mot de passe">
            @if($errors->register->has('password'))
                <span class="error-msg">{{ $errors->register->first('password') }}</span>
            @endif
        </div>

        <button type="submit" class="submit full">Créer un compte</button>
    </form>

    <p class="small">© 2026 AquaWatt — Gestion Intelligente</p>
</div>

<script>
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    function showLogin() {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
    }

    function showRegister() {
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
    }

    loginTab.onclick = showLogin;
    registerTab.onclick = showRegister;

    const phoneInput = document.getElementById('phone');
    phoneInput.onfocus = function() {
        if(!this.value.startsWith('+212')) this.value = '+212';
    };
</script>
</body>
</html>