<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
<link rel="icon" href="{{ asset('image.png') }}">
<title>AquaWatt Technicien — @yield('title', 'Dashboard')</title>
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
.logo-txt em{font-style:normal;color:var(--cyan)}
.shell.collapsed .logo-txt{max-width:0;opacity:0}
.topbar-right{display:flex;align-items:center;gap:12px}
.tech-badge{font-size:10px;font-weight:600;padding:3px 8px;border-radius:99px;background:rgba(0,207,255,0.12);color:var(--cyan);border:1px solid rgba(0,207,255,0.25);letter-spacing:.05em;white-space:nowrap}
.avatar{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--cyan),var(--blue));display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff}
.user-name{font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;transition:opacity 0.2s, max-width 0.28s cubic-bezier(.4,0,.2,1);max-width:200px}

/* SIDEBAR */
.sidebar{
  border-right:1px solid var(--border);
  background:var(--s1);
  padding:1.25rem 0.75rem;
  display:flex;
  flex-direction:column;
  gap:4px;
  overflow:visible;
  position:relative;
  transition:padding 0.28s cubic-bezier(.4,0,.2,1);
}
.shell.collapsed .sidebar{padding:1.25rem 0.5rem}

/* TOGGLE EDGE BUTTON */
.toggle-btn-edge{
  position:absolute;
  right:-12px;
  top:50%;
  transform:translateY(-50%);
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

.nav-divider{height:1px;background:var(--border);margin:8px 0;transition:opacity 0.18s}
.shell.collapsed .nav-divider{opacity:0}

.sidebar-footer{margin-top:auto;padding-top:12px}

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
.main{overflow-y:auto;padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem}
</style>
</head>
<body>
<div class="shell" id="shell">

  <header class="topbar">
    <a href="{{ route('tech.index') }}" class="logo">
      <div class="logo-gem"><svg viewBox="0 0 16 16"><path d="M8 1C8 1 3 6.5 3 10.5a5 5 0 0010 0C13 6.5 8 1 8 1z"/></svg></div>
      <span class="logo-txt">Aqua<em>Watt</em></span>
    </a>
    <div class="topbar-right">
      <span class="tech-badge">TECHNICIEN</span>
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

    <a href="{{ route('tech.index') }}" class="nav-item {{ request()->routeIs('tech.index') ? 'active' : '' }}" data-tooltip="Relevés">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><circle cx="8" cy="8" r="6"/><path d="M8 5v3l2 2"/></svg>
      <span class="nav-label">Relevés</span>
    </a>

    <a href="{{ route('tech.alertes') }}" class="nav-item {{ request()->routeIs('tech.alertes') ? 'active' : '' }}" data-tooltip="Alertes">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4" style="flex-shrink:0"><path d="M8 2l1.5 4h4l-3 2.5 1 4L8 10l-3.5 2.5 1-4L2.5 6h4z"/></svg>
      <span class="nav-label">Alertes</span>
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