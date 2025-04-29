<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FormBox')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f8f9fa; }
        #sidebar-menu { background: #0d6efd; color: #fff; }
        .navbar, .navbar * { font-family: inherit; }
    </style>
    @yield('head')
</head>
<body>
<script>
let menuType = localStorage.getItem('menuType') || 'top';
function toggleMenuType() {
    menuType = (menuType === 'top') ? 'side' : 'top';
    localStorage.setItem('menuType', menuType);
    renderMenus();
}
function renderMenus() {
    if(menuType === 'top') {
        document.getElementById('navbar-top').style.display = '';
        document.getElementById('sidebar-menu').style.display = 'none';
    } else {
        document.getElementById('navbar-top').style.display = 'none';
        document.getElementById('sidebar-menu').style.display = '';
    }
}
document.addEventListener('DOMContentLoaded', renderMenus);
</script>
<nav id="navbar-top" class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">FormBox</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link @if(request()->is('builder')) active @endif" href="/formbox/public/builder">Builder</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if(request()->is('formularios')) active @endif" href="/formbox/public/formularios">Formularios</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Exportar</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Analytics</a>
        </li>
      </ul>
      <button class="btn btn-outline-light btn-sm ms-auto" onclick="toggleMenuType()">Cambiar menú</button>
    </div>
  </div>
</nav>
<div id="sidebar-menu" style="display:none;position:fixed;top:0;left:0;height:100vh;width:220px;z-index:1030;">
    <div class="p-3">
        <h4 class="mb-4">FormBox</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="/formbox/public/builder">Builder</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/formbox/public/formularios">Formularios</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Exportar</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Analytics</a></li>
        </ul>
        <button class="btn btn-light btn-sm mt-4" onclick="toggleMenuType()">Cambiar menú</button>
    </div>
</div>
<div class="container-fluid px-0">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
