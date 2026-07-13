<!DOCTYPE html> 
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<link rel="icon" href="{{ asset('image.png') }}">
<title>AquaWatt Admin — @yield('title', 'Dashboard')</title>
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
  
  --current-sidebar-w: var(--sidebar-w);
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:var(--body);-webkit-font-smoothing:antialiased;overflow:hidden}

/* SHELL - Dynamic grid template changes based on variable */
.shell{
  display:grid;
  grid-template-columns:var(--current-sidebar-w) 1fr;
  grid-template-rows:56px 1fr;
  height:100vh;
  transition:grid-template-columns 0.28s cubic-bezier(.4,0,.2,1);
}
.shell.collapsed {
  --current-sidebar-w: 56px;
}

/* TOPBAR */
.topbar{
  grid-column:1/-1;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 1.5rem;
  border-bottom:1px solid var(--border);
  background:rgba(4,6,15,0.85);
  backdrop-filter:blur(20px);
  z-index:110;
}
.logo{display:flex;align-items:center;gap:8px;text-decoration:none}
.logo-gem{width:28px;height:28px;border-radius:7px;background:linear-gradient(140deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;flex-shrink:0}
.logo-gem svg{width:13px;height:13px;fill:white}
.logo-txt{font-family:var(--display);font-size:16px;font-weight:800;color:var(--white);letter-spacing:-.3px;white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}
.logo-txt em{font-style:normal;color:var(--blue-hi)}
.shell.collapsed .logo-txt{max-width:0;opacity:0}
.topbar-right{display:flex;align-items:center;gap:12px}
.admin-badge{font-size:10px;font-weight:600;padding:3px 8px;border-radius:99px;background:rgba(61,127,255,0.15);color:var(--blue-hi);border:1px solid var(--border2);letter-spacing:.05em;white-space:nowrap}
.avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--blue),var(--cyan));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff}
.user-name{font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}

/* SIDEBAR - Changed to relative positioning to follow the grid rules flawlessly */
.sidebar{
  grid-column:1;
  grid-row:2;
  border-right:1px solid var(--border);
  background:var(--s1);
  padding:1.25rem 0.75rem;
  display:flex;
  flex-direction:column;
  gap:0;
  overflow:visible;
  position: relative;
  height: 100%;
  width: var(--current-sidebar-w);
  z-index: 100;
  transition: width 0.28s cubic-bezier(.4,0,.2,1), padding 0.28s cubic-bezier(.4,0,.2,1);
}
.shell.collapsed .sidebar{
  padding:1.25rem 0.5rem;
}

/* TOGGLE EDGE BUTTON */
.toggle-btn-edge{
  position:absolute;
  right:-12px;
  top:96px;
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
  z-index:120;
  transition:background .2s, color .2s;
}
.toggle-btn-edge:hover{background:var(--blue-lo);color:var(--text)}
.toggle-btn-edge svg{transition:transform 0.28s cubic-bezier(.4,0,.2,1)}
.shell.collapsed .toggle-btn-edge svg{transform:rotate(180deg)}

/* SIDEBAR NAV */
.sidebar-nav{
  flex:1;
  overflow-y:auto;
  display:flex;
  flex-direction:column;
  gap:4px;
  padding-bottom:8px;
}
.sidebar-nav::-webkit-scrollbar{width:3px}
.sidebar-nav::-webkit-scrollbar-track{background:transparent}
.sidebar-nav::-webkit-scrollbar-thumb{background:var(--border2);border-radius:99px}
.sidebar-footer{
  flex-shrink:0;
  padding-top:12px;
  border-top:1px solid var(--border);
}

/* NAV ITEMS */
.nav-item{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:10px;font-size:13px;color:var(--muted);cursor:pointer;transition:all .2s;border:none;background:none;width:100%;text-align:left;text-decoration:none;white-space:nowrap;overflow:hidden}
.nav-item:hover{background:var(--blue-lo);color:var(--text)}
.nav-item.active{background:var(--blue-lo);color:var(--blue-hi);font-weight:500}
.nav-item svg{width:15px;height:15px;flex-shrink:0;opacity:.7}
.nav-item.active svg{opacity:1}
.shell.collapsed .nav-item{justify-content:center;padding:9px 0;gap:0}

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
.main {
  grid-column:2;
  grid-row:2;
  overflow-y:auto;
  overflow-x:hidden;
  padding:1.5rem;
  display:flex;
  flex-direction:column;
  gap:1.25rem;
  position:relative;
  z-index:1;
}

.charts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 12px;
  width: 100%;
}

.page-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.page-title{font-family:var(--display);font-size:20px;font-weight:700;color:var(--white);letter-spacing:-.5px}
.page-sub{font-size:13px;color:var(--muted);margin-top:2px}
.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
.mc{border:1px solid var(--border);border-radius:var(--r);padding:1rem;background:var(--s2);position:relative;overflow:hidden;transition:border-color .2s}
.mc:hover{border-color:var(--border2)}
.mc::before{content:'';position:absolute;top:-30px;right:-20px;width:80px;height:80px;border-radius:50%;filter:blur(30px);pointer-events:none}
.mc.blue::before{background:rgba(61,127,255,.3)}.mc.cyan::before{background:rgba(0,207,255,.25)}.mc.green::before{background:rgba(74,222,128,.2)}.mc.amber::before{background:rgba(251,191,36,.2)}.mc.red::before{background:rgba(248,113,113,.2)}
.mc-label{font-size:10px;color:var(--muted2);letter-spacing:.06em;text-transform:uppercase;margin-bottom:8px}
.mc-val{font-family:var(--display);font-size:22px;font-weight:700;letter-spacing:-.5px}
.mc-val.blue{color:var(--blue-hi)}.mc-val.cyan{color:var(--cyan)}.mc-val.green{color:#4ade80}.mc-val.amber{color:#fbbf24}.mc-val.red{color:#f87171}
.mc-trend{font-size:11px;color:var(--muted2);margin-top:5px}
.chart-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.25rem;min-width: 0;}
.cc-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem}
.cc-title{font-family:var(--display);font-size:14px;font-weight:700;color:var(--white)}
.cc-legend{display:flex;gap:16px}
.cc-leg-item{display:flex;align-items:center;gap:5px;font-size:11px;color:var(--muted)}
.cc-dot{width:8px;height:8px;border-radius:2px}
.chart-wrap{position:relative;height:220px;width:100%;}
.tbl-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);overflow:hidden}
.tbl-head{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid var(--border)}
table{width:100%;border-collapse:collapse;font-size:12px}
thead th{padding:10px 1.25rem;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted2);border-bottom:1px solid var(--border);font-weight:500}
tbody tr{border-bottom:1px solid rgba(61,127,255,0.05);transition:background .15s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--blue-lo2)}
tbody td{padding:10px 1.25rem;color:var(--text)}
.badge{display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:99px;font-size:10px;font-weight:600}
.badge.green{background:var(--green-lo);color:var(--green)}
.badge.red{background:var(--red-lo);color:var(--red)}
.badge.blue{background:var(--blue-lo);color:var(--blue-hi)}
.badge.amber{background:var(--amber-lo);color:var(--amber)}
.badge-dot{width:4px;height:4px;border-radius:50%;background:currentColor}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:8px;font-size:12px;font-weight:500;cursor:pointer;transition:all .2s;border:none;font-family:var(--body);text-decoration:none}
.btn-primary{background:var(--blue);color:#fff}
.btn-primary:hover{background:#2d6fee}
.btn-ghost{background:transparent;color:var(--muted);border:1px solid var(--border2)}
.btn-ghost:hover{background:var(--blue-lo);color:var(--text)}
.btn-danger{background:var(--red-lo);color:var(--red);border:1px solid rgba(248,113,113,0.2)}
.btn-danger:hover{background:rgba(248,113,113,0.2)}
.btn-sm{padding:4px 10px;font-size:11px}
.form-card{border:1px solid var(--border);border-radius:var(--r);background:var(--s2);padding:1.5rem;max-width:640px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{display:flex;flex-direction:column;gap:6px}
.form-group.full{grid-column:1/-1}
label{font-size:11px;color:var(--muted2);text-transform:uppercase;letter-spacing:.06em}
input,select{background:var(--s3);border:1px solid var(--border2);border-radius:8px;padding:9px 12px;font-size:13px;color:var(--text);font-family:var(--body);width:100%;outline:none;transition:border-color .2s}
input:focus,select:focus{border-color:var(--blue)}
select option{background:var(--s3)}
.form-error{font-size:11px;color:var(--red);margin-top:2px}
.alert-success{background:var(--green-lo);border:1px solid rgba(74,222,128,0.2);color:var(--green);padding:10px 14px;border-radius:8px;font-size:12px}
.alert-error{background:var(--red-lo);border:1px solid rgba(248,113,113,0.2);color:var(--red);padding:10px 14px;border-radius:8px;font-size:12px}
.year-selector{display:flex;gap:6px}
.year-btn{padding:6px 14px;border-radius:8px;font-size:12px;font-weight:500;cursor:pointer;transition:all .2s;border:1px solid var(--border2);background:transparent;color:var(--muted);font-family:var(--body)}
.year-btn:hover{background:var(--blue-lo);color:var(--text)}
.year-btn.active{background:var(--blue);border-color:var(--blue);color:white}
.type-tabs{display:flex;gap:8px}
.type-tab{padding:5px 14px;border-radius:99px;font-size:12px;cursor:pointer;border:1px solid var(--border);background:transparent;color:var(--muted);font-family:var(--body);transition:all .2s}
.type-tab.active{background:var(--blue-lo);border-color:var(--blue);color:var(--blue-hi)}
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
    <a href="{{ route('admin.dashboard') }}" class="logo">
      <div class="logo-gem"><svg viewBox="0 0 16 16"><path d="M8 1C8 1 3 6.5 3 10.5a5 5 0 0010 0C13 6.5 8 1 8 1z"/></svg></div>
      <span class="logo-txt">Aqua<em>Watt</em></span>
    </a>
    <div class="topbar-right">
      <span class="admin-badge">ADMIN</span>
      <span class="user-name">{{ Auth::user()->name }}</span>
      <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
    </div>
  </header>

  <aside class="sidebar">

    <button class="toggle-btn-edge" onclick="toggleSidebar()" title="Réduire le menu">
      <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M10 3L5 8l5 5"/>
      </svg>
    </button>

    <div class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-tooltip="Dashboard">
        <svg viewBox="0 0 16 16" fill="currentColor" style="flex-shrink:0"><rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/><rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/></svg>
        <span class="nav-label">Dashboard</span>
      </a>
      <a href="{{ route('admin.consommation') }}" class="nav-item {{ request()->routeIs('admin.consommation') ? 'active' : '' }}" data-tooltip="Consommation">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><polyline points="1,12 5,7 8,9 12,4 15,6"/></svg>
        <span class="nav-label">Consommation</span>
      </a>
      <div class="nav-divider"></div>
      <div class="nav-section">Gestion</div>
      <a href="{{ route('admin.contrats.index') }}" class="nav-item {{ request()->routeIs('admin.contrats.*') ? 'active' : '' }}" data-tooltip="Contrats">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><rect x="2" y="2" width="12" height="12" rx="1.5"/><path d="M5 6h6M5 8.5h6M5 11h4"/></svg>
        <span class="nav-label">Contrats</span>
      </a>
      <a href="{{ route('admin.techniciens.index') }}" class="nav-item {{ request()->routeIs('admin.techniciens.*') ? 'active' : '' }}" data-tooltip="Techniciens">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><circle cx="8" cy="5.5" r="3"/><path d="M2 14c0-3.3 2.7-5 6-5s6 1.7 6 5"/></svg>
        <span class="nav-label">Techniciens</span>
      </a>
      <a href="{{ route('admin.alerts.index') }}" class="nav-item {{ request()->routeIs('admin.alerts.*') ? 'active' : '' }}" data-tooltip="Alertes">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><path d="M8 2a5 5 0 00-5 5c0 3 5 8 5 8s5-5 5-8a5 5 0 00-5-5z"/><circle cx="8" cy="7" r="1.5" fill="currentColor" stroke="none"/></svg>
        <span class="nav-label">Alertes</span>
      </a>
      <a href="{{ route('admin.compteurs.create') }}" class="nav-item {{ request()->routeIs('admin.compteurs.*') ? 'active' : '' }}" data-tooltip="Compteur">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><circle cx="8" cy="8" r="6"/><path d="M8 5v3.5l2 1.5"/></svg>
        <span class="nav-label">Compteur</span>
      </a>
    </div>

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
    
    // Broadcast resize to force chart libraries to dynamically adapt sizing layout instantly
    setTimeout(() => {
      window.dispatchEvent(new Event('resize'));
    }, 300);
  }
</script>

@stack('scripts')
</body>
</html>