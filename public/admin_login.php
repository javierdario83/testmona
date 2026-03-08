<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/helpers.php';

$msg = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quick_view'])) {
        $correlative = trim($_POST['correlative'] ?? '');
        header('Location: /public/client_view.php?code=' . urlencode($correlative));
        exit;
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (attempt_login($username, $password)) {
        header('Location: /admin/dashboard.php');
        exit;
    }

    $msg = 'Usuario o contraseña inválidos.';
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin | Brujosa Tarot</title>
  <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<div class="container" style="max-width:560px;">
  <div class="card">
    <h1>Bienvenida Brujosa</h1>
    <?php if ($msg): ?><div class="notice error"><?= e($msg) ?></div><?php endif; ?>
    <form method="post">
      <label>Usuario</label>
      <input name="username" required>
      <label>Contraseña</label>
      <input type="password" name="password" required>
      <br><br>
      <button type="submit">Ingresar</button>
    </form>
  </div>

  <div class="card">
    <h3>Vista rápida de cliente (sin login)</h3>
    <form method="post">
      <input type="hidden" name="quick_view" value="1">
      <label>Correlativo de lectura</label>
      <input name="correlative" required>
      <br><br>
      <button type="submit">Vista de cliente</button>
    </form>
  </div>
</div>
</body>
</html>
