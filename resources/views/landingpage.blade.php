<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="{{ asset('image.png') }}">
<title>AquaWatt — Gestion Eau &amp; Électricité</title>
 
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#04060f;
  --s1:#090c1a;
  --s2:#0d1122;
  --s3:#111628;
  --blue:#3d7fff;
  --blue-hi:#6699ff;
  --blue-lo:rgba(61,127,255,0.13);
  --blue-lo2:rgba(61,127,255,0.06);
  --cyan:#00cfff;
  --cyan-lo:rgba(0,207,255,0.1);
  --white:#ffffff;
  --text:#e8eeff;
  --muted:rgba(200,210,255,0.48);
  --muted2:rgba(200,210,255,0.2);
  --border:rgba(61,127,255,0.1);
  --border2:rgba(61,127,255,0.2);
  --display:'Syne',sans-serif;
  --body:'DM Sans',sans-serif;
  --r:16px;
  --r2:24px;
}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--text);font-family:var(--body);overflow-x:hidden;line-height:1.65;-webkit-font-smoothing:antialiased}
body::after{content:'';position:fixed;inset:0;opacity:.5;pointer-events:none;z-index:9998;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.028'/%3E%3C/svg%3E")}

/* NAV */
nav{position:fixed;inset:0 0 auto;z-index:300;display:flex;align-items:center;justify-content:space-between;padding:1rem 3rem;backdrop-filter:blur(30px);background:rgba(4,6,15,0.8);border-bottom:1px solid var(--border)}
.logo{display:flex;align-items:center;gap:9px;text-decoration:none}
.logo-gem{width:32px;height:32px;border-radius:8px;background:linear-gradient(140deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;box-shadow:0 0 18px rgba(61,127,255,.4)}
.logo-gem svg{width:16px;height:16px;fill:white}
.logo-txt{font-family:var(--display);font-size:17px;font-weight:800;color:var(--white);letter-spacing:-.3px}
.logo-txt em{font-style:normal;color:var(--blue-hi)}
.nav-mid{display:flex;gap:2rem;list-style:none}
.nav-mid a{color:var(--muted);text-decoration:none;font-size:13.5px;transition:color .18s}
.nav-mid a:hover{color:var(--white)}
.nav-r{display:flex;gap:10px}
.n-ghost{padding:7px 18px;border:1px solid var(--border2);border-radius:99px;background:transparent;color:var(--text);font-family:var(--body);font-size:13px;text-decoration:none;cursor:pointer;transition:background .2s,border-color .2s}
.n-ghost:hover{background:var(--blue-lo);border-color:var(--blue)}
.n-solid{padding:7px 18px;border-radius:99px;background:var(--blue);color:#fff;font-family:var(--body);font-size:13px;font-weight:500;text-decoration:none;border:none;cursor:pointer;box-shadow:0 0 22px rgba(61,127,255,.35);transition:opacity .2s,transform .18s}
.n-solid:hover{opacity:.86;transform:translateY(-1px)}

/* HERO */
.hero{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:8rem 2rem 5rem;position:relative;overflow:hidden}
.hero-g1{position:absolute;top:-8%;left:10%;width:650px;height:650px;border-radius:50%;background:rgba(61,127,255,.14);filter:blur(140px);pointer-events:none;animation:float1 9s ease-in-out infinite alternate}
.hero-g2{position:absolute;top:20%;right:-5%;width:400px;height:400px;border-radius:50%;background:rgba(0,207,255,.1);filter:blur(110px);pointer-events:none;animation:float1 12s ease-in-out infinite alternate-reverse}
.hero-g3{position:absolute;bottom:-10%;left:35%;width:500px;height:300px;border-radius:50%;background:rgba(61,127,255,.07);filter:blur(120px);pointer-events:none}
@keyframes float1{from{transform:translate(0,0)}to{transform:translate(20px,-25px)}}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(var(--border) 1px,transparent 1px),linear-gradient(90deg,var(--border) 1px,transparent 1px);background-size:56px 56px;mask-image:radial-gradient(ellipse 75% 65% at 50% 45%,black 20%,transparent 100%);pointer-events:none;opacity:.7}

.h-badge{display:inline-flex;align-items:center;gap:7px;padding:5px 14px;border:1px solid var(--border2);border-radius:99px;font-size:12px;color:var(--muted);backdrop-filter:blur(10px);margin-bottom:2rem;animation:fadeup .8s ease both}
.h-dot{width:6px;height:6px;border-radius:50%;background:var(--cyan);box-shadow:0 0 8px var(--cyan);animation:blink 2.2s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1}50%{opacity:.25}}

.h-title{font-family:var(--display);font-size:clamp(2.2rem, 5vw, 4rem);font-weight:800;line-height:1.05;letter-spacing:-2.5px;max-width:800px;margin-bottom:1.4rem;animation:fadeup .85s .08s ease both}
.h-title .hl{background:linear-gradient(90deg,var(--blue-hi),var(--cyan));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.h-sub{font-size:17px;color:var(--muted);max-width:440px;font-weight:300;margin-bottom:2.5rem;animation:fadeup .85s .16s ease both;line-height:1.7}
.h-title .hl-elec{color:#ffce44;-webkit-text-fill-color:#ffce44}

.h-cta{display:flex;gap:12px;flex-wrap:wrap;justify-content:center;animation:fadeup .85s .24s ease both}
.btn-hero{display:inline-flex;align-items:center;gap:10px;padding:13px 26px;border-radius:99px;background:var(--blue);color:#fff;font-family:var(--body);font-size:15px;font-weight:500;border:none;cursor:pointer;text-decoration:none;box-shadow:0 0 36px rgba(61,127,255,.4);transition:opacity .2s,transform .18s}
.btn-hero:hover{opacity:.86;transform:translateY(-2px)}
.btn-hero-arr{width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:13px}
.btn-sec{padding:13px 26px;border-radius:99px;border:1px solid var(--border2);background:transparent;color:var(--text);font-family:var(--body);font-size:15px;cursor:pointer;text-decoration:none;transition:background .2s,border-color .2s}
.btn-sec:hover{background:var(--blue-lo);border-color:var(--blue)}

.h-stats{display:flex;margin:4.5rem auto 0;max-width:660px;width:100%;border:1px solid var(--border);border-radius:20px;background:rgba(255,255,255,.018);backdrop-filter:blur(20px);overflow:hidden;animation:fadeup .85s .32s ease both}
.hst{flex:1;padding:1.4rem 1rem;text-align:center;position:relative}
.hst+.hst::before{content:'';position:absolute;left:0;top:22%;bottom:22%;width:1px;background:var(--border)}
.hst-n{font-family:var(--display);font-size:25px;font-weight:700;letter-spacing:-.8px;color:var(--blue-hi);margin-bottom:3px}
.hst-n.c{color:var(--cyan)}
.hst-n.w{color:var(--white)}
.hst-l{font-size:11.5px;color:var(--muted);letter-spacing:.01em}

/* SHOWCASE */
.showcase{padding:6rem 2rem;max-width:1080px;margin:0 auto}
.sec-top{text-align:center;margin-bottom:3.5rem}
.eyebrow{font-size:11px;letter-spacing:.18em;text-transform:uppercase;color:var(--blue-hi);margin-bottom:.75rem;opacity:.8}
.sec-h{font-family:var(--display);font-size:clamp(1.9rem,3.5vw,2.8rem);font-weight:700;letter-spacing:-1px;margin-bottom:.75rem}
.sec-p{color:var(--muted);font-size:15px;max-width:400px;margin:0 auto;font-weight:300;line-height:1.7}

.dash-mockup{border:1px solid var(--border2);border-radius:var(--r2);overflow:hidden;background:var(--s1);box-shadow:0 0 80px rgba(61,127,255,.1),0 40px 80px rgba(0,0,0,.5);margin-bottom:1.5rem}
.dm-bar{display:flex;align-items:center;gap:8px;padding:.75rem 1.25rem;border-bottom:1px solid var(--border);background:var(--s2)}
.dm-dot{width:10px;height:10px;border-radius:50%}
.dm-url{flex:1;text-align:center;background:var(--bg);border-radius:6px;padding:4px 12px;font-size:11px;color:var(--muted2)}
.dm-body{display:grid;grid-template-columns:200px 1fr;min-height:360px}
.dm-sidebar{border-right:1px solid var(--border);padding:1.25rem 1rem;display:flex;flex-direction:column;gap:6px;background:var(--s2)}
.dm-logo-sm{font-family:var(--display);font-size:13px;font-weight:800;color:var(--white);padding:.5rem .5rem .75rem;border-bottom:1px solid var(--border);margin-bottom:.25rem}
.dm-logo-sm span{color:var(--blue-hi)}
.dm-nav-item{display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:8px;font-size:12px;color:var(--muted);cursor:pointer;transition:background .2s}
.dm-nav-item:hover,.dm-nav-item.active{background:var(--blue-lo);color:var(--blue-hi)}
.dm-nav-item .ico{width:14px;height:14px;opacity:.7}
.dm-main{padding:1.5rem;display:flex;flex-direction:column;gap:1rem}
.dm-head{font-family:var(--display);font-size:15px;font-weight:700;color:var(--white);margin-bottom:.25rem}
.dm-metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
.dm-card{background:var(--s3);border:1px solid var(--border);border-radius:10px;padding:.85rem .9rem}
.dm-card-label{font-size:10px;color:var(--muted2);margin-bottom:5px;letter-spacing:.03em}
.dm-card-val{font-family:var(--display);font-size:18px;font-weight:700;letter-spacing:-.5px}
.dm-card-val.b{color:var(--blue-hi)}
.dm-card-val.c{color:var(--cyan)}
.dm-card-val.w{color:var(--white)}
.dm-card-trend{font-size:10px;color:#4ade80;margin-top:4px}
.dm-table-wrap{background:var(--s3);border:1px solid var(--border);border-radius:10px;overflow:hidden}
.dm-table-head{display:grid;grid-template-columns:1fr 1fr 1fr 80px;padding:.5rem .85rem;background:var(--s2);font-size:10px;color:var(--muted2);letter-spacing:.04em}
.dm-row{display:grid;grid-template-columns:1fr 1fr 1fr 80px;padding:.55rem .85rem;font-size:11px;color:var(--muted);border-top:1px solid var(--border);align-items:center}
.dm-row:hover{background:var(--blue-lo2)}
.dm-badge{display:inline-block;padding:2px 9px;border-radius:99px;font-size:10px;font-weight:500}
.dm-badge.eau{background:rgba(0,207,255,.12);color:var(--cyan)}
.dm-badge.elec{background:rgba(61,127,255,.15);color:var(--blue-hi)}
.dm-badge.ok{background:rgba(74,222,128,.12);color:#4ade80}
.dm-badge.pend{background:rgba(251,191,36,.12);color:#fbbf24}

/* FEATURES */
.feats{padding:2rem 2rem 6rem;max-width:1080px;margin:0 auto}
.feats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:3rem}
.fc{border:1px solid var(--border);border-radius:var(--r2);padding:2rem 1.75rem;background:var(--s1);position:relative;overflow:hidden;transition:border-color .3s,transform .28s}
.fc:hover{border-color:var(--blue);transform:translateY(-5px)}
.fc::before{content:'';position:absolute;top:-70px;right:-50px;width:170px;height:170px;border-radius:50%;background:var(--blue-lo);filter:blur(55px);pointer-events:none}
.fc-icon{width:42px;height:42px;border-radius:11px;background:var(--blue-lo);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:1.2rem}
.fc-title{font-family:var(--display);font-size:15px;font-weight:700;color:var(--white);margin-bottom:.5rem}
.fc-desc{font-size:13px;color:var(--muted);line-height:1.65}
.fc.wide{grid-column:span 2}

/* FLOW */
.flow{padding:2rem 2rem 6rem;max-width:1080px;margin:0 auto}
.flow-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:3rem;position:relative}
.flow-steps::before{content:'';position:absolute;top:28px;left:calc(12.5% + 20px);right:calc(12.5% + 20px);height:1px;background:linear-gradient(90deg,var(--blue-lo),var(--blue),var(--cyan),var(--blue-lo));z-index:0}
.fstep{display:flex;flex-direction:column;align-items:center;text-align:center;padding:0 1rem;position:relative;z-index:1}
.fstep-num{width:56px;height:56px;border-radius:50%;background:var(--s2);border:1px solid var(--border2);display:flex;align-items:center;justify-content:center;font-family:var(--display);font-size:20px;font-weight:700;color:var(--blue-hi);margin-bottom:1rem;box-shadow:0 0 20px rgba(61,127,255,.15)}
.fstep-title{font-family:var(--display);font-size:14px;font-weight:700;color:var(--white);margin-bottom:.4rem}
.fstep-desc{font-size:12px;color:var(--muted);line-height:1.6}

/* CTA BAND */
.cta-band{margin:0 2rem 6rem;border-radius:var(--r2);background:linear-gradient(135deg,var(--s2) 0%,#0a1535 50%,var(--s2) 100%);border:1px solid var(--border2);padding:4rem 3rem;text-align:center;position:relative;overflow:hidden}
.cta-band::before{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:500px;height:300px;background:rgba(61,127,255,.1);border-radius:50%;filter:blur(80px);pointer-events:none}
.cta-band-title{font-family:var(--display);font-size:clamp(2rem,3.5vw,3rem);font-weight:800;letter-spacing:-1.2px;margin-bottom:1rem;position:relative}
.cta-band-sub{color:var(--muted);font-size:16px;max-width:380px;margin:0 auto 2rem;font-weight:300;position:relative}

/* FOOTER */
footer{border-top:1px solid var(--border);padding:2rem 3rem;display:flex;align-items:center;justify-content:space-between}
.foot-logo{font-family:var(--display);font-size:16px;font-weight:800;color:var(--muted)}
.foot-logo span{color:var(--blue-hi)}
.foot-copy{font-size:12px;color:var(--muted2)}
.foot-links{display:flex;gap:1.5rem}
.foot-links a{font-size:12px;color:var(--muted2);text-decoration:none;transition:color .2s}
.foot-links a:hover{color:var(--blue-hi)}

/* REVEAL — bidirectional scroll animation */
.reveal{
  opacity:0;
  transform:translateY(32px);
  transition:opacity .65s cubic-bezier(.22,1,.36,1), transform .65s cubic-bezier(.22,1,.36,1);
}
.reveal.on{
  opacity:1;
  transform:translateY(0);
}

@keyframes fadeup{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:none}}

@media(max-width:800px){
  /* NAV */
  nav{padding:.75rem 1.25rem}
  nav .nav-mid{display:none}
  .nav-r{gap:6px}
  .n-ghost{padding:6px 12px;font-size:12px}
  .n-solid{padding:6px 12px;font-size:12px}

  /* HERO */
  .hero{padding:6rem 1.25rem 3rem}
  .h-title{font-size:clamp(1.8rem,7vw,2.5rem);letter-spacing:-1.5px}
  .h-sub{font-size:15px}
  .btn-hero,.btn-sec{font-size:13px;padding:11px 20px}
  .h-cta{flex-direction:column;align-items:center}
  .h-stats{flex-direction:column;border-radius:14px}
  .hst+.hst::before{content:'';position:absolute;left:10%;right:10%;top:0;width:auto;height:1px;background:var(--border)}
  .hst{position:relative;padding:1rem}

  /* SHOWCASE */
  .dm-body{grid-template-columns:1fr !important}
  .dm-sidebar{display:none !important}
  .dm-metrics{grid-template-columns:repeat(2,1fr) !important}
  .dm-body > div:last-child > div:nth-child(2){flex-wrap:wrap;gap:4px}

  /* FEATURES */
  .feats{padding:2rem 1.25rem 4rem}
  .feats-grid{grid-template-columns:1fr !important}
  .fc.wide{grid-column:span 1 !important}
  .fc{padding:1.5rem 1.25rem}

  /* FLOW */
  .flow{padding:2rem 1.25rem 4rem}
  .flow-steps{grid-template-columns:1fr !important}
  .flow-steps::before{display:none !important}

  /* CTA BAND */
  .cta-band{margin:0 1.25rem 4rem;padding:2.5rem 1.5rem}
  .cta-band-title{font-size:clamp(1.5rem,5vw,2rem);letter-spacing:-.8px}

  /* FOOTER */
  footer{flex-direction:column;gap:1rem;text-align:center;padding:1.5rem 1.25rem}
  .foot-links{flex-wrap:wrap;justify-content:center;gap:1rem}
}
@media(max-width:480px){
  .flow-steps{grid-template-columns:1fr}
  .h-stats .hst-n{font-size:20px}
  .dm-metrics{grid-template-columns:1fr}
  .nav-r .n-ghost{display:none} /* garde juste le bouton principal */
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

<!-- NAV -->
<nav>
  <a href="#" class="logo">
    <div class="logo-gem">
      <svg viewBox="0 0 16 16"><path d="M8 1C8 1 3 6.5 3 10.5a5 5 0 0010 0C13 6.5 8 1 8 1z"/></svg>
    </div>
    <span class="logo-txt">Aqua<em>Watt</em></span>
  </a>
  <ul class="nav-mid">
    <li><a href="#showcase">Tableau de bord</a></li>
    <li><a href="#features">Fonctionnalités</a></li>
    <li><a href="#flow">Comment ça marche</a></li>
  </ul>
  <div class="nav-r">
    <a href="{{ route('login') }}?tab=login" class="n-ghost">Se connecter</a>
    <a href="{{ route('login') }}?tab=register" class="n-solid">Accéder →</a>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-grid"></div>
  <div class="hero-g1"></div>
  <div class="hero-g2"></div>
  <div class="hero-g3"></div>

  <!-- Ring 1 — large, top right -->
  <div class="ring-wrap" style="top:8%;right:5%">
    <div class="ring ring-a" style="width:220px;height:220px;border:1px solid rgba(61,127,255,.35)">
      <div class="rdot rdot-blue"></div>
      <div class="ring ring-b" style="width:140px;height:140px;border:1px dashed rgba(0,207,255,.25);position:absolute;top:40px;left:40px">
        <div class="rdot rdot-cyan"></div>
      </div>
    </div>
  </div>

  <!-- Ring 2 — medium, bottom left -->
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

  <div class="h-badge"><div class="h-dot"></div>Plateforme de gestion des ressources</div>

  <h1 class="h-title">
    Gérez votre <span class="hl">eau &amp; <span class="hl-elec">électricité</span></span><br>par zone, en temps réel
  </h1>

  <p class="h-sub">
    De la saisie du relevé à la facture générée — automatiquement, pour chaque client, chaque mois.
  </p>

  <div class="h-cta">
    <a href="{{ route('login') }}" class="btn-hero">
      Accéder à la plateforme
      <div class="btn-hero-arr">→</div>
    </a>
    <a href="#showcase" class="btn-sec">Voir l'application</a>
  </div>

  <div class="h-stats">
    <div class="hst">
      <div class="hst-n">2 400+</div>
      <div class="hst-l">m³/kWh suivis par mois</div>
    </div>
    <div class="hst">
      <div class="hst-n c">18</div>
      <div class="hst-l">zones actives</div>
    </div>
    <div class="hst">
      <div class="hst-n w">99.8%</div>
      <div class="hst-l">disponibilité</div>
    </div>
    <div class="hst">
      <div class="hst-n">340+</div>
      <div class="hst-l">factures / mois</div>
    </div>
  </div>
</section>

<!-- SHOWCASE -->
<section class="showcase" id="showcase">
  <div class="sec-top reveal">
    <p class="eyebrow">L'application</p>
    <h2 class="sec-h">Un tableau de bord pensé pour la clarté</h2>
    <p class="sec-p">Relevés, contrats et factures de toutes vos zones — visibles en un coup d'œil.</p>
  </div>

  <div class="dash-mockup reveal">
    <div class="dm-bar">
      <div class="dm-dot" style="background:#ff5f57"></div>
      <div class="dm-dot" style="background:#febc2e"></div>
      <div class="dm-dot" style="background:#28c840"></div>
      <div class="dm-url">aquawatt.app / dashboard</div>
    </div>
    <div class="dm-body" style="display:grid;grid-template-columns:200px 1fr;min-height:420px">

      <!-- Sidebar -->
      <div class="dm-sidebar">
        <div class="dm-logo-sm">Aqua<span>Watt</span></div>
        <div class="dm-nav-item active">
          <svg class="ico" viewBox="0 0 14 14" fill="currentColor"><rect x="1" y="1" width="5" height="5" rx="1"/><rect x="8" y="1" width="5" height="5" rx="1"/><rect x="1" y="8" width="5" height="5" rx="1"/><rect x="8" y="8" width="5" height="5" rx="1"/></svg>
          Tableau de bord
        </div>
        <div class="dm-nav-item">
          <svg class="ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.3"><circle cx="7" cy="5" r="2.5"/><path d="M2 12c0-2.8 2.2-4 5-4s5 1.2 5 4"/></svg>
          Mon profil
        </div>
        <div class="dm-nav-item">
          <svg class="ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.3"><rect x="2" y="2" width="10" height="10" rx="1.5"/><path d="M5 5h4M5 7h4M5 9h2"/></svg>
          Mon contrat
        </div>
        <div class="dm-nav-item">
          <svg class="ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.3"><rect x="2" y="1" width="10" height="12" rx="1.5"/><path d="M5 5h4M5 7h2"/></svg>
          Mes factures
        </div>
        <div style="height:1px;background:var(--border);margin:6px 0"></div>
        <div style="font-size:9px;letter-spacing:.08em;color:var(--muted2);text-transform:uppercase;padding:4px 10px">Consommation</div>
        <div class="dm-nav-item">
          <svg class="ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M7 2C7 2 3 5.5 3 8.5a4 4 0 008 0C11 5.5 7 2 7 2z"/></svg>
          Eau
        </div>
        <div class="dm-nav-item">
          <svg class="ico" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.3"><path d="M8 1L4 8h4l-2 5 6-7H8L10 1z"/></svg>
          Électricité
        </div>
      </div>

      <!-- Main -->
      <div class="dm-main">
        <div style="display:flex;align-items:flex-start;justify-content:space-between">
          <div>
            <div class="dm-head">Tableau de bord</div>
            <div style="font-size:11px;color:var(--muted2)">Bienvenue, Dr. Carole Moore</div>
          </div>
          <div style="background:var(--s3);border:1px solid var(--border2);border-radius:7px;padding:4px 10px;font-size:11px;color:var(--muted);display:flex;align-items:center;gap:5px">
            2024
            <svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2 3.5l3 3 3-3"/></svg>
          </div>
        </div>

        <div style="display:flex;gap:6px">
          <div style="padding:5px 12px;border-radius:99px;font-size:11px;font-weight:500;background:rgba(61,127,255,.15);border:1px solid rgba(61,127,255,.4);color:var(--blue-hi)">💧 Eau</div>
          <div style="padding:5px 12px;border-radius:99px;font-size:11px;border:1px solid var(--border2);color:var(--muted)">⚡ Électricité</div>
          <div style="padding:5px 12px;border-radius:99px;font-size:11px;border:1px solid var(--border2);color:var(--muted)">✕ Les deux</div>
        </div>

        <div class="dm-metrics" style="grid-template-columns:repeat(3,1fr)">
          <div class="dm-card">
            <div class="dm-card-label">CONSO. EAU 2024</div>
            <div class="dm-card-val c">189 m³</div>
            <div class="dm-card-trend" style="color:var(--muted2)">Cumul annuel</div>
          </div>
          <div class="dm-card">
            <div class="dm-card-label">MOYENNE MENSUELLE</div>
            <div class="dm-card-val b">16 m³</div>
            <div class="dm-card-trend" style="color:var(--muted2)">Sur 12 mois</div>
          </div>
          <div class="dm-card">
            <div class="dm-card-label">DERNIER RELEVÉ</div>
            <div class="dm-card-val w" style="font-size:13px;padding-top:4px">Décembre 2024</div>
            <div class="dm-card-trend" style="color:var(--muted2)">Mois le plus récent</div>
          </div>
        </div>

        <div style="flex:1;background:var(--s3);border:1px solid var(--border);border-radius:10px;padding:.85rem .9rem;min-height:160px;display:flex;flex-direction:column">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
            <span style="font-size:11px;color:var(--muted2)">Consommation mensuelle — 2024</span>
            <div style="display:flex;align-items:center;gap:5px">
              <div style="width:7px;height:7px;border-radius:50%;background:#3d7fff"></div>
              <span style="font-size:10px;color:var(--muted2)">Eau (m³)</span>
            </div>
          </div>
          <div style="flex:1;position:relative;min-height:120px">
            <canvas id="showcaseChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="feats" id="features">
  <div class="sec-top reveal">
    <p class="eyebrow">Fonctionnalités</p>
    <h2 class="sec-h">Tout ce dont vous avez besoin</h2>
    <p class="sec-p">De la saisie à la facturation, chaque étape simplifiée.</p>
  </div>
  <div class="feats-grid">
    <div class="fc wide reveal">
      <div class="fc-icon">⚡</div>
      <div class="fc-title">Génération automatique de factures</div>
      <p class="fc-desc">Choisissez un mois — AquaWatt retrouve le relevé correspondant, calcule la consommation et génère la facture avec le nom du client, le numéro de contrat et le montant dû. En un clic.</p>
    </div>
    <div class="fc reveal">
      <div class="fc-icon">🗺️</div>
      <div class="fc-title">Gestion par zone</div>
      <p class="fc-desc">Groupez vos clients par zone. Visualisez les totaux eau et électricité par zone et par mois.</p>
    </div>
    <div class="fc reveal">
      <div class="fc-icon">💧</div>
      <div class="fc-title">Relevés eau &amp; électricité</div>
      <p class="fc-desc">Saisie séparée des index départ et fin. Calcul automatique de la consommation réelle.</p>
    </div>
    <div class="fc reveal">
      <div class="fc-icon">📊</div>
      <div class="fc-title">Tableau de bord temps réel</div>
      <p class="fc-desc">Métriques clés, factures en attente, consommations — tout visible d'un coup d'œil.</p>
    </div>
    <div class="fc reveal">
      <div class="fc-icon">📄</div>
      <div class="fc-title">Historique complet</div>
      <p class="fc-desc">Chaque relevé, chaque facture, chaque contrat — archivés et consultables à tout moment.</p>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="flow" id="flow">
  <div class="sec-top reveal">
    <p class="eyebrow">Comment ça marche</p>
    <h2 class="sec-h">De la lecture au paiement</h2>
    <p class="sec-p">4 étapes, entièrement gérées dans AquaWatt.</p>
  </div>
  <div class="flow-steps reveal">
    <div class="fstep">
      <div class="fstep-num">1</div>
      <div class="fstep-title">Relevé saisi</div>
      <p class="fstep-desc">Le technicien saisit l'index de départ et l'index final pour le mois.</p>
    </div>
    <div class="fstep">
      <div class="fstep-num">2</div>
      <div class="fstep-title">Calcul auto</div>
      <p class="fstep-desc">AquaWatt calcule la consommation réelle et la lie au contrat client.</p>
    </div>
    <div class="fstep">
      <div class="fstep-num">3</div>
      <div class="fstep-title">Facture générée</div>
      <p class="fstep-desc">En sélectionnant le mois, la facture est créée automatiquement avec tous les détails.</p>
    </div>
    <div class="fstep">
      <div class="fstep-num">4</div>
      <div class="fstep-title">Client notifié</div>
      <p class="fstep-desc">Le client consulte sa facture depuis son espace personnel.</p>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<div style="padding:0 2rem 6rem">
  <div class="cta-band reveal">
    <h2 class="cta-band-title">Prêt à gérer vos ressources<br>intelligemment ?</h2>
    <p class="cta-band-sub">Connectez-vous et prenez le contrôle de votre eau et électricité dès maintenant.</p>
    <a href="{{ route('login') }}?tab=register" class="btn-hero">
      Accéder à AquaWatt
      <div class="btn-hero-arr">→</div>
    </a>
  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="foot-logo">Aqua<span>Watt</span></div>
  <div class="foot-links">
    <a href="#">À propos</a>
    <a href="#">Contact</a>
    <a href="#">Confidentialité</a>
  </div>
  <div class="foot-copy">© 2026 AquaWatt</div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
  /* ── Bidirectional scroll reveal ── */
  const obs = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('on');
      } else {
        entry.target.classList.remove('on');
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal').forEach(function(el) {
    obs.observe(el);
  });

  /* ── Showcase chart ── */
  (function() {
    var canvas = document.getElementById('showcaseChart');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var grad = ctx.createLinearGradient(0, 0, 0, 140);
    grad.addColorStop(0, 'rgba(61,127,255,0.22)');
    grad.addColorStop(1, 'rgba(61,127,255,0)');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
        datasets: [{
          data: [19,15,20,14,20,15,14,12,17,13,10,19],
          borderColor: '#3d7fff',
          borderWidth: 2,
          backgroundColor: grad,
          pointBackgroundColor: '#3d7fff',
          pointRadius: 3,
          pointHoverRadius: 5,
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgba(9,12,26,0.95)',
            borderColor: 'rgba(61,127,255,.25)',
            borderWidth: 1,
            titleColor: 'rgba(200,210,255,.5)',
            bodyColor: '#6699ff',
            bodyFont: { size: 12, weight: '600' },
            callbacks: { label: function(c) { return c.parsed.y + ' m³'; } }
          }
        },
        scales: {
          x: { grid: { color: 'rgba(61,127,255,.06)' }, ticks: { color: 'rgba(200,210,255,.3)', font: { size: 10 } } },
          y: { grid: { color: 'rgba(61,127,255,.06)' }, ticks: { color: 'rgba(200,210,255,.3)', font: { size: 10 } }, min: 8, max: 22 }
        }
      }
    });
  })();
</script>
</body>
</html>