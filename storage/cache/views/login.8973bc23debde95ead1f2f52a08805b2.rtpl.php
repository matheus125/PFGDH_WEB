<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Prato Cheio</title>

  <link rel="stylesheet" href="/res/admin/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    :root {
      --bg-dark: #0f172a;
      --bg-dark-2: #111827;
      --panel-dark: rgba(17, 24, 39, 0.92);
      --brand: #ff6b35;
      --brand-dark: #e85a2a;
      --brand-light: #ff8a5c;
      --text-white: #f8fafc;
      --text-soft: #cbd5e1;
      --text-muted: #94a3b8;
      --input-bg: rgba(255, 255, 255, 0.04);
      --input-border: rgba(255, 255, 255, 0.10);
      --shadow-main: 0 30px 80px rgba(0, 0, 0, 0.45);
      --shadow-brand: 0 18px 38px rgba(255, 107, 53, 0.28);
      --radius-xl: 30px;
      --transition: all 0.25s ease;
    }

    * {
      box-sizing: border-box;
    }

    html, body {
      margin: 0;
      padding: 0;
      min-height: 100%;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      min-height: 100vh;
      background:
        radial-gradient(circle at top left, rgba(255, 107, 53, 0.16), transparent 28%),
        radial-gradient(circle at bottom right, rgba(59, 130, 246, 0.10), transparent 30%),
        linear-gradient(135deg, rgba(2, 6, 23, 0.92), rgba(15, 23, 42, 0.96)),
        url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1800&q=80');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 28px;
      color: var(--text-white);
      overflow-x: hidden;
    }

    .page-shell {
      width: 100%;
      max-width: 1180px;
      animation: fadeUp 0.8s ease;
    }

    .login-wrapper {
      display: grid;
      grid-template-columns: 1.1fr 0.9fr;
      width: 100%;
      border-radius: var(--radius-xl);
      overflow: hidden;
      background: rgba(255, 255, 255, 0.04);
      backdrop-filter: blur(14px);
      box-shadow: var(--shadow-main);
      border: 1px solid rgba(255, 255, 255, 0.06);
      position: relative;
    }

    .login-brand {
      position: relative;
      padding: 54px 46px;
      background:
        linear-gradient(160deg, rgba(255, 107, 53, 0.96), rgba(232, 90, 42, 0.86)),
        linear-gradient(45deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0));
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
      animation: slideInLeft 0.7s ease;
    }

    .login-brand::before,
    .login-brand::after {
      content: "";
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }

    .login-brand::before {
      width: 340px;
      height: 340px;
      right: -110px;
      top: -110px;
      background: rgba(255, 255, 255, 0.08);
      filter: blur(8px);
    }

    .login-brand::after {
      width: 220px;
      height: 220px;
      left: -60px;
      bottom: -60px;
      background: rgba(255, 255, 255, 0.08);
      filter: blur(8px);
    }

    .brand-top,
    .brand-bottom {
      position: relative;
      z-index: 2;
    }

    .logo-brand {
      margin-bottom: 22px;
      animation: floating 3s ease-in-out infinite;
    }

    .logo-brand img {
      width: 190px;
      max-width: 100%;
      height: auto;
      display: block;
      background: rgba(255, 255, 255, 0.12);
      padding: 10px;
      border-radius: 20px;
      box-shadow: 0 16px 32px rgba(0, 0, 0, 0.18);
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 11px 18px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.16);
      border: 1px solid rgba(255, 255, 255, 0.12);
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 24px;
    }

    .brand-title {
      margin: 0 0 16px;
      font-size: 50px;
      line-height: 1.02;
      font-weight: bold;
      letter-spacing: -1px;
    }

    .brand-text {
      margin: 0;
      max-width: 500px;
      font-size: 16px;
      line-height: 1.8;
      color: rgba(255, 255, 255, 0.95);
    }

    .brand-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
      margin-top: 34px;
    }

    .brand-stat {
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.14);
      border-radius: 18px;
      padding: 18px 16px;
      text-align: left;
      transition: var(--transition);
    }

    .brand-stat:hover {
      transform: translateY(-3px);
      background: rgba(255, 255, 255, 0.16);
    }

    .brand-stat strong {
      display: block;
      font-size: 24px;
      margin-bottom: 6px;
    }

    .brand-stat span {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.92);
      line-height: 1.5;
    }

    .login-panel {
      background: var(--panel-dark);
      padding: 54px 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: slideInRight 0.7s ease;
      position: relative;
    }

    .login-card {
      width: 100%;
      max-width: 420px;
    }

    .logo-link {
      display: inline-flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
      color: var(--text-white);
      margin-bottom: 14px;
    }

    .logo-icon {
      width: 64px;
      height: 64px;
      border-radius: 18px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      background: linear-gradient(135deg, var(--brand), var(--brand-dark));
      box-shadow: var(--shadow-brand);
      flex-shrink: 0;
    }

    .logo-icon img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .logo-text {
      font-size: 30px;
      font-weight: bold;
      line-height: 1.1;
      color: var(--text-white);
    }

    .login-subtitle {
      margin: 0 0 28px;
      color: var(--text-soft);
      font-size: 15px;
      line-height: 1.7;
    }

    .login-alert {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 16px 18px;
      border-radius: 18px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      animation: alertFade 0.35s ease;
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
    }

    .login-alert-icon {
      width: 42px;
      height: 42px;
      min-width: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    .login-alert-content {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .login-alert-content strong {
      font-size: 15px;
      line-height: 1.2;
    }

    .login-alert-content span {
      font-size: 14px;
      line-height: 1.5;
    }

    .login-alert-error {
      background: linear-gradient(135deg, rgba(220, 53, 69, 0.16), rgba(220, 53, 69, 0.08));
      border-color: rgba(220, 53, 69, 0.24);
    }

    .login-alert-error .login-alert-icon {
      background: rgba(220, 53, 69, 0.18);
      color: #ff8a8a;
    }

    .login-alert-error .login-alert-content strong {
      color: #ffd4d8;
    }

    .login-alert-error .login-alert-content span {
      color: #ffc2c7;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-label-title {
      display: block;
      margin-bottom: 8px;
      color: #e2e8f0;
      font-size: 14px;
      font-weight: bold;
    }

    .input-group-custom {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 15px;
      z-index: 2;
      transition: var(--transition);
    }

    .form-control-custom {
      width: 100%;
      height: 58px;
      border: 1px solid var(--input-border);
      border-radius: 18px;
      padding: 0 48px 0 48px;
      font-size: 16px;
      background: var(--input-bg);
      outline: none;
      transition: var(--transition);
      color: var(--text-white);
    }

    .form-control-custom::placeholder {
      color: #94a3b8;
    }

    .form-control-custom:focus {
      border-color: rgba(255, 107, 53, 0.9);
      box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.15);
      background: rgba(255, 255, 255, 0.06);
    }

    .input-group-custom:focus-within .input-icon {
      color: var(--brand-light);
    }

    .cpf-feedback {
      display: none;
      align-items: center;
      gap: 8px;
      margin-top: 10px;
      padding: 10px 12px;
      border-radius: 14px;
      font-size: 13px;
      animation: alertFade 0.25s ease;
    }

    .cpf-feedback.show {
      display: flex;
    }

    .cpf-feedback.valid {
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.22);
      color: #4ade80;
    }

    .cpf-feedback.invalid {
      background: rgba(220, 53, 69, 0.12);
      border: 1px solid rgba(220, 53, 69, 0.22);
      color: #ff8a8a;
    }

    .cpf-valid .form-control-custom {
      border-color: rgba(34, 197, 94, 0.65);
      box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.10);
    }

    .cpf-invalid .form-control-custom {
      border-color: rgba(220, 53, 69, 0.75);
      box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.10);
    }

    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      width: 36px;
      height: 36px;
      border: none;
      background: transparent;
      color: var(--text-muted);
      cursor: pointer;
      border-radius: 10px;
      transition: var(--transition);
      z-index: 3;
    }

    .toggle-password:hover,
    .toggle-password:focus {
      color: var(--brand-light);
      background: rgba(255, 107, 53, 0.10);
      outline: none;
    }

    .capslock-warning {
      display: none;
      align-items: center;
      gap: 8px;
      margin-top: 10px;
      padding: 10px 12px;
      border-radius: 14px;
      background: rgba(245, 158, 11, 0.12);
      border: 1px solid rgba(245, 158, 11, 0.22);
      color: #fbbf24;
      font-size: 13px;
      animation: alertFade 0.25s ease;
    }

    .capslock-warning.show {
      display: flex;
    }

    .shake-field {
      animation: fieldShake 0.35s ease;
    }

    .password-error .form-control-custom {
      border-color: rgba(220, 53, 69, 0.75);
      box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.10);
    }

    .form-row-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin: 6px 0 22px;
      flex-wrap: wrap;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-check-input {
      cursor: pointer;
      accent-color: var(--brand);
    }

    .form-check-label {
      color: var(--text-soft);
      font-size: 14px;
      cursor: pointer;
    }

    .btn-login {
      width: 100%;
      height: 58px;
      border: none;
      border-radius: 18px;
      background: linear-gradient(135deg, var(--brand), var(--brand-dark));
      color: #fff;
      font-size: 18px;
      font-weight: bold;
      box-shadow: var(--shadow-brand);
      cursor: pointer;
      transition: var(--transition);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.18), transparent);
      transform: translateX(-100%);
      transition: transform 0.7s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 22px 36px rgba(255, 107, 53, 0.34);
    }

    .btn-login:hover::before {
      transform: translateX(100%);
    }

    .btn-login:disabled {
      opacity: 0.95;
      cursor: not-allowed;
      transform: none;
    }

    .btn-login.enter-pressed {
      transform: scale(0.985);
      box-shadow: 0 12px 22px rgba(255, 107, 53, 0.24);
    }

    .btn-text,
    .btn-loader {
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-loader {
      display: none;
      gap: 10px;
      font-size: 16px;
      font-weight: 600;
    }

    .btn-login.loading .btn-text {
      display: none;
    }

    .btn-login.loading .btn-loader {
      display: inline-flex;
    }

    .spinner-login {
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255, 255, 255, 0.45);
      border-top-color: #fff;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    .login-links {
      margin-top: 22px;
      text-align: center;
    }

    .login-links a {
      color: var(--brand-light);
      font-size: 15px;
      font-weight: 700;
      text-decoration: none;
      transition: var(--transition);
    }

    .login-links a:hover {
      color: #fff;
      text-decoration: underline;
    }

    .mini-note {
      margin-top: 18px;
      text-align: center;
      font-size: 12px;
      color: var(--text-muted);
    }

    .toast-login {
      position: fixed;
      top: 24px;
      right: 24px;
      width: min(380px, calc(100vw - 24px));
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 16px 16px 16px 14px;
      border-radius: 18px;
      border: 1px solid rgba(220, 53, 69, 0.24);
      background: linear-gradient(135deg, rgba(30, 41, 59, 0.96), rgba(15, 23, 42, 0.96));
      box-shadow: 0 18px 40px rgba(0, 0, 0, 0.30);
      z-index: 9999;
      opacity: 0;
      transform: translateY(-16px) scale(0.98);
      pointer-events: none;
      transition: all 0.30s ease;
      backdrop-filter: blur(8px);
    }

    .toast-login.show {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: auto;
    }

    .toast-login.hide {
      opacity: 0;
      transform: translateY(-10px) scale(0.98);
      pointer-events: none;
    }

    .toast-login-error {
      border-color: rgba(220, 53, 69, 0.28);
    }

    .toast-login-icon {
      width: 42px;
      height: 42px;
      min-width: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(220, 53, 69, 0.16);
      color: #ff8a8a;
      font-size: 18px;
    }

    .toast-login-content {
      display: flex;
      flex-direction: column;
      gap: 4px;
      flex: 1;
    }

    .toast-login-content strong {
      color: #ffd4d8;
      font-size: 15px;
      line-height: 1.2;
    }

    .toast-login-content span {
      color: #ffc2c7;
      font-size: 14px;
      line-height: 1.5;
    }

    .toast-login-close {
      border: none;
      background: transparent;
      color: #cbd5e1;
      width: 32px;
      height: 32px;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .toast-login-close:hover {
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-30px); }
      to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
      from { opacity: 0; transform: translateX(30px); }
      to { opacity: 1; transform: translateX(0); }
    }

    @keyframes floating {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-4px); }
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @keyframes alertFade {
      from { opacity: 0; transform: translateY(-8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fieldShake {
      0%, 100% { transform: translateX(0); }
      20% { transform: translateX(-5px); }
      40% { transform: translateX(5px); }
      60% { transform: translateX(-4px); }
      80% { transform: translateX(4px); }
    }

    @media (max-width: 1080px) {
      .login-wrapper {
        grid-template-columns: 1fr;
        max-width: 580px;
      }

      .brand-stats {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    @media (max-width: 768px) {
      body {
        padding: 16px;
      }

      .login-brand {
        display: none;
      }

      .login-panel {
        padding: 32px 22px;
      }

      .logo-icon {
        width: 56px;
        height: 56px;
      }

      .logo-text {
        font-size: 24px;
      }

      .form-control-custom {
        height: 54px;
        font-size: 15px;
      }

      .btn-login {
        height: 54px;
        font-size: 16px;
      }

      .toast-login {
        top: 14px;
        right: 12px;
        left: 12px;
        width: auto;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 12px;
      }

      .login-panel {
        padding: 24px 16px;
      }

      .logo-text {
        font-size: 22px;
      }
    }
  </style>
</head>

<body>
  <div class="page-shell">
    <div class="login-wrapper">

      <section class="login-brand">
        <div class="brand-top">
          <div class="logo-brand">
            <img src="/app/views/assets/img/logo-prato-cheio.png" alt="Logo Prato Cheio">
          </div>

          <div class="brand-badge">
            <i class="fas fa-bowl-food"></i>
            Plataforma moderna para restaurante
          </div>

          <h1 class="brand-title">PRATO CHEIO</h1>

          <p class="brand-text">
            Centralize seu atendimento, gestão e operação em uma experiência profissional, elegante e rápida para o dia a dia do seu restaurante.
          </p>
        </div>

        <div class="brand-bottom">
          <div class="brand-stats">
            <div class="brand-stat">
              <strong>Rápido</strong>
              <span>Acesso ágil ao painel administrativo.</span>
            </div>
            <div class="brand-stat">
              <strong>Seguro</strong>
              <span>Proteção no login da equipe.</span>
            </div>
            <div class="brand-stat">
              <strong>Moderno</strong>
              <span>Visual premium e responsivo.</span>
            </div>
          </div>
        </div>
      </section>

      <section class="login-panel">
        <div class="login-card">

          <a href="/admin/login" class="logo-link">
            <span class="logo-icon">
              <img src="/app/views/assets/img/logo-prato-cheio.png" alt="Logo">
            </span>
            <span class="logo-text">Prato Cheio</span>
          </a>

          <p class="login-subtitle">
            Entre para iniciar sua sessão no painel administrativo.
          </p>

          <form action="/admin/login" method="post" id="loginForm">

            <?php if( $error != '' ){ ?>

            <div class="login-alert login-alert-error" role="alert" id="inlineLoginError">
              <div class="login-alert-icon">
                <i class="fas fa-circle-exclamation"></i>
              </div>
              <div class="login-alert-content">
                <strong>Não foi possível entrar</strong>
                <span><?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
              </div>
            </div>

            <div class="toast-login toast-login-error show" id="loginErrorToast" role="alert" aria-live="assertive">
              <div class="toast-login-icon">
                <i class="fas fa-triangle-exclamation"></i>
              </div>
              <div class="toast-login-content">
                <strong>Erro no login</strong>
                <span><?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?></span>
              </div>
              <button type="button" class="toast-login-close" id="closeLoginToast" aria-label="Fechar">
                <i class="fas fa-xmark"></i>
              </button>
            </div>
            <?php } ?>


            <?php if( $attempts > 0 ){ ?>

            <div class="mini-note" style="margin-top:-6px; margin-bottom:16px;">
              Tentativas recentes: <?php echo htmlspecialchars( $attempts, ENT_COMPAT, 'UTF-8', FALSE ); ?>/5
            </div>
            <?php } ?>


            <div class="form-group">
              <label for="cpf_cliente" class="form-label-title">CPF</label>
              <div class="input-group-custom" id="cpfGroup">
                <i class="fas fa-id-card input-icon"></i>
                <input
                  type="text"
                  class="form-control-custom"
                  id="cpf_cliente"
                  name="cpf"
                  placeholder="Digite seu CPF"
                  maxlength="14"
                  oninput="formatCPF(this); validateCPFField(this)"
                  autocomplete="username"
                  required>
              </div>

              <div class="cpf-feedback" id="cpfFeedback">
                <i class="fas fa-circle-info"></i>
                <span>Digite um CPF válido.</span>
              </div>
            </div>

            <div class="form-group">
              <label for="senha" class="form-label-title">Senha</label>
              <div class="input-group-custom" id="passwordGroup">
                <i class="fas fa-lock input-icon"></i>
                <input
                  type="password"
                  class="form-control-custom"
                  id="senha"
                  name="senha"
                  placeholder="Digite sua senha"
                  autocomplete="current-password"
                  required>
                <button type="button" class="toggle-password" id="togglePassword" aria-label="Mostrar ou ocultar senha">
                  <i class="fas fa-eye"></i>
                </button>
              </div>

              <div class="capslock-warning" id="capsLockWarning">
                <i class="fas fa-keyboard"></i>
                <span>Caps Lock está ativado.</span>
              </div>
            </div>

            <div class="form-row-actions">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Lembrar-me</label>
              </div>
            </div>

            <button type="submit" class="btn-login" id="submitButton">
              <span class="btn-text">Entrar no sistema</span>
              <span class="btn-loader">
                <span class="spinner-login"></span>
                Entrando...
              </span>
            </button>

            <div class="login-links">
              <a href="/admin/forgot">Esqueceu sua senha?</a>
            </div>

            <div class="mini-note">
              Acesso restrito ao painel administrativo.
            </div>
          </form>

        </div>
      </section>

    </div>
  </div>

  <script>
    function formatCPF(input) {
      let value = input.value.replace(/\D/g, '');

      if (value.length > 11) {
        value = value.slice(0, 11);
      }

      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d)/, '$1.$2');
      value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

      input.value = value;
    }

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('senha');
    const passwordGroup = document.getElementById('passwordGroup');
    const capsLockWarning = document.getElementById('capsLockWarning');
    const loginForm = document.getElementById('loginForm');
    const submitButton = document.getElementById('submitButton');
    const cpfInput = document.getElementById('cpf_cliente');
    const cpfGroup = document.getElementById('cpfGroup');
    const cpfFeedback = document.getElementById('cpfFeedback');

    const loginErrorToast = document.getElementById('loginErrorToast');
    const closeLoginToast = document.getElementById('closeLoginToast');
    const inlineLoginError = document.getElementById('inlineLoginError');

    function isValidCPF(cpf) {
      cpf = cpf.replace(/\D/g, '');

      if (cpf.length !== 11) return false;
      if (/^(\d)\1+$/.test(cpf)) return false;

      let sum = 0;
      for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf.charAt(i)) * (10 - i);
      }

      let rev = 11 - (sum % 11);
      if (rev === 10 || rev === 11) rev = 0;
      if (rev !== parseInt(cpf.charAt(9))) return false;

      sum = 0;
      for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf.charAt(i)) * (11 - i);
      }

      rev = 11 - (sum % 11);
      if (rev === 10 || rev === 11) rev = 0;
      if (rev !== parseInt(cpf.charAt(10))) return false;

      return true;
    }

    function validateCPFField(input) {
      if (!cpfGroup || !cpfFeedback) return;

      const cpf = input.value.replace(/\D/g, '');

      cpfGroup.classList.remove('cpf-valid', 'cpf-invalid');
      cpfFeedback.classList.remove('show', 'valid', 'invalid');

      if (cpf.length === 0) return;

      if (cpf.length < 11) {
        cpfFeedback.classList.add('show', 'invalid');
        cpfFeedback.innerHTML = '<i class="fas fa-circle-info"></i><span>CPF incompleto.</span>';
        return;
      }

      if (isValidCPF(cpf)) {
        cpfGroup.classList.add('cpf-valid');
        cpfFeedback.classList.add('show', 'valid');
        cpfFeedback.innerHTML = '<i class="fas fa-circle-check"></i><span>CPF válido.</span>';
      } else {
        cpfGroup.classList.add('cpf-invalid');
        cpfFeedback.classList.add('show', 'invalid');
        cpfFeedback.innerHTML = '<i class="fas fa-circle-xmark"></i><span>CPF inválido.</span>';
      }
    }

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function () {
        const icon = this.querySelector('i');
        const isPassword = passwordInput.getAttribute('type') === 'password';

        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

        if (icon) {
          icon.classList.toggle('fa-eye');
          icon.classList.toggle('fa-eye-slash');
        }
      });
    }

    function hideToast() {
      if (loginErrorToast) {
        loginErrorToast.classList.remove('show');
        loginErrorToast.classList.add('hide');
      }
    }

    if (loginErrorToast) {
      setTimeout(hideToast, 5000);
    }

    if (closeLoginToast) {
      closeLoginToast.addEventListener('click', hideToast);
    }

    function animatePasswordError() {
      if (passwordGroup) {
        passwordGroup.classList.remove('shake-field');
        passwordGroup.classList.add('password-error');

        void passwordGroup.offsetWidth;
        passwordGroup.classList.add('shake-field');

        setTimeout(() => {
          passwordGroup.classList.remove('shake-field');
        }, 400);
      }
    }

    if (inlineLoginError && passwordGroup) {
      animatePasswordError();
    }

    function updateCapsLockState(event) {
      if (!capsLockWarning || !event.getModifierState) return;

      const isCapsLockOn = event.getModifierState('CapsLock');
      capsLockWarning.classList.toggle('show', isCapsLockOn);
    }

    if (passwordInput) {
      passwordInput.addEventListener('keydown', updateCapsLockState);
      passwordInput.addEventListener('keyup', updateCapsLockState);
      passwordInput.addEventListener('focus', function (event) {
        if (event.getModifierState && event.getModifierState('CapsLock')) {
          capsLockWarning.classList.add('show');
        }
      });
      passwordInput.addEventListener('blur', function () {
        if (capsLockWarning) {
          capsLockWarning.classList.remove('show');
        }
      });
    }

    function animateEnterButton() {
      if (!submitButton) return;

      submitButton.classList.add('enter-pressed');
      setTimeout(() => {
        submitButton.classList.remove('enter-pressed');
      }, 140);
    }

    if (cpfInput) {
      cpfInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
          animateEnterButton();
        }
      });
    }

    if (passwordInput) {
      passwordInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
          animateEnterButton();
        }
      });
    }

    if (loginForm && submitButton) {
      loginForm.addEventListener('submit', function () {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
      });
    }
  </script>

</body>
</html>