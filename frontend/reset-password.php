<?php
$token = isset($_GET['token']) && is_string($_GET['token']) ? $_GET['token'] : '';
$tokenValido = preg_match('/\A[a-f0-9]{64}\z/', $token) === 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Restablecer contraseña · TechnoStore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box }
        body {
            min-height: 100vh;
            margin: 0;
            padding: 1.25rem;
            display: grid;
            place-items: center;
            background: #0b1019;
            color: #eef3fb;
            font: 16px/1.55 'DM Sans', system-ui, sans-serif
        }
        main {
            width: min(100%, 440px);
            padding: clamp(1.4rem, 6vw, 2.25rem);
            border: 1px solid #293447;
            border-radius: 18px;
            background: #121a27;
            box-shadow: 0 24px 70px #0006
        }
        .brand { color: #82b8f2; font: 700 1.15rem Sora, sans-serif }
        h1 { margin: 1.3rem 0 .4rem; font: 700 1.65rem Sora, sans-serif }
        .intro, .help { color: #aab8ca }
        label { display: block; margin: 1rem 0 .35rem; font-weight: 500 }
        input {
            width: 100%;
            padding: .8rem .9rem;
            border: 1px solid #39475c;
            border-radius: 9px;
            background: #0c121c;
            color: #fff;
            font: inherit
        }
        input:focus { outline: 3px solid #438bd366; border-color: #82b8f2 }
        button, .back {
            display: inline-flex;
            justify-content: center;
            width: 100%;
            margin-top: 1.2rem;
            padding: .82rem 1rem;
            border: 0;
            border-radius: 9px;
            background: #f27d82;
            color: #241419;
            font: 700 1rem 'DM Sans', sans-serif;
            text-align: center;
            text-decoration: none;
            cursor: pointer
        }
        button:disabled { opacity: .65; cursor: wait }
        .message { margin: 1rem 0 0; color: #f5a1a1 }
        .message.success { color: #79d6a5 }
        [hidden] { display: none !important }
    </style>
</head>
<body>
<main>
    <div class="brand">TechnoStore</div>
    <h1>Restablecer contraseña</h1>
    <?php if (!$tokenValido): ?>
        <p class="message">El enlace no es válido o está incompleto. Solicita uno nuevo desde la tienda.</p>
        <a class="back" href="./">Volver a la tienda</a>
    <?php else: ?>
        <p class="intro">Crea una contraseña nueva para volver a acceder a tu cuenta.</p>
        <form id="resetForm" novalidate>
            <label for="password">Nueva contraseña</label>
            <input id="password" type="password" minlength="6" autocomplete="new-password" required>
            <label for="confirmation">Confirmar contraseña</label>
            <input id="confirmation" type="password" minlength="6" autocomplete="new-password" required>
            <p class="help">Debe tener al menos 6 caracteres.</p>
            <button type="submit">Guardar contraseña</button>
        </form>
        <p class="message" id="status" role="status" aria-live="polite"></p>
        <a class="back" id="backLink" href="./" hidden>Volver a iniciar sesión</a>
        <noscript><p class="message">Activa JavaScript para completar el cambio de contraseña.</p></noscript>
        <script>
            const form = document.querySelector('#resetForm');
            const status = document.querySelector('#status');
            const button = form.querySelector('button');
            form.addEventListener('submit', async event => {
                event.preventDefault();
                const password = document.querySelector('#password').value;
                const confirmation = document.querySelector('#confirmation').value;
                status.classList.remove('success');
                if (password.length < 6) {
                    status.textContent = 'La contraseña debe tener al menos 6 caracteres.';
                    return;
                }
                if (password !== confirmation) {
                    status.textContent = 'Las contraseñas no coinciden.';
                    return;
                }
                button.disabled = true;
                status.textContent = 'Actualizando contraseña…';
                try {
                    const data = new FormData();
                    data.append('action', 'reset_password');
                    data.append('token', <?= json_encode($token, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
                    data.append('password', password);
                    data.append('password_confirmacion', confirmation);
                    const response = await fetch('backend/endpoints/api.php', { method: 'POST', body: data });
                    const result = await response.json();
                    if (!response.ok || !result.success) throw new Error(result.error || 'No se pudo actualizar la contraseña.');
                    form.hidden = true;
                    status.classList.add('success');
                    status.textContent = result.message || 'Contraseña actualizada. Ya puedes iniciar sesión.';
                    document.querySelector('#backLink').hidden = false;
                } catch (error) {
                    status.textContent = error.message || 'No se pudo conectar con el servidor. Inténtalo de nuevo.';
                    button.disabled = false;
                }
            });
        </script>
    <?php endif; ?>
</main>
</body>
</html>
