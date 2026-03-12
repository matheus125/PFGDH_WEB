<?php if(!class_exists('Rain\Tpl')){exit;}?><!doctype html>
<html lang="pt-br">

<head charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PRATO CHEIO</title>

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="PRATO CHEIO | Painel Administrativo" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="Sistema administrativo PRATO CHEIO" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="/res/admin/dist/css/adminlte.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    <!-- HEADER -->
    <nav class="app-header navbar navbar-expand bg-body painel-header">
      <div class="container-fluid">

        <!-- ESQUERDA -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link btn-menu" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="/admin" class="nav-link nav-home-link">Home</a>
          </li>
        </ul>

        <!-- DIREITA -->
        <ul class="navbar-nav ms-auto align-items-center">

          <!-- NOTIFICAÇÕES -->
          <li class="nav-item dropdown">
            <a class="nav-link nav-icone-topo position-relative" data-bs-toggle="dropdown" href="#">
              <i class="bi bi-bell-fill"></i>
              <?php if( $total > 0 ){ ?>

              <span class="navbar-badge badge text-bg-warning"><?php echo htmlspecialchars( $total, ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
              <?php } ?>

            </a>

            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end dropdown-notificacoes shadow-sm">
              <div class="dropdown-header-notificacao">
                <div>
                  <strong>Notificações</strong>
                  <div class="small text-muted">
                    <?php echo htmlspecialchars( $total, ENT_COMPAT, 'UTF-8', FALSE ); ?> item(ns)
                  </div>
                </div>

                <button type="button" id="btn-limpar-notificacoes"
                  class="btn btn-sm btn-outline-danger btn-limpar-notificacoes">
                  <i class="bi bi-trash3"></i> Limpar
                </button>
              </div>

              <div class="dropdown-divider"></div>

              <?php if( $total == 0 ){ ?>

              <div class="dropdown-item text-center text-muted py-3">
                <i class="bi bi-bell-slash d-block fs-4 mb-2"></i>
                Nenhuma notificação.
              </div>
              <?php }else{ ?>

              <div class="lista-notificacoes">
                <?php $counter1=-1;  if( isset($notificacoes) && ( is_array($notificacoes) || $notificacoes instanceof Traversable ) && sizeof($notificacoes) ) foreach( $notificacoes as $key1 => $value1 ){ $counter1++; ?>

                <a href="#" class="dropdown-item item-notificacao">
                  <div class="d-flex align-items-start">
                    <div class="icone-notificacao">
                      <i class="bi bi-cloud-check-fill text-success"></i>
                    </div>
                    <div class="flex-grow-1">
                      <div class="texto-notificacao"><?php echo htmlspecialchars( $value1["msg"], ENT_COMPAT, 'UTF-8', FALSE ); ?></div>
                      <small class="text-muted"><?php echo htmlspecialchars( $value1["time"], ENT_COMPAT, 'UTF-8', FALSE ); ?></small>
                    </div>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <?php } ?>

              </div>
              <?php } ?>


              <a href="/admin/notificacoes" class="dropdown-item dropdown-footer">
                Ver painel
              </a>
            </div>
          </li>

          <!-- FULLSCREEN -->
          <li class="nav-item">
            <a class="nav-link nav-icone-topo" href="#" data-lte-toggle="fullscreen">
              <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
              <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
            </a>
          </li>

          <!-- USUÁRIO -->
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle user-topo" data-bs-toggle="dropdown">
              <img src="/res/admin/dist/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow-sm"
                alt="User Image" />
              <span class="d-none d-md-inline"><?php echo getUserName(); ?></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-sm">
              <li class="user-header text-bg-primary perfil-header">
                <img src="/res/admin/dist/assets/img/user2-160x160.jpg" class="rounded-circle shadow"
                  alt="User Image" />
                <p>
                  <?php echo getUserName(); ?>

                  <small>Membro desde Nov. 2023</small>
                </p>
              </li>

              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Perfil</a>
                <a href="/admin/logout" class="btn btn-danger btn-flat float-end">Sair</a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="app-sidebar bg-body-secondary sidebar-custom" data-bs-theme="light">
      <div class="sidebar-brand">
        <a href="/admin" class="brand-link brand-custom">
          <img src="/res/admin/dist/assets/img/AdminLTELogo.png" alt="Logo" class="brand-image opacity-75 shadow-sm" />
          <span class="brand-text fw-semibold">PRATO CHEIO</span>
        </a>
      </div>

      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">

            <li class="nav-header titulo-menu">PRINCIPAL</li>
            <li class="nav-item">
              <?php if( canAccess('DASHBOARD_VIEW') ){ ?><a href="/admin" class="nav-link">
                <i class="nav-icon bi bi-speedometer2"></i>
                <p>Dashboard</p>
              </a><?php } ?>

            </li>

            <li class="nav-header titulo-menu">CADASTROS</li>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link active">
                <i class="nav-icon bi bi-folder2-open"></i>
                <p>
                  Cadastros
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <?php if( canAccess('FUNCIONARIOS_VIEW') ){ ?><a href="/admin/funcionarios" class="nav-link active">
                    <i class="nav-icon bi bi-person-badge"></i>
                    <p>Funcionários</p>
                  </a><?php } ?>

                </li>
                <li class="nav-item">
                  <?php if( canAccess('CLIENTES_VIEW') ){ ?><a href="/admin/clientes" class="nav-link">
                    <i class="nav-icon bi bi-people"></i>
                    <p>Clientes</p>
                  </a><?php } ?>

                </li>
              </ul>
            </li>

            <li class="nav-header titulo-menu">MOVIMENTOS</li>
            <li class="nav-item menu-open">
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-cart-check"></i>
                <p>
                  Vendas
                  <i class="nav-arrow bi bi-chevron-right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <?php if( canAccess('VENDAS_VIEW') ){ ?><a href="/admin/vendas" class="nav-link">
                    <i class="nav-icon bi bi-ticket-perforated"></i>
                    <p>Senhas</p>
                  </a><?php } ?>

                </li>
                <li class="nav-item">
                  <?php if( canAccess('RELATORIOS_VIEW') ){ ?><a href="/admin/relatorio/senhas" class="nav-link">
                    <i class="nav-icon bi bi-file-earmark-text"></i>
                    <p>Expediente</p>
                  </a><?php } ?>

                </li>
              </ul>
            </li>

          </ul>
        </nav>
      </div>
    </aside>

    <style>
      body {
        background: #f4f6f9;
        font-family: "Source Sans 3", sans-serif;
      }

      .painel-header {
        border-bottom: 1px solid #dcdcdc;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        background: #fff;
      }

      .btn-menu,
      .nav-home-link,
      .nav-icone-topo,
      .user-topo {
        color: #2c3e50 !important;
      }

      .btn-menu:hover,
      .nav-home-link:hover,
      .nav-icone-topo:hover,
      .user-topo:hover {
        color: #2e86c1 !important;
      }

      .navbar-badge {
        font-size: 11px;
        padding: 4px 6px;
        border-radius: 50px;
      }

      .dropdown-notificacoes {
        width: 360px;
        border: 0;
        border-radius: 10px;
        overflow: hidden;
      }

      .dropdown-header-notificacao {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 16px 10px 16px;
        background: #f8f9fa;
      }

      .btn-limpar-notificacoes {
        border-radius: 6px;
        font-size: 12px;
      }

      .lista-notificacoes {
        max-height: 320px;
        overflow-y: auto;
      }

      .item-notificacao {
        padding: 12px 14px;
        transition: background 0.2s ease;
      }

      .item-notificacao:hover {
        background: #f4f8fb;
      }

      .icone-notificacao {
        width: 34px;
        min-width: 34px;
        text-align: center;
        margin-right: 10px;
        font-size: 18px;
      }

      .texto-notificacao {
        color: #2c3e50;
        font-size: 14px;
        line-height: 1.3;
      }

      .dropdown-footer {
        text-align: center;
        font-weight: 600;
        color: #2e86c1;
        background: #fafafa;
      }

      .perfil-header {
        background: linear-gradient(135deg, #2e86c1, #3c8dbc) !important;
      }

      .sidebar-custom {
        border-right: 1px solid #dcdcdc;
      }

      .brand-custom {
        background: #ffffff;
        border-bottom: 1px solid #e5e5e5;
      }

      .brand-custom .brand-text {
        color: #2c3e50;
        font-size: 18px;
      }

      .titulo-menu {
        font-size: 11px;
        font-weight: 700;
        color: #7f8c8d !important;
        letter-spacing: 0.8px;
        margin-top: 8px;
      }

      .sidebar-menu .nav-link {
        border-radius: 8px;
        margin: 3px 8px;
        color: #34495e;
        transition: all 0.2s ease;
      }

      .sidebar-menu .nav-link:hover {
        background: #edf4fa;
        color: #2e86c1;
      }

      .sidebar-menu .nav-link.active {
        background: #2e86c1;
        color: #fff !important;
        font-weight: 600;
      }

      .sidebar-menu .nav-treeview .nav-link.active {
        background: #d9ecf8;
        color: #2e86c1 !important;
      }

      .sidebar-menu .nav-icon {
        margin-right: 6px;
      }

      .user-footer {
        padding: 12px 14px;
      }

      .user-footer .btn {
        border-radius: 6px;
      }

      @media (max-width: 767px) {
        .dropdown-notificacoes {
          width: 300px;
        }
      }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("btn-limpar-notificacoes");
        if (!btn) return;
        btn.addEventListener("click", function () {
          Swal.fire({ title: "Limpar notificações?", text: "Todas as notificações serão removidas.", icon: "warning", showCancelButton: true, confirmButtonText: "Sim, limpar", cancelButtonText: "Cancelar", confirmButtonColor: "#d33" }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch("/admin/notificacoes/limpar", { method: "POST", headers: { "X-Requested-With": "XMLHttpRequest" } })
              .then(r => r.json())
              .then(function (data) {
                if (data.success) {
                  Swal.fire({ toast: true, position: "top-end", icon: "success", title: "Notificações limpas com sucesso", showConfirmButton: false, timer: 1800, timerProgressBar: true });
                  setTimeout(function () { window.location.reload(); }, 1000);
                } else {
                  throw new Error();
                }
              }).catch(function () {
                Swal.fire({ icon: "error", title: "Erro", text: "Não foi possível limpar as notificações." });
              });
          });
        });
      });

      let totalAnterior = { $total };

      function atualizarNotificacoes() {

        fetch("/admin/api/notificacoes")

          .then(res => res.json())

          .then(data => {

            const badge = document.getElementById("badge-notificacoes");

            if (!badge) return;

            badge.innerText = data.total;

            if (data.total > totalAnterior) {

              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Nova notificação recebida',
                showConfirmButton: false,
                timer: 3000
              });

            }

            totalAnterior = data.total;

          });

      }

      setInterval(atualizarNotificacoes, 10000);
    </script>