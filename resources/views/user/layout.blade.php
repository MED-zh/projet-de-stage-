<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
<link rel="icon" href="{{ asset('image.png') }}">
<title>AquaWatt — @yield('title', 'Dashboard')</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#04060f;--s1:#090c1a;--s2:#0d1122;--s3:#111628;
  --blue:#3d7fff;--blue-hi:#6699ff;--blue-lo:rgba(61,127,255,0.13);--blue-lo2:rgba(61,127,255,0.06);
  --cyan:#00cfff;--cyan-lo:rgba(0,207,255,0.1);
  --white:#ffffff;--text:#e8eeff;--muted:rgba(200,210,255,0.48);--muted2:rgba(200,210,255,0.2);
  --border:rgba(61,127,255,0.1);--border2:rgba(61,127,255,0.22);
  --display:'Syne',sans-serif;--body:'DM Sans',sans-serif;--r:12px;
  --sidebar-w:220px;
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:var(--body);-webkit-font-smoothing:antialiased;overflow:hidden}

/* SHELL */
.shell{display:grid;grid-template-columns:var(--sidebar-w) 1fr;grid-template-rows:56px 1fr;height:100vh;transition:grid-template-columns 0.28s cubic-bezier(.4,0,.2,1)}
.shell.collapsed{grid-template-columns:56px 1fr}

/* TOP BAR */
.topbar{grid-column:1/-1;display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;border-bottom:1px solid var(--border);background:rgba(4,6,15,0.85);backdrop-filter:blur(20px);z-index:10}
.logo{display:flex;align-items:center;gap:8px;text-decoration:none}
.logo-gem{width:28px;height:28px;border-radius:7px;background:linear-gradient(140deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-gem svg{width:13px;height:13px;fill:white}
.logo-txt{font-family:var(--display);font-size:16px;font-weight:800;color:var(--white);letter-spacing:-.3px;white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}
.logo-txt em{font-style:normal;color:var(--blue-hi)}
.shell.collapsed .logo-txt{max-width:0;opacity:0}
.topbar-right{display:flex;align-items:center;gap:12px}
.avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;cursor:pointer}
.user-name{font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}

/* SIDEBAR */
.sidebar{
  border-right:1px solid var(--border);
  background:var(--s1);
  padding:1.25rem 0.75rem;
  display:flex;
  flex-direction:column;
  gap:4px;
  overflow:visible; /* ✅ changed from overflow:hidden */
  transition:padding 0.28s cubic-bezier(.4,0,.2,1);
  position:relative;
}

.toggle-btn-edge {
  position: absolute;
  right: -12px;
  top:170px;
  transform:none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: var(--s1);
  border: 1px solid var(--border2);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--muted);
  z-index: 20;
  transition: background .2s, color .2s;
}
.toggle-btn-edge:hover { background: var(--blue-lo); color: var(--text); }
.toggle-btn-edge svg { transition: transform 0.28s cubic-bezier(.4,0,.2,1); }
.shell.collapsed .toggle-btn-edge svg { transform: rotate(180deg); }

.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:13px;color:var(--muted);cursor:pointer;transition:all .2s;border:none;background:none;width:100%;text-align:left;text-decoration:none;white-space:nowrap;overflow:hidden}
.nav-item:hover{background:var(--blue-lo);color:var(--text)}
.nav-item.active{background:var(--blue-lo);color:var(--blue-hi);font-weight:500}
.nav-item svg{width:15px;height:15px;flex-shrink:0;opacity:.7}
.nav-item.active svg{opacity:1}
.shell.collapsed .nav-item{justify-content:center;padding:9px;gap:0}
.shell.collapsed .nav-item svg{opacity:.7}

/* Text labels that slide out */
.nav-label{transition:opacity 0.18s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:160px;overflow:hidden}
.shell.collapsed .nav-label{max-width:0;opacity:0}

.nav-section{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted2);padding:12px 12px 4px;margin-top:8px;white-space:nowrap;overflow:hidden;transition:opacity 0.18s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}
.shell.collapsed .nav-section{max-width:0;opacity:0;padding:12px 0 4px}

.nav-divider{height:1px;background:var(--border);margin:8px 0;transition:opacity 0.18s}
.shell.collapsed .nav-divider{opacity:0}

.sidebar-footer{margin-top:auto;padding-top:12px}

/* Tooltip on collapsed sidebar */
.shell.collapsed .nav-item{position:relative}
.shell.collapsed .nav-item:hover::after{
  content:attr(data-tooltip);
  position:absolute;
  left:calc(100% + 10px);
  top:50%;
  transform:translateY(-50%);
  background:var(--s3);
  border:1px solid var(--border2);
  color:var(--text);
  font-size:12px;
  padding:5px 10px;
  border-radius:8px;
  white-space:nowrap;
  z-index:100;
  pointer-events:none;
}

/* MAIN */
.main{overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem}

/* METRIC CARDS */
.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.mc{border:1px solid var(--border);border-radius:var(--r);padding:1rem;background:var(--s2);position:relative;overflow:hidden;transition:border-color .2s}
.mc:hover{border-color:var(--border2)}
.mc::before{content:'';position:absolute;top:-30px;right:-20px;width:80px;height:80px;border-radius:50%;filter:blur(30px);pointer-events:none}
.mc.blue::before{background:rgba(61,127,255,.3)}.mc.cyan::before{background:rgba(0,207,255,.25)}.mc.green::before{background:rgba(74,222,128,.2)}.mc.amber::before{background:rgba(251,191,36,.2)}
.mc-label{font-size:10px;color:var(--muted2);letter-spacing:.06em;text-transform:uppercase;margin-bottom:8px}
.mc-val{font-family:var(--display);font-size:22px;font-weight:700;letter-spacing:-.5px}
.mc-val.blue{color:var(--blue-hi)}.mc-val.cyan{color:var(--cyan)}.mc-val.green{color:#4ade80}.mc-val.amber{color:#fbbf24}
.mc-trend{font-size:11px;color:var(--muted2);margin-top:5px}

/* CHART CARD */
.chart-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem}
.cc-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.cc-title{font-family:var(--display);font-size:14px;font-weight:700;color:var(--white)}
.cc-legend{display:flex;gap:16px}
.cc-leg-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--muted)}
.cc-dot{width:8px;height:8px;border-radius:2px}
.chart-wrap{position:relative;height:220px}

/* YEAR + TYPE SELECTORS */
.year-selector{display:flex;gap:6px}
.year-btn{padding:6px 14px;border-radius:8px;font-size:12px;font-weight:500;cursor:pointer;transition:all .2s;border:1px solid var(--border2);background:transparent;color:var(--muted);font-family:var(--body)}
.year-btn:hover{background:var(--blue-lo);color:var(--text)}
.year-btn.active{background:var(--blue);border-color:var(--blue);color:white;box-shadow:0 0 16px rgba(61,127,255,.35)}
.type-tabs{display:flex;gap:8px}
.type-tab{padding:5px 14px;border-radius:99px;font-size:12px;cursor:pointer;border:1px solid var(--border);background:transparent;color:var(--muted);font-family:var(--body);transition:all .2s}
.type-tab.active{background:var(--blue-lo);border-color:var(--blue);color:var(--blue-hi)}

/* PAGE HEADER */
.page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.page-title{font-family:var(--display);font-size:20px;font-weight:700;color:var(--white);letter-spacing:-.5px}
.page-sub{font-size:13px;color:var(--muted);margin-top:2px}

/* INFO / CONTRACT CARDS */
.info-card,.contrat-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem}
.ic-head{display:flex;align-items:center;gap:12px;margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border)}
.ic-avatar{width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0}
.ic-name{font-family:var(--display);font-size:15px;font-weight:700;color:var(--white)}
.ic-role{font-size:11px;color:var(--muted2);margin-top:2px}
.ic-row,.ct-row{display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid rgba(61,127,255,0.05);font-size:12px}
.ic-row:last-child,.ct-row:last-child{border-bottom:none}
.ic-key,.ct-key{color:var(--muted2)}.ic-val,.ct-val{color:var(--text);font-weight:500}
.ct-badge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:600;background:rgba(74,222,128,.12);color:#4ade80;margin-bottom:.75rem}
.ct-badge-dot{width:5px;height:5px;border-radius:50%;background:#4ade80;box-shadow:0 0 5px #4ade80}
.ct-num{font-family:var(--display);font-size:17px;font-weight:700;color:var(--white);margin-bottom:.25rem}
.ct-sub{font-size:11px;color:var(--muted2);margin-bottom:1rem}
.dm-badge{display:inline-block;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:500}
.dm-badge.eau{background:rgba(0,207,255,.12);color:var(--cyan)}
.dm-badge.elec{background:rgba(61,127,255,.15);color:var(--blue-hi)}

/* Toggle button animation */
.toggle-btn{background:transparent;border:1px solid var(--border2);border-radius:8px;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);flex-shrink:0;transition:background .2s, color .2s}
.toggle-btn:hover{background:var(--blue-lo);color:var(--text)}
.toggle-btn svg{transition:transform 0.28s cubic-bezier(.4,0,.2,1)}
.shell.collapsed .toggle-btn svg{transform:rotate(180deg)}
</style>
</head>
<body>
<div class="shell" id="shell">

  <!-- TOP BAR -->
  <header class="topbar">
    <a href="{{ route('user') }}" class="logo">
      <div class="logo-gem"><svg viewBox="0 0 16 16"><path d="M8 1C8 1 3 6.5 3 10.5a5 5 0 0010 0C13 6.5 8 1 8 1z"/></svg></div>
      <span class="logo-txt">Aqua<em>Watt</em></span>
    </a>
    <div class="topbar-right">
      <span class="user-name">{{ Auth::user()->name }}</span>
      <a href="{{ route('user.profil') }}" style="text-decoration:none">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
      </a>
    </div>
  </header>

  <!-- SIDEBAR -->
  <aside class="sidebar"> 
  


    <a href="{{ route('user') }}" class="nav-item {{ request()->routeIs('user') ? 'active' : '' }}" data-tooltip="Tableau de bord">
      <svg viewBox="0 0 16 16" fill="currentColor" style="flex-shrink:0"><rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/><rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/></svg>
      <span class="nav-label">Tableau de bord</span>
    </a>

    <a href="{{ route('user.profil') }}" class="nav-item {{ request()->routeIs('user.profil') ? 'active' : '' }}" data-tooltip="Mon profil">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><circle cx="8" cy="5.5" r="3"/><path d="M2 14c0-3.3 2.7-5 6-5s6 1.7 6 5"/></svg>
      <span class="nav-label">Mon profil</span>
    </a>

    <a href="{{ route('user.contrat') }}" class="nav-item {{ request()->routeIs('user.contrat') ? 'active' : '' }}" data-tooltip="Mon contrat">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><rect x="2" y="2" width="12" height="12" rx="1.5"/><path d="M5 6h6M5 8.5h6M5 11h4"/></svg>
      <span class="nav-label">Mon contrat</span>
    </a>

    <a href="{{ route('factures.index') }}" class="nav-item {{ request()->routeIs('user.factures') ? 'active' : '' }}" data-tooltip="Mes factures">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0">
        <path d="M4 2h8a1 1 0 011 1v11a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
        <path d="M6 6h4M6 9h4M6 12h2"/>
      </svg>
      <span class="nav-label">Mes factures</span>
    </a>

   <button class="toggle-btn-edge" onclick="toggleSidebar()" title="Réduire le menu">
    <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M10 3L5 8l5 5"/>
    </svg>
  </button>

    {{-- ✅ stray </div> removed here --}}

    <div class="nav-divider"></div>
    <div class="nav-section">Consommation</div>

    <a href="{{ route('user', ['type' => 'eau']) }}" class="nav-item" data-tooltip="Eau">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><path d="M8 2C8 2 3 7.5 3 11a5 5 0 0010 0C13 7.5 8 2 8 2z"/></svg>
      <span class="nav-label">Eau</span>
    </a>

    <a href="{{ route('user', ['type' => 'elec']) }}" class="nav-item" data-tooltip="Électricité">
      <svg viewBox="0 0 16 16" fill="currentColor" style="flex-shrink:0"><path d="M9 2L4 9h5l-2 5 7-8H9z"/></svg>
      <span class="nav-label">Électricité</span>
    </a>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-item" style="color:#f87171" data-tooltip="Déconnexion">
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><path d="M10 3h3v10h-3M7 11l4-4-4-4M11 7H2"/></svg>
          <span class="nav-label">Déconnexion</span>
        </button>
      </form>
    </div>

</aside>
  <!-- PAGE CONTENT -->
  <main class="main">
    @yield('content')
  </main>

</div>

<script>
  const shell = document.getElementById('shell');

if (localStorage.getItem('sidebar') === 'collapsed') {
  shell.classList.add('collapsed');
}

  function toggleSidebar() {
    shell.classList.toggle('collapsed');
    localStorage.setItem('sidebar',
      shell.classList.contains('collapsed') ? 'collapsed' : 'expanded'
    );
  }
</script>

@stack('scripts')
</body>
</html>