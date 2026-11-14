<?php
$token = trim((string)($_GET['token'] ?? ''));
$esRestablecimiento = preg_match('/^[a-f0-9]{64}$/i', $token) === 1;
$tokenInvalido = $token !== '' && !$esRestablecimiento;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña · TechnoStore</title>
  <link rel="icon" type="image/png" href="logo/logo.png">
  <link rel="stylesheet" href="assets/css/styles.css">
  <style>
    body { min-height: 100vh; display: grid; place-items: center; padding: 1rem; }
    main { width: min(100%, 30rem); }
    .recover { padding: 1.5rem; }
    .recover h1 { margin-top: 0; }
    #recoverMessage { min-height: 1.5em; }
  </style>
</head>
<body>
  <main class="tw recover">
    <h1><?= $tokenInvalido ? 'Enlace no válido' : ($esRestablecimiento ? 'Crear una nueva contraseña' : 'Recuperar contraseña') ?></h1>
    <p class="sub"><?= $tokenInvalido
      ? 'El enlace de recuperación no tiene un formato válido. Solicita uno nuevo.'
      : ($esRestablecimiento
      ? 'Elige una contraseña nueva para tu cuenta.'
      : 'Te enviaremos un enlace de recuperación al correo asociado a tu cuenta.') ?></p>
    <?php if ($tokenInvalido): ?>
      <a class="btn ok" href="recuperar_contraseña.php">Solicitar otro enlace</a>
    <?php else: ?>
    <form id="recoverForm">
      <?php if ($esRestablecimiento): ?>
        <input type="hidden" name="action" value="restablecer_password">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        <div class="fg">
          <label for="newPassword">Nueva contraseña (mín. 6 caracteres)</label>
          <input id="newPassword" name="password" type="password" minlength="6" autocomplete="new-password" required>
        </div>
        <div class="fg">
          <label for="confirmPassword">Repetir contraseña</label>
          <input id="confirmPassword" type="password" minlength="6" autocomplete="new-password" required>
        </div>
      <?php else: ?>
        <input type="hidden" name="action" value="solicitar_recuperacion">
        <div class="fg">
          <label for="email">Correo electrónico</label>
          <input id="email" name="correo" type="email" maxlength="120" autocomplete="email" required>
        </div>
      <?php endif; ?>
      <p id="recoverMessage" role="status" aria-live="polite"></p>
      <div class="mf">
        <a class="btn ghost" href="index.php">Volver a iniciar sesión</a>
        <button class="btn ok" id="submitButton"><?= $esRestablecimiento ? 'Guardar contraseña' : 'Enviar enlace' ?></button>
      </div>
    </form>
    <?php endif; ?>
  </main>
  <script>
    const form = document.getElementById('recoverForm');
    const message = document.getElementById('recoverMessage');
    const button = document.getElementById('submitButton');
    form.addEventListener('submit', async event => {
      event.preventDefault();
      if (form.elements.confirmPassword && form.elements.password.value !== form.elements.confirmPassword.value) {
        message.textContent = 'Las contraseñas no coinciden.';
        return;
      }
      button.disabled = true;
      message.textContent = 'Procesando…';
      try {
        const response = await fetch('backend/endpoints/api.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
          body: new URLSearchParams(new FormData(form))
        });
        const result = await response.json();
        if (!response.ok || !result.success) throw new Error(result.error || 'No se pudo completar la solicitud.');
        message.textContent = result.message || 'Solicitud completada.';
        if (form.querySelector('[name="action"]').value === 'restablecer_password') {
          form.reset();
          button.disabled = true;
        } else {
          form.elements.email.value = '';
        }
      } catch (error) {
        message.textContent = error.message || 'No se pudo contactar al servidor. Inténtalo nuevamente.';
        button.disabled = false;
      }
    });
  </script>
</body>
</html>
