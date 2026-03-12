<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>

  <link rel="stylesheet" href="/res/admin/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="/res/admin/assets/css/overlayscrollbars.min.css">



  <!-- Custom Styles -->
  <style>
    body.login-page {
      background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e');
      background-size: cover;
      background-position: center;
      font-family: 'Roboto', sans-serif;
    }

    .login-box {
      backdrop-filter: blur(6px);
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .card-header a {
      font-size: 1.8rem;
      font-weight: bold;
      color: #007bff;
      text-decoration: none;
    }

    .card-header a i {
      margin-right: 8px;
    }

    .login-box-msg {
      font-size: 1rem;
      font-weight: 500;
    }

    .form-floating>.form-control:focus~label {
      opacity: 1;
      transform: scale(0.85) translateY(-1.5rem) translateX(0.15rem);
    }
  </style>
</head>

<body class="login-page bg-body-secondary"
  style="background-image: url(''); background-size: cover; background-position: center;">

  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-box" style="width: 100%; max-width: 420px;">
      <div class="card card-outline card-primary shadow-sm rounded-4">
        <div class="card-header text-center bg-white border-bottom-0">
          <a href="/admin/login" class="fs-4 fw-bold text-dark text-decoration-none">
            <i class="fas fa-utensils me-2"></i> PRATO CHEIO
          </a>
        </div>

        <div class="card-body p-4">
          <p class="login-box-msg text-center mb-3">Entre para iniciar sua sessão</p>

          <form action="/admin/login" method="post">
            <?php if( $error != '' ){ ?>

            <div class="alert alert-danger">
              <?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?>

            </div>
            <?php } ?>


            <!-- CPF -->
            <div class="input-group mb-3">
              <div class="form-floating flex-fill">
                <input id="cpf_cliente" type="text" class="form-control" name="cpf" placeholder="Seu CPF" maxlength="14"
                  oninput="formatCPF(this)" required />
                <label for="cpf">CPF</label>
              </div>
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>

            <!-- Senha -->
            <div class="input-group mb-3">
              <div class="form-floating flex-fill">
                <input id="password" type="password" class="form-control" name="senha" placeholder="Sua senha"
                  required />
                <label for="password">SENHA</label>
              </div>
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
            </div>

            <!-- Opções -->
            <div class="row mb-3">
              <div class="col-6 d-flex align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="rememberMe" />
                  <label class="form-check-label" for="rememberMe">Lembrar-me</label>
                </div>
              </div>
              <div class="col-6">
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
              </div>
            </div>
          </form>

          <div class="text-center">
            <a href="#">Esqueceu sua senha?</a><br />
          </div>
        </div>
      </div>
    </div>
  </div>

  <link href="/res/admin/assets/css/bootstrap.min.css" rel="stylesheet">  
  <link href="/res/admin/assets/css/overlayscrollbars.min.css" rel="stylesheet">

  <!-- Scrollbar Config -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true,
          },
        });
      }
    });

    function formatCPF(input) {
      let value = input.value.replace(/\D/g, ''); // Remove tudo que não é número
      if (value.length > 11) value = value.slice(0, 11); // Limita a 11 dígitos

      // Formata: 000.000.000-00
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

      input.value = value;
    }


    document.querySelector("form").addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      const response = await fetch("/admin/login", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        window.location.href = "/admin/index";
      } else {
        alert(result.error);
      }
    });

  </script>

</body>


</html>