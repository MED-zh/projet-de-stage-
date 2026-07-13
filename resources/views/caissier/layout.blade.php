<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<link rel="icon" href="{{ asset('image.png') }}">
<title>AquaWatt Caissier — @yield('title', 'Dashboard')</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#04060f;--s1:#090c1a;--s2:#0d1122;--s3:#111628;
  --blue:#3d7fff;--blue-hi:#6699ff;--blue-lo:rgba(61,127,255,0.13);--blue-lo2:rgba(61,127,255,0.06);
  --cyan:#00cfff;--cyan-lo:rgba(0,207,255,0.1);
  --white:#ffffff;--text:#e8eeff;--muted:rgba(200,210,255,0.48);--muted2:rgba(200,210,255,0.2);
  --border:rgba(61,127,255,0.1);--border2:rgba(61,127,255,0.22);
  --red:#f87171;--red-lo:rgba(248,113,113,0.12);
  --green:#4ade80;--green-lo:rgba(74,222,128,0.12);
  --amber:#fbbf24;--amber-lo:rgba(251,191,36,0.12);
  --display:'Syne',sans-serif;--body:'DM Sans',sans-serif;--r:12px;
  --sidebar-w:220px;
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:var(--body);-webkit-font-smoothing:antialiased;overflow:hidden}

/* SHELL */
.shell{display:grid;grid-template-columns:var(--sidebar-w) 1fr;grid-template-rows:56px 1fr;height:100vh;transition:grid-template-columns 0.28s cubic-bezier(.4,0,.2,1)}
.shell.collapsed{grid-template-columns:56px 1fr}

/* TOPBAR */
.topbar{grid-column:1/-1;display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;border-bottom:1px solid var(--border);background:rgba(4,6,15,0.85);backdrop-filter:blur(20px);z-index:10}
.logo{display:flex;align-items:center;gap:8px;text-decoration:none}
.logo-gem{width:28px;height:28px;border-radius:7px;background:linear-gradient(140deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-gem svg{width:13px;height:13px;fill:white}
.logo-txt{font-family:var(--display);font-size:16px;font-weight:800;color:var(--white);letter-spacing:-.3px;white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}
.logo-txt em{font-style:normal;color:var(--blue-hi)}
.shell.collapsed .logo-txt{max-width:0;opacity:0}
.topbar-right{display:flex;align-items:center;gap:12px}
.role-badge{font-size:10px;font-weight:600;padding:3px 8px;border-radius:99px;background:rgba(251,191,36,0.15);color:var(--amber);border:1px solid rgba(251,191,36,0.3);letter-spacing:.05em;white-space:nowrap}
.avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--amber),#f97316);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff}
.user-name{font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}

/* SIDEBAR */
.sidebar{
  border-right:1px solid var(--border);
  background:var(--s1);
  padding:1.25rem 0.75rem;
  display:flex;
  flex-direction:column;
  gap:0;
  overflow:visible;
  position:relative;
  z-index:2;
  transition:padding 0.28s cubic-bezier(.4,0,.2,1);
}
.shell.collapsed .sidebar{padding:1.25rem 0.5rem}

/* TOGGLE EDGE BUTTON */
.toggle-btn-edge{
  position:absolute;
  right:-12px;
  top:55px;
  transform:none;
  width:24px;
  height:24px;
  border-radius:50%;
  background:var(--s1);
  border:1px solid var(--border2);
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  color:var(--muted);
  z-index:20;
  transition:background .2s, color .2s;
}
.toggle-btn-edge:hover{background:var(--blue-lo);color:var(--text)}
.toggle-btn-edge svg{transition:transform 0.28s cubic-bezier(.4,0,.2,1)}
.shell.collapsed .toggle-btn-edge svg{transform:rotate(180deg)}

/* SIDEBAR NAV */
.sidebar-nav{flex:1;overflow-y:auto;display:flex;flex-direction:column;gap:4px;padding-bottom:8px}
.sidebar-nav::-webkit-scrollbar{width:3px}
.sidebar-nav::-webkit-scrollbar-track{background:transparent}
.sidebar-nav::-webkit-scrollbar-thumb{background:var(--border2);border-radius:99px}
.sidebar-footer{flex-shrink:0;padding-top:12px;border-top:1px solid var(--border)}

/* NAV ITEMS */
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:13px;color:var(--muted);cursor:pointer;transition:all .2s;border:none;background:none;width:100%;text-align:left;text-decoration:none;white-space:nowrap;overflow:hidden}
.nav-item:hover{background:var(--blue-lo);color:var(--text)}
.nav-item.active{background:var(--blue-lo);color:var(--blue-hi);font-weight:500}
.nav-item svg{width:15px;height:15px;flex-shrink:0;opacity:.7}
.nav-item.active svg{opacity:1}
.shell.collapsed .nav-item{justify-content:center;padding:9px;gap:0}

/* Labels */
.nav-label{transition:opacity 0.18s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:160px;overflow:hidden}
.shell.collapsed .nav-label{max-width:0;opacity:0}

.nav-section{font-size:10px;letter-spacing:.1em;text-transform:uppercase;color:var(--muted2);padding:12px 12px 4px;margin-top:8px;white-space:nowrap;overflow:hidden;transition:opacity 0.18s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}
.shell.collapsed .nav-section{max-width:0;opacity:0;padding:12px 0 4px}

.nav-divider{height:1px;background:var(--border);margin:8px 0;transition:opacity 0.18s}
.shell.collapsed .nav-divider{opacity:0}

/* Tooltips */
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
.main{overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;position:relative;z-index:1;isolation:isolate}

/* PAGE HEADER */
.page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.page-title{font-family:var(--display);font-size:20px;font-weight:700;color:var(--white);letter-spacing:-.5px}
.page-sub{font-size:13px;color:var(--muted);margin-top:2px}

/* METRICS */
.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.mc{border:1px solid var(--border);border-radius:var(--r);padding:1rem;background:var(--s2);position:relative;overflow:hidden;transition:border-color .2s}
.mc:hover{border-color:var(--border2)}
.mc::before{content:'';position:absolute;top:-30px;right:-20px;width:80px;height:80px;border-radius:50%;filter:blur(30px);pointer-events:none}
.mc.blue::before{background:rgba(61,127,255,.3)}.mc.cyan::before{background:rgba(0,207,255,.25)}.mc.green::before{background:rgba(74,222,128,.2)}.mc.amber::before{background:rgba(251,191,36,.2)}.mc.red::before{background:rgba(248,113,113,.2)}
.mc-label{font-size:10px;color:var(--muted2);letter-spacing:.06em;text-transform:uppercase;margin-bottom:8px}
.mc-val{font-family:var(--display);font-size:22px;font-weight:700;letter-spacing:-.5px}
.mc-val.blue{color:var(--blue-hi)}.mc-val.cyan{color:var(--cyan)}.mc-val.green{color:#4ade80}.mc-val.amber{color:#fbbf24}.mc-val.red{color:#f87171}
.mc-trend{font-size:11px;color:var(--muted2);margin-top:5px}

/* TABLE */
.tbl-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);overflow:hidden}
.tbl-head{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:10px}
table{width:100%;border-collapse:collapse;font-size:12px}
thead th{padding:10px 1.25rem;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);border-bottom:1px solid var(--border);font-weight:500;background:var(--s3)}
tbody tr{border-bottom:1px solid rgba(61,127,255,0.05);transition:background .15s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--blue-lo2)}
tbody td{padding:10px 1.25rem;color:var(--text)}

/* BADGES */
.badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:600}
.badge.green{background:var(--green-lo);color:var(--green)}
.badge.red{background:var(--red-lo);color:var(--red)}
.badge.blue{background:var(--blue-lo);color:var(--blue-hi)}
.badge.amber{background:var(--amber-lo);color:var(--amber)}
.badge-dot{width:4px;height:4px;border-radius:50%;background:currentColor}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:500;cursor:pointer;transition:all .2s;border:none;font-family:var(--body);text-decoration:none}
.btn-primary{background:var(--blue);color:#fff}
.btn-primary:hover{background:#2d6fee}
.btn-ghost{background:transparent;color:var(--muted);border:1px solid var(--border2)}
.btn-ghost:hover{background:var(--blue-lo);color:var(--text)}
.btn-success{background:var(--green-lo);color:var(--green);border:1px solid rgba(74,222,128,0.2)}
.btn-success:hover{background:rgba(74,222,128,0.2)}
.btn-sm{padding:4px 10px;font-size:11px}

/* FORM */
.form-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.5rem;max-width:540px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
label{font-size:11px;color:var(--muted2);text-transform:uppercase;letter-spacing:.06em}
input,select{background:var(--s3);border:1px solid var(--border2);border-radius:8px;padding:9px 12px;font-size:13px;color:var(--text);font-family:var(--body);width:100%;outline:none;transition:border-color .2s}
input:focus,select:focus{border-color:var(--blue)}
select option{background:var(--s3)}
.alert-success{background:var(--green-lo);border:1px solid rgba(74,222,128,0.2);color:var(--green);padding:10px 14px;border-radius:8px;font-size:12px;margin-bottom:1rem}
.alert-error{background:var(--red-lo);border:1px solid rgba(248,113,113,0.2);color:var(--red);padding:10px 14px;border-radius:8px;font-size:12px;margin-bottom:1rem}
.error-msg{font-size:11px;color:#f87171;margin-top:5px;display:flex;align-items:center;gap:4px}
</style>
</head>
<body>

@if(session('logged_out'))
<script>
  history.pushState(null, null, window.location.href);
  window.addEventListener('popstate', function () {
    history.pushState(null, null, window.location.href);
  });
</script>
@endif

<div class="shell" id="shell">

  <header class="topbar">
    <a href="{{ route('caissier.dashboard') }}" class="logo">
      <div class="logo-gem"><svg viewBox="0 0 16 16"><path d="M8 1C8 1 3 6.5 3 10.5a5 5 0 0010 0C13 6.5 8 1 8 1z"/></svg></div>
      <span class="logo-txt">Aqua<em>Watt</em></span>
    </a>
    <div class="topbar-right">
      <span class="role-badge">CAISSIER</span>
      <span class="user-name">{{ Auth::user()->name }}</span>
      <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
    </div>
  </header>

  <aside class="sidebar">

    <!-- Toggle edge button -->
    <button class="toggle-btn-edge" onclick="toggleSidebar()" title="Réduire le menu">
      <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M10 3L5 8l5 5"/>
      </svg>
    </button>

    <div class="sidebar-nav">

      <a href="{{ route('caissier.dashboard') }}" class="nav-item {{ request()->routeIs('caissier.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor" style="flex-shrink:0"><rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/><rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/></svg>
        <span class="nav-label">Dashboard</span>
      </a>

      <div class="nav-divider"></div>
      <div class="nav-section">Paiements</div>

      <a href="{{ route('caissier.factures.index') }}" class="nav-item {{ request()->routeIs('caissier.factures.*') ? 'active' : '' }}" data-tooltip="Factures à encaisser">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><rect x="2" y="2" width="12" height="12" rx="1.5"/><path d="M5 6h6M5 8.5h6M5 11h4"/></svg>
        <span class="nav-label">Factures à encaisser</span>
      </a>

      <a href="{{ route('caissier.paiements.index') }}" class="nav-item {{ request()->routeIs('caissier.paiements.*') ? 'active' : '' }}" data-tooltip="Historique paiements">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><path d="M2 5h12v8a1 1 0 01-1 1H3a1 1 0 01-1-1V5zM2 5l1-2h10l1 2"/><path d="M6 9h4"/></svg>
        <span class="nav-label">Historique paiements</span>
      </a>

    </div>

    <div class="sidebar-footer">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="nav-item" style="color:#f87171" data-tooltip="Déconnexion" >
          <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><path d="M10 3h3v10h-3M7 11l4-4-4-4M11 7H2"/></svg>
          <span class="nav-label">Déconnexion</span>
        </button>
      </form>
    </div>

  </aside>

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
    );}
 
</script>

@stack('scripts')
</body>
</html>