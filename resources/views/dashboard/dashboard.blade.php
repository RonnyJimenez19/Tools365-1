<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tools365 — Panel de Usuario (Prototipo)</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --sidebar-bg:#0f1117;
  --sidebar-w:220px;
  --accent:#534AB7;
  --accent-light:#7F77DD;
  --topbar-h:56px;
  --font:'Nunito',sans-serif;
  --card-bg:#fff;
  --card-border:#e8e8ef;
  --body-bg:#f4f5fa;
  --text-primary:#1a1a2e;
  --text-secondary:#6b7280;
  --text-muted:#a0aec0;
}
html,body{height:100%;font-family:var(--font);background:var(--body-bg);color:var(--text-primary)}
.layout{display:flex;height:100vh;overflow:hidden}

/* SIDEBAR */
.sidebar{width:var(--sidebar-w);background:var(--sidebar-bg);display:flex;flex-direction:column;flex-shrink:0;overflow-y:auto}
.sidebar-logo{padding:18px 16px 12px;font-size:18px;font-weight:900;color:#fff;letter-spacing:-0.5px;text-decoration:none;display:block}
.sidebar-logo span{color:var(--accent-light)}
.sidebar-user{display:flex;align-items:center;gap:10px;padding:10px 14px 14px;border-bottom:1px solid rgba(255,255,255,.07)}
.sidebar-avatar{width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-light));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;color:#fff;flex-shrink:0}
.sidebar-user-name{font-size:12px;font-weight:700;color:#fff}
.sidebar-user-role{font-size:10px;color:rgba(255,255,255,.4);margin-top:1px}
.nav-label{font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:1.2px;color:rgba(255,255,255,.3);padding:14px 16px 4px}
.nav-item{display:flex;align-items:center;gap:9px;padding:8px 16px;font-size:12px;font-weight:700;color:rgba(255,255,255,.55);cursor:pointer;border-radius:0;transition:all .15s;border:none;background:none;width:100%;text-align:left;text-decoration:none}
.nav-item:hover{color:#fff;background:rgba(255,255,255,.06)}
.nav-item.active{color:#fff;background:rgba(83,74,183,.35);border-left:3px solid var(--accent-light)}
.nav-item i{font-size:14px;width:16px;text-align:center}
.nav-badge{margin-left:auto;background:var(--accent);color:#fff;font-size:9px;font-weight:800;padding:2px 6px;border-radius:10px}
.nav-badge.new{background:#e74c3c}
.sidebar-footer{padding:12px 14px;border-top:1px solid rgba(255,255,255,.07);margin-top:auto}
.sidebar-logout{display:flex;align-items:center;gap:8px;width:100%;background:none;border:1px solid rgba(255,255,255,.12);border-radius:8px;padding:8px 12px;font-size:11px;font-weight:700;color:rgba(255,255,255,.5);cursor:pointer;transition:all .15s}
.sidebar-logout:hover{background:rgba(231,76,60,.15);border-color:rgba(231,76,60,.4);color:#e74c3c}

/* MAIN */
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.topbar{height:var(--topbar-h);background:#fff;border-bottom:1px solid var(--card-border);display:flex;align-items:center;gap:12px;padding:0 20px;flex-shrink:0}
.topbar-title{font-size:15px;font-weight:800;color:var(--text-primary)}
.topbar-spacer{flex:1}
.topbar-search{display:flex;align-items:center;gap:8px;background:#f4f5fa;border:1px solid #e8e8ef;border-radius:20px;padding:6px 14px;font-size:12px;color:var(--text-muted)}
.topbar-user-avatar{width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#fff}

/* CONTENT */
.content{flex:1;overflow-y:auto;padding:24px}
.section{display:none}
.section.active{display:block}
.page-header{margin-bottom:18px}
.page-header h2{font-size:20px;font-weight:900;color:var(--text-primary);margin-bottom:2px}
.page-header p{font-size:13px;color:var(--text-secondary)}

/* CARDS */
.card{background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:18px}
.card-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.card-title{font-size:14px;font-weight:800;color:var(--text-primary)}
.card-link{font-size:12px;font-weight:700;color:var(--accent);text-decoration:none;cursor:pointer;background:none;border:none}

/* GRID */
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px}
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:14px}

/* STAT CARDS */
.stat-card{background:#fff;border:1px solid var(--card-border);border-radius:12px;padding:14px;display:flex;align-items:center;gap:12px}
.stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.si-blue{background:#eef2ff;color:#534AB7}
.si-green{background:#e8f5e9;color:#27ae60}
.si-orange{background:#fff3e0;color:#e67e22}
.si-red{background:#fdecea;color:#e74c3c}
.stat-num{font-size:20px;font-weight:900;color:var(--text-primary);line-height:1}
.stat-lbl{font-size:11px;color:var(--text-secondary);margin-top:2px;font-weight:600}

/* BADGES */
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:800}
.badge-activo{background:#e8f5e9;color:#27ae60}
.badge-pausado{background:#fff3e0;color:#e67e22}
.badge-pendiente{background:#eef2ff;color:#534AB7}
.badge-vendido{background:#f5f5f5;color:#888}
.badge-venta{background:#dbeafe;color:#1d4ed8}
.badge-renta{background:#d1fae5;color:#065f46}
.badge-subasta{background:#fdecea;color:#c0392b}

/* TABLE */
.pub-table{width:100%;border-collapse:collapse;font-size:12px}
.pub-table th{text-align:left;padding:8px 10px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:var(--text-muted);border-bottom:1px solid var(--card-border)}
.pub-table td{padding:10px 10px;border-bottom:1px solid #f7f7f9;vertical-align:middle}
.pub-table tr:last-child td{border-bottom:none}
.pub-img{width:32px;height:32px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:14px;color:#534AB7;flex-shrink:0}
.pub-name{font-weight:800;font-size:12px;color:var(--text-primary)}
.pub-price{font-weight:900;color:#1F3A93;font-size:13px}
.actions-btns{display:flex;gap:6px}
.btn-sm{padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all .15s}
.btn-edit{background:#eef2ff;color:#534AB7}
.btn-edit:hover{background:#534AB7;color:#fff}
.btn-del{background:#fdecea;color:#e74c3c}
.btn-del:hover{background:#e74c3c;color:#fff}

/* PLAN BANNER */
.plan-banner{background:linear-gradient(135deg,#1F3A93,#534AB7);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;margin-bottom:16px;color:#fff}
.plan-banner-icon{font-size:24px}
.plan-banner h3{font-size:13px;font-weight:800;margin-bottom:2px}
.plan-banner p{font-size:11px;opacity:.8}
.plan-banner-btn{margin-left:auto;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:7px 14px;font-size:11px;font-weight:800;color:#fff;cursor:pointer;white-space:nowrap;flex-shrink:0;text-decoration:none}
.plan-banner-btn:hover{background:rgba(255,255,255,.25)}

/* PLAN CARDS */
.plan-card{background:#fff;border:2px solid var(--card-border);border-radius:14px;padding:18px;position:relative;cursor:pointer;transition:border-color .2s}
.plan-card:hover,.plan-card.featured{border-color:var(--accent)}
.plan-card.featured{background:#f9f7ff}
.plan-popular{position:absolute;top:-10px;left:50%;transform:translateX(-50%);background:var(--accent);color:#fff;font-size:10px;font-weight:800;padding:3px 12px;border-radius:20px;white-space:nowrap}
.plan-name{font-size:15px;font-weight:900;color:var(--text-primary);margin-bottom:4px}
.plan-price{font-size:26px;font-weight:900;color:var(--accent);line-height:1}
.plan-price small{font-size:12px;font-weight:600;color:var(--text-muted)}
.plan-features{margin:12px 0;list-style:none}
.plan-features li{font-size:12px;color:var(--text-secondary);padding:4px 0;display:flex;align-items:center;gap:7px;font-weight:600}
.plan-features li i{color:#27ae60;font-size:13px}
.plan-btn{width:100%;padding:9px;border-radius:8px;font-size:12px;font-weight:800;cursor:pointer;transition:all .2s;border:2px solid var(--accent);background:transparent;color:var(--accent);font-family:var(--font)}
.plan-btn.featured-btn{background:var(--accent);color:#fff}
.plan-btn:hover{background:var(--accent);color:#fff}

/* PERFIL */
.profile-header{display:flex;align-items:center;gap:16px;margin-bottom:18px}
.profile-avatar-big{width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-light));display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:900;color:#fff;flex-shrink:0;position:relative;cursor:pointer}
.avatar-edit-overlay{position:absolute;inset:0;border-radius:50%;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .2s;font-size:16px;color:#fff}
.profile-avatar-big:hover .avatar-edit-overlay{opacity:1}
.profile-name{font-size:18px;font-weight:900;color:var(--text-primary)}
.profile-username{font-size:13px;color:var(--text-muted);font-weight:600}
.form-group{margin-bottom:14px}
.form-label{font-size:11px;font-weight:800;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.7px;display:block;margin-bottom:5px}
.form-input{width:100%;padding:9px 13px;border:1.5px solid var(--card-border);border-radius:9px;font-size:13px;font-family:var(--font);font-weight:600;color:var(--text-primary);background:#fff;transition:border-color .2s;outline:none}
.form-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(83,74,183,.1)}
.form-hint{font-size:11px;color:var(--text-muted);margin-top:4px}
.form-hint.error{color:#e74c3c}
.btn-primary{background:var(--accent);color:#fff;border:none;padding:10px 22px;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer;font-family:var(--font);transition:background .2s}
.btn-primary:hover{background:#3C3489}
.btn-outline{background:transparent;color:var(--accent);border:2px solid var(--accent);padding:9px 20px;border-radius:9px;font-size:13px;font-weight:800;cursor:pointer;font-family:var(--font);transition:all .2s}
.btn-outline:hover{background:var(--accent);color:#fff}

/* PUBLICAR FORM */
.pub-step{display:flex;align-items:center;gap:8px;margin-bottom:20px}
.step-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0}
.step-dot.done{background:var(--accent);color:#fff}
.step-dot.current{background:#eef2ff;color:var(--accent);border:2px solid var(--accent)}
.step-dot.pending{background:#f4f5fa;color:var(--text-muted);border:2px solid #e2e2ed}
.step-line{flex:1;height:2px;background:#e2e2ed}
.step-line.done{background:var(--accent)}

/* UPLOAD ZONE */
.upload-zone{border:2px dashed var(--card-border);border-radius:12px;padding:24px;text-align:center;cursor:pointer;transition:all .2s;background:#fafafa}
.upload-zone:hover{border-color:var(--accent);background:#f9f7ff}
.upload-zone i{font-size:28px;color:var(--text-muted);display:block;margin-bottom:8px}
.upload-zone p{font-size:12px;color:var(--text-muted);font-weight:600}

/* COMENTARIOS */
.review-item{padding:14px 0;border-bottom:1px solid #f4f5fa}
.review-item:last-child{border-bottom:none}
.review-header{display:flex;align-items:center;gap:10px;margin-bottom:6px}
.review-avatar{width:30px;height:30px;border-radius:50%;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--accent);flex-shrink:0}
.review-name{font-size:12px;font-weight:800}
.review-date{font-size:11px;color:var(--text-muted);margin-left:auto}
.stars{color:#f1c40f;font-size:12px}
.review-text{font-size:12px;color:var(--text-secondary);line-height:1.6;margin-left:40px}
.review-reply{margin-left:40px;margin-top:8px;background:#f9f7ff;border-left:3px solid var(--accent);padding:8px 12px;border-radius:0 8px 8px 0}
.review-reply-label{font-size:10px;font-weight:800;color:var(--accent);margin-bottom:3px}
.review-reply-text{font-size:12px;color:var(--text-secondary)}

/* RENTAS / SUBASTAS */
.renta-item{display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--card-border);border-radius:10px;margin-bottom:10px;background:#fff}
.renta-img{width:44px;height:44px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;font-size:20px;color:var(--accent);flex-shrink:0}
.renta-name{font-size:13px;font-weight:800}
.renta-meta{font-size:11px;color:var(--text-muted);margin-top:2px}
.renta-price{margin-left:auto;text-align:right}
.renta-amount{font-size:14px;font-weight:900;color:var(--accent)}
.renta-period{font-size:11px;color:var(--text-muted)}
.timer-chip{display:inline-flex;align-items:center;gap:5px;background:#fdecea;color:#c0392b;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px}
.puja-chip{display:inline-flex;align-items:center;gap:5px;background:#e8f5e9;color:#27ae60;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px}
</style>
</head>
<body>

<div class="layout">

  <!-- ══ SIDEBAR ══ -->
  <aside class="sidebar">
    <a class="sidebar-logo">Tools<span>365</span></a>
    <div class="sidebar-user">
      <div class="sidebar-avatar">A</div>
      <div>
        <div class="sidebar-user-name">Angel Nahzapata</div>
        <div class="sidebar-user-role">Plan Básico</div>
      </div>
    </div>

    <div class="nav-label">Principal</div>
    <button class="nav-item active" onclick="go('overview',this)"><i class="bi bi-grid-1x2-fill"></i> Mi Panel</button>

    <div class="nav-label">Publicaciones</div>
    <button class="nav-item" onclick="go('publicar',this)"><i class="bi bi-plus-circle-fill"></i> Publicar herramienta</button>
    <button class="nav-item" onclick="go('mis-pubs',this)"><i class="bi bi-box-seam-fill"></i> Mis publicaciones</button>
    <button class="nav-item" onclick="go('comentarios',this)"><i class="bi bi-chat-left-text-fill"></i> Comentarios <span class="nav-badge">3</span></button>

    <div class="nav-label">Actividad</div>
    <button class="nav-item" onclick="go('rentas',this)"><i class="bi bi-clock-history"></i> Mis rentas <span class="nav-badge">2</span></button>
    <button class="nav-item" onclick="go('subastas',this)"><i class="bi bi-hammer"></i> Subastas <span class="nav-badge new">5</span></button>

    <div class="nav-label">Cuenta</div>
    <button class="nav-item" onclick="go('perfil',this)"><i class="bi bi-person-circle"></i> Mi perfil</button>
    <button class="nav-item" onclick="go('planes',this)"><i class="bi bi-star-fill"></i> Mi plan</button>

    <div class="sidebar-footer">
      <button class="sidebar-logout"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</button>
    </div>
  </aside>

  <!-- ══ MAIN ══ -->
  <div class="main">
    <header class="topbar">
      <span class="topbar-title" id="topbar-title">Mi Panel</span>
      <div class="topbar-spacer"></div>
      <div class="topbar-search"><i class="bi bi-search"></i>&nbsp; Buscar herramientas...</div>
      <div class="topbar-user-avatar">A</div>
    </header>

    <div class="content">

      <!-- ── OVERVIEW ── -->
      <div class="section active" id="s-overview">
        <div class="page-header">
          <h2>¡Hola, Angel! 👋</h2>
          <p>Resumen de tu actividad en Tools365</p>
        </div>
        <div class="plan-banner">
          <div class="plan-banner-icon">⚡</div>
          <div>
            <h3>Estás en el Plan Básico</h3>
            <p>17 de 20 publicaciones disponibles este mes. ¡Actualiza para publicaciones ilimitadas!</p>
          </div>
          <button class="plan-banner-btn" onclick="go('planes', document.querySelector('[onclick*=planes]'))">Ver planes</button>
        </div>
        <div class="grid-4" style="margin-bottom:16px">
          <div class="stat-card"><div class="stat-icon si-blue"><i class="bi bi-box-seam-fill"></i></div><div><div class="stat-num">3</div><div class="stat-lbl">Publicaciones</div></div></div>
          <div class="stat-card"><div class="stat-icon si-green"><i class="bi bi-bag-heart-fill"></i></div><div><div class="stat-num">8</div><div class="stat-lbl">Compras</div></div></div>
          <div class="stat-card"><div class="stat-icon si-orange"><i class="bi bi-hammer"></i></div><div><div class="stat-num">2</div><div class="stat-lbl">Ofertas activas</div></div></div>
          <div class="stat-card"><div class="stat-icon si-red"><i class="bi bi-wallet2"></i></div><div><div class="stat-num">$4,280</div><div class="stat-lbl">Este mes</div></div></div>
        </div>
        <div class="grid-2">
          <div class="card">
            <div class="card-row"><span class="card-title">Mis publicaciones</span><button class="card-link" onclick="go('mis-pubs',null)">Ver todas →</button></div>
            <table class="pub-table"><thead><tr><th>Producto</th><th>Tipo</th><th>Estado</th></tr></thead><tbody>
              <tr><td><div style="display:flex;align-items:center;gap:8px"><div class="pub-img"><i class="bi bi-gear"></i></div><span class="pub-name">Taladro Bosch 800W</span></div></td><td><span class="badge badge-venta">Venta</span></td><td><span class="badge badge-activo">● Activo</span></td></tr>
              <tr><td><div style="display:flex;align-items:center;gap:8px"><div class="pub-img"><i class="bi bi-truck"></i></div><span class="pub-name">Montacargas Yale 2T</span></div></td><td><span class="badge badge-renta">Renta</span></td><td><span class="badge badge-activo">● Activo</span></td></tr>
              <tr><td><div style="display:flex;align-items:center;gap:8px"><div class="pub-img" style="background:#fdecea;color:#e74c3c"><i class="bi bi-hammer"></i></div><span class="pub-name">Compresor 150 psi</span></div></td><td><span class="badge badge-subasta">Subasta</span></td><td><span class="badge badge-activo">● Activo</span></td></tr>
            </tbody></table>
          </div>
          <div class="card">
            <div class="card-row"><span class="card-title">Comentarios recientes</span><button class="card-link" onclick="go('comentarios',null)">Ver todos →</button></div>
            <div class="review-item">
              <div class="review-header"><div class="review-avatar">JL</div><div><div class="review-name">Juan López</div><div class="stars">★★★★★</div></div><span class="review-date">Hace 2h</span></div>
              <div class="review-text">Excelente herramienta, en perfectas condiciones...</div>
            </div>
            <div class="review-item">
              <div class="review-header"><div class="review-avatar">MR</div><div><div class="review-name">María R.</div><div class="stars">★★★★☆</div></div><span class="review-date">Ayer</span></div>
              <div class="review-text">Muy buen equipo, entrega puntual y bien empacado...</div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── PUBLICAR ── -->
      <div class="section" id="s-publicar">
        <div class="page-header"><h2>Publicar herramienta</h2><p>Completa los datos de tu publicación</p></div>
        <div class="pub-step">
          <div class="step-dot done">1</div><div class="step-line done"></div>
          <div class="step-dot current">2</div><div class="step-line"></div>
          <div class="step-dot pending">3</div>
        </div>
        <div class="grid-2" style="align-items:start">
          <div class="card">
            <div style="margin-bottom:14px;font-size:13px;font-weight:800;color:var(--text-primary)">Información general</div>
            <div class="form-group"><label class="form-label">Nombre del producto</label><input class="form-input" type="text" placeholder="Ej. Taladro Bosch 800W"></div>
            <div class="form-group"><label class="form-label">Tipo de publicación</label>
              <select class="form-input"><option>Venta</option><option>Renta</option><option>Subasta</option></select></div>
            <div class="form-group"><label class="form-label">Precio</label><input class="form-input" type="number" placeholder="$0.00"></div>
            <div class="form-group"><label class="form-label">Categoría</label>
              <select class="form-input"><option>Herramientas eléctricas</option><option>Maquinaria pesada</option><option>Equipos de medición</option></select></div>
            <div class="form-group"><label class="form-label">Descripción</label><textarea class="form-input" rows="3" placeholder="Describe el estado, características y condición..."></textarea></div>
          </div>
          <div class="card">
            <div style="margin-bottom:14px;font-size:13px;font-weight:800;color:var(--text-primary)">Imágenes</div>
            <div class="upload-zone">
              <i class="bi bi-cloud-arrow-up"></i>
              <p>Arrastra fotos aquí o <span style="color:var(--accent);font-weight:800">selecciona archivos</span></p>
              <p style="margin-top:4px;font-size:11px">PNG, JPG hasta 5MB — máx. 8 fotos</p>
            </div>
            <div style="margin-top:14px;font-size:13px;font-weight:800;color:var(--text-primary);margin-bottom:10px">Detalles adicionales</div>
            <div class="form-group"><label class="form-label">Marca</label><input class="form-input" type="text" placeholder="Bosch, DeWalt, Makita..."></div>
            <div class="form-group"><label class="form-label">Modelo / SKU</label><input class="form-input" type="text" placeholder="Opcional"></div>
            <div class="form-group"><label class="form-label">Condición</label>
              <select class="form-input"><option>Nuevo</option><option>Como nuevo</option><option>Bueno</option><option>Regular</option></select></div>
            <div style="display:flex;gap:10px;margin-top:6px">
              <button class="btn-outline">Guardar borrador</button>
              <button class="btn-primary" style="flex:1">Publicar →</button>
            </div>
          </div>
        </div>
      </div>

      <!-- ── MIS PUBLICACIONES ── -->
      <div class="section" id="s-mis-pubs">
        <div class="page-header">
          <div style="display:flex;align-items:center;justify-content:space-between">
            <div><h2>Mis publicaciones</h2><p>Administra todas tus herramientas publicadas</p></div>
            <button class="btn-primary" onclick="go('publicar',null)" style="display:flex;align-items:center;gap:6px"><i class="bi bi-plus-lg"></i> Publicar</button>
          </div>
        </div>
        <div class="card" style="margin-bottom:12px">
          <div style="display:flex;gap:8px">
            <span class="badge badge-activo" style="cursor:pointer">Todos (3)</span>
            <span class="badge" style="background:#f4f5fa;color:#888;cursor:pointer">Activos (2)</span>
            <span class="badge" style="background:#fff3e0;color:#e67e22;cursor:pointer">Pausados (1)</span>
            <span class="badge" style="background:#f5f5f5;color:#888;cursor:pointer">Vendidos (0)</span>
          </div>
        </div>
        <div class="card">
          <table class="pub-table">
            <thead><tr><th>Producto</th><th>Precio</th><th>Tipo</th><th>Estado</th><th>Vistas</th><th>Acciones</th></tr></thead>
            <tbody>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="pub-img"><i class="bi bi-gear"></i></div><div><div class="pub-name">Taladro Bosch 800W</div><div style="font-size:10px;color:var(--text-muted)">Publicado: 20 Abr 2026</div></div></div></td>
                <td class="pub-price">$1,200</td><td><span class="badge badge-venta">Venta</span></td>
                <td><span class="badge badge-activo">● Activo</span></td><td style="font-size:12px;color:var(--text-muted);font-weight:700">142</td>
                <td><div class="actions-btns"><button class="btn-sm btn-edit"><i class="bi bi-pencil-fill"></i></button><button class="btn-sm btn-del"><i class="bi bi-trash-fill"></i></button></div></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="pub-img"><i class="bi bi-truck"></i></div><div><div class="pub-name">Montacargas Yale 2T</div><div style="font-size:10px;color:var(--text-muted)">Publicado: 15 Abr 2026</div></div></div></td>
                <td class="pub-price">$850<small style="font-size:10px;font-weight:600;color:var(--text-muted)">/día</small></td><td><span class="badge badge-renta">Renta</span></td>
                <td><span class="badge badge-activo">● Activo</span></td><td style="font-size:12px;color:var(--text-muted);font-weight:700">89</td>
                <td><div class="actions-btns"><button class="btn-sm btn-edit"><i class="bi bi-pencil-fill"></i></button><button class="btn-sm btn-del"><i class="bi bi-trash-fill"></i></button></div></td>
              </tr>
              <tr>
                <td><div style="display:flex;align-items:center;gap:10px"><div class="pub-img" style="background:#fdecea;color:#e74c3c"><i class="bi bi-hammer"></i></div><div><div class="pub-name">Compresor 150 psi</div><div style="font-size:10px;color:var(--text-muted)">Publicado: 10 Abr 2026</div></div></div></td>
                <td class="pub-price" style="color:#e74c3c">$3,500</td><td><span class="badge badge-subasta">Subasta</span></td>
                <td><span class="badge badge-pausado">⏸ Pausado</span></td><td style="font-size:12px;color:var(--text-muted);font-weight:700">231</td>
                <td><div class="actions-btns"><button class="btn-sm btn-edit"><i class="bi bi-pencil-fill"></i></button><button class="btn-sm btn-del"><i class="bi bi-trash-fill"></i></button></div></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ── COMENTARIOS ── -->
      <div class="section" id="s-comentarios">
        <div class="page-header"><h2>Comentarios de ventas</h2><p>Reseñas de compradores y rentadores</p></div>
        <div class="grid-3" style="margin-bottom:14px">
          <div class="stat-card"><div class="stat-icon si-green" style="font-size:20px">⭐</div><div><div class="stat-num">4.7</div><div class="stat-lbl">Calificación promedio</div></div></div>
          <div class="stat-card"><div class="stat-icon si-blue"><i class="bi bi-chat-left-text-fill"></i></div><div><div class="stat-num">14</div><div class="stat-lbl">Total de reseñas</div></div></div>
          <div class="stat-card"><div class="stat-icon si-orange"><i class="bi bi-reply-fill"></i></div><div><div class="stat-num">3</div><div class="stat-lbl">Sin responder</div></div></div>
        </div>
        <div class="card">
          <div class="card-row"><span class="card-title">Reseñas recientes</span>
            <select style="font-size:12px;border:1px solid var(--card-border);border-radius:8px;padding:5px 10px;font-family:var(--font);font-weight:700;color:var(--text-secondary)"><option>Todos los productos</option><option>Taladro Bosch 800W</option></select>
          </div>
          <div class="review-item">
            <div class="review-header"><div class="review-avatar">JL</div><div><div class="review-name">Juan López</div><div class="stars">★★★★★</div></div><div style="margin-left:8px;font-size:11px;color:var(--text-muted)">Taladro Bosch 800W</div><span class="review-date">Hace 2h</span></div>
            <div class="review-text">Excelente herramienta, en perfectas condiciones. El vendedor fue muy amable y la entrega fue rápida. Totalmente recomendado.</div>
            <div class="review-reply"><div class="review-reply-label"><i class="bi bi-reply-fill"></i> Tu respuesta</div><div class="review-reply-text">¡Muchas gracias Juan! Fue un placer.</div></div>
          </div>
          <div class="review-item">
            <div class="review-header"><div class="review-avatar">MR</div><div><div class="review-name">María Ruiz</div><div class="stars">★★★★☆</div></div><div style="margin-left:8px;font-size:11px;color:var(--text-muted)">Montacargas Yale 2T</div><span class="review-date">Ayer</span></div>
            <div class="review-text">Muy buen equipo, entrega puntual y bien empacado. Le quito una estrella porque traía un detalle menor en el mástil.</div>
            <div style="margin-left:40px;margin-top:8px"><textarea class="form-input" rows="2" placeholder="Responder reseña..." style="font-size:12px"></textarea><button class="btn-primary" style="margin-top:6px;font-size:11px;padding:6px 14px"><i class="bi bi-reply-fill"></i> Responder</button></div>
          </div>
          <div class="review-item">
            <div class="review-header"><div class="review-avatar">CP</div><div><div class="review-name">Carlos Pérez</div><div class="stars">★★★☆☆</div></div><div style="margin-left:8px;font-size:11px;color:var(--text-muted)">Compresor 150 psi</div><span class="review-date">Hace 3 días</span></div>
            <div class="review-text">El compresor funciona bien pero llegó un poco tarde. Podría mejorar la comunicación durante el envío.</div>
            <div style="margin-left:40px;margin-top:8px"><textarea class="form-input" rows="2" placeholder="Responder reseña..." style="font-size:12px"></textarea><button class="btn-primary" style="margin-top:6px;font-size:11px;padding:6px 14px"><i class="bi bi-reply-fill"></i> Responder</button></div>
          </div>
        </div>
      </div>

      <!-- ── RENTAS ── -->
      <div class="section" id="s-rentas">
        <div class="page-header"><h2>Mis rentas</h2><p>Herramientas que tienes rentadas actualmente</p></div>
        <div class="grid-3" style="margin-bottom:14px">
          <div class="stat-card"><div class="stat-icon si-blue"><i class="bi bi-clock-history"></i></div><div><div class="stat-num">2</div><div class="stat-lbl">Rentas activas</div></div></div>
          <div class="stat-card"><div class="stat-icon si-green"><i class="bi bi-check-circle-fill"></i></div><div><div class="stat-num">7</div><div class="stat-lbl">Rentas completadas</div></div></div>
          <div class="stat-card"><div class="stat-icon si-red"><i class="bi bi-wallet2"></i></div><div><div class="stat-num">$1,700</div><div class="stat-lbl">Total gastado</div></div></div>
        </div>
        <div class="card">
          <div class="card-row"><span class="card-title">Rentas activas</span><span class="badge badge-activo">2 activas</span></div>
          <div class="renta-item">
            <div class="renta-img"><i class="bi bi-truck"></i></div>
            <div style="flex:1"><div class="renta-name">Montacargas Yale 2T</div><div class="renta-meta">De: Carlos Méndez &nbsp;·&nbsp; Desde: 5 May — Hasta: 12 May</div><div style="margin-top:6px"><span class="badge badge-activo">● En curso</span></div></div>
            <div class="renta-price"><div class="renta-amount">$850<small style="font-size:11px;font-weight:600;color:var(--text-muted)">/día</small></div><div class="renta-period">7 días · $5,950 total</div></div>
          </div>
          <div class="renta-item">
            <div class="renta-img"><i class="bi bi-lightning-charge"></i></div>
            <div style="flex:1"><div class="renta-name">Generador Cummins 45kW</div><div class="renta-meta">De: Servicios MX &nbsp;·&nbsp; Desde: 8 May — Hasta: 15 May</div><div style="margin-top:6px"><span class="badge badge-pendiente">⏳ Pendiente de inicio</span></div></div>
            <div class="renta-price"><div class="renta-amount">$1,200<small style="font-size:11px;font-weight:600;color:var(--text-muted)">/día</small></div><div class="renta-period">7 días · $8,400 total</div></div>
          </div>
          <div style="border-top:1px solid var(--card-border);margin-top:10px;padding-top:14px">
            <div class="card-row"><span class="card-title">Historial</span></div>
            <div class="renta-item" style="opacity:.65">
              <div class="renta-img" style="background:#f4f5fa;color:#aaa"><i class="bi bi-gear"></i></div>
              <div style="flex:1"><div class="renta-name">Taladro de Impacto DeWalt</div><div class="renta-meta">Completada: 1 May 2026 · 3 días</div></div>
              <div class="renta-price"><div class="renta-amount" style="color:#888">$450</div></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── SUBASTAS ── -->
      <div class="section" id="s-subastas">
        <div class="page-header"><h2>Subastas</h2><p>Ofertas en las que participas</p></div>
        <div class="grid-3" style="margin-bottom:14px">
          <div class="stat-card"><div class="stat-icon si-orange"><i class="bi bi-hammer"></i></div><div><div class="stat-num">2</div><div class="stat-lbl">Ofertas activas</div></div></div>
          <div class="stat-card"><div class="stat-icon si-green"><i class="bi bi-trophy-fill"></i></div><div><div class="stat-num">1</div><div class="stat-lbl">Subasta ganada</div></div></div>
          <div class="stat-card"><div class="stat-icon si-blue"><i class="bi bi-eye-fill"></i></div><div><div class="stat-num">5</div><div class="stat-lbl">Siguiendo</div></div></div>
        </div>
        <div class="card">
          <div class="card-row"><span class="card-title">Mis ofertas activas</span></div>
          <div class="renta-item">
            <div class="renta-img" style="font-size:22px"><i class="bi bi-buildings"></i></div>
            <div style="flex:1"><div class="renta-name">Excavadora CAT 320D</div><div class="renta-meta" style="margin-bottom:6px">Publicada por: Equipos MX</div>
              <div style="display:flex;gap:6px"><span class="timer-chip"><i class="bi bi-clock"></i> 3h 42m restantes</span><span class="puja-chip">👤 Tú vas ganando</span></div></div>
            <div class="renta-price"><div class="renta-amount">$48,500</div><div class="renta-period">tu puja actual</div><button class="btn-sm btn-edit" style="margin-top:6px;padding:5px 12px;font-size:11px;width:100%">Subir puja</button></div>
          </div>
          <div class="renta-item">
            <div class="renta-img" style="font-size:22px"><i class="bi bi-wind"></i></div>
            <div style="flex:1"><div class="renta-name">Dron DJI Agras T40</div><div class="renta-meta" style="margin-bottom:6px">Publicada por: Agro Drones SA</div>
              <div style="display:flex;gap:6px"><span class="timer-chip"><i class="bi bi-clock"></i> 8h 15m restantes</span><span style="display:inline-flex;align-items:center;gap:5px;background:#fff3e0;color:#e67e22;font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px">⚠ Superado</span></div></div>
            <div class="renta-price"><div class="renta-amount" style="color:#e74c3c">$22,000</div><div class="renta-period">mejor puja: $23,500</div><button class="btn-sm btn-del" style="margin-top:6px;padding:5px 12px;font-size:11px;width:100%">Contraofertar</button></div>
          </div>
        </div>
      </div>

      <!-- ── PERFIL ── -->
      <div class="section" id="s-perfil">
        <div class="page-header"><h2>Mi perfil</h2><p>Actualiza tu información personal</p></div>
        <div class="grid-2" style="align-items:start">
          <div class="card">
            <div class="profile-header">
              <div class="profile-avatar-big">A<div class="avatar-edit-overlay"><i class="bi bi-camera-fill"></i></div></div>
              <div><div class="profile-name">Angel Nahzapata</div><div class="profile-username">@angel_nahz</div></div>
            </div>
            <div style="margin-bottom:12px;font-size:11px;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:.7px">Foto de perfil</div>
            <div class="upload-zone" style="padding:14px;margin-bottom:16px">
              <i class="bi bi-image" style="font-size:20px;display:block;margin-bottom:6px"></i>
              <p style="font-size:11px">Subir nueva foto — JPG o PNG, máx. 2MB</p>
            </div>
            <div class="form-group">
              <label class="form-label">Nombre de usuario</label>
              <input class="form-input" type="text" value="angel_nahz" id="username-input" oninput="checkUsername(this)">
              <div class="form-hint" id="username-hint">Visible públicamente. No puede repetirse.</div>
            </div>
            <div class="form-group"><label class="form-label">Nombre completo</label><input class="form-input" type="text" value="Angel Nahzapata"></div>
            <div class="form-group"><label class="form-label">Descripción / Bio</label>
              <textarea class="form-input" rows="3">Proveedor de herramientas industriales en Yucatán. +5 años de experiencia en maquinaria pesada.</textarea></div>
            <button class="btn-primary" style="width:100%">Guardar cambios</button>
          </div>
          <div class="card">
            <div style="margin-bottom:14px;font-size:13px;font-weight:800;color:var(--text-primary)">Información de contacto</div>
            <div class="form-group"><label class="form-label">Correo electrónico</label><input class="form-input" type="email" value="angel@example.com" disabled style="background:#f9f9f9;color:var(--text-muted)"><div class="form-hint">El correo no puede editarse aquí.</div></div>
            <div class="form-group"><label class="form-label">Teléfono</label><input class="form-input" type="tel" placeholder="+52 999 000 0000"></div>
            <div class="form-group"><label class="form-label">Ciudad / Estado</label><input class="form-input" type="text" value="Oxkutzkab, Yucatán"></div>
            <div class="form-group"><label class="form-label">WhatsApp de negocios</label><input class="form-input" type="tel" placeholder="+52 999 000 0000"></div>
            <div style="margin:18px 0 12px;font-size:13px;font-weight:800;color:var(--text-primary)">Seguridad</div>
            <div class="form-group"><label class="form-label">Contraseña actual</label><input class="form-input" type="password" placeholder="••••••••"></div>
            <div class="form-group"><label class="form-label">Nueva contraseña</label><input class="form-input" type="password" placeholder="••••••••"></div>
            <button class="btn-outline" style="width:100%">Cambiar contraseña</button>
          </div>
        </div>
      </div>

      <!-- ── PLANES ── -->
      <div class="section" id="s-planes">
        <div class="page-header"><h2>Mi plan</h2><p>Elige el plan que mejor se adapta a tu negocio</p></div>
        <div class="card" style="background:#eef2ff;border-color:#c7c2f5;margin-bottom:16px;display:flex;align-items:center;gap:14px">
          <i class="bi bi-star-fill" style="font-size:22px;color:var(--accent)"></i>
          <div><div style="font-size:13px;font-weight:800;color:var(--accent)">Plan actual: Básico</div><div style="font-size:12px;color:#534AB7;opacity:.8">17 de 20 publicaciones disponibles · Vence el 31 Mayo 2026</div></div>
          <div style="margin-left:auto;font-size:12px;color:var(--text-muted);font-weight:700">$199/mes</div>
        </div>
        <div class="grid-3">
          <div class="plan-card">
            <div class="plan-name">Básico</div>
            <div class="plan-price">$199 <small>/mes</small></div>
            <ul class="plan-features">
              <li><i class="bi bi-check-circle-fill"></i> 20 publicaciones/mes</li>
              <li><i class="bi bi-check-circle-fill"></i> Venta y renta</li>
              <li><i class="bi bi-check-circle-fill"></i> Soporte por correo</li>
              <li><i class="bi bi-x-circle-fill" style="color:#e74c3c"></i> Subastas ilimitadas</li>
              <li><i class="bi bi-x-circle-fill" style="color:#e74c3c"></i> Posición prioritaria</li>
            </ul>
            <button class="plan-btn" disabled style="opacity:.5;cursor:default">Plan actual</button>
          </div>
          <div class="plan-card featured" style="margin-top:-8px">
            <div class="plan-popular">⭐ Más popular</div>
            <div class="plan-name">Pro</div>
            <div class="plan-price" style="color:var(--accent)">$499 <small>/mes</small></div>
            <ul class="plan-features">
              <li><i class="bi bi-check-circle-fill"></i> 100 publicaciones/mes</li>
              <li><i class="bi bi-check-circle-fill"></i> Venta, renta y subastas</li>
              <li><i class="bi bi-check-circle-fill"></i> Soporte prioritario</li>
              <li><i class="bi bi-check-circle-fill"></i> Posición mejorada</li>
              <li><i class="bi bi-x-circle-fill" style="color:#e74c3c"></i> API y reportes avanzados</li>
            </ul>
            <button class="plan-btn featured-btn">Contratar Pro</button>
          </div>
          <div class="plan-card">
            <div class="plan-name">Enterprise</div>
            <div class="plan-price">$1,299 <small>/mes</small></div>
            <ul class="plan-features">
              <li><i class="bi bi-check-circle-fill"></i> Publicaciones ilimitadas</li>
              <li><i class="bi bi-check-circle-fill"></i> Todo en Pro</li>
              <li><i class="bi bi-check-circle-fill"></i> Gerente de cuenta dedicado</li>
              <li><i class="bi bi-check-circle-fill"></i> API y reportes avanzados</li>
              <li><i class="bi bi-check-circle-fill"></i> Marca personalizada</li>
            </ul>
            <button class="plan-btn">Contratar Enterprise</button>
          </div>
        </div>
      </div>

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /layout -->

<script>
const titles = {
  'overview':'Mi Panel','publicar':'Publicar herramienta','mis-pubs':'Mis publicaciones',
  'comentarios':'Comentarios','rentas':'Mis rentas','subastas':'Subastas',
  'perfil':'Mi perfil','planes':'Mi plan'
};
function go(id, btn){
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  const s = document.getElementById('s-' + id);
  if(s) s.classList.add('active');
  if(btn) { btn.classList.add('active'); }
  else {
    document.querySelectorAll('.nav-item').forEach(n => {
      if(n.getAttribute('onclick') && n.getAttribute('onclick').includes("'" + id + "'"))
        n.classList.add('active');
    });
  }
  document.getElementById('topbar-title').textContent = titles[id] || 'Panel';
}
function checkUsername(input){
  const hint = document.getElementById('username-hint');
  const taken = ['admin','maria_r','tools365','juan_lp'];
  if(taken.includes(input.value.toLowerCase())){
    hint.textContent = '⚠ Ese nombre de usuario ya está en uso.';
    hint.className = 'form-hint error';
    input.style.borderColor = '#e74c3c';
  } else if(input.value.length < 3){
    hint.textContent = 'Mínimo 3 caracteres.';
    hint.className = 'form-hint error';
    input.style.borderColor = '#e74c3c';
  } else {
    hint.textContent = '✓ Nombre disponible';
    hint.className = 'form-hint';
    hint.style.color = '#27ae60';
    input.style.borderColor = '#27ae60';
  }
}
</script>
</body>
</html>