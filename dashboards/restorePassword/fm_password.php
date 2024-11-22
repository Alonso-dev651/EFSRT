<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Cambio de Contraseña</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ+zXDA++cSu/XHQA0vVn9Z2c6gq4b5u+Rysm2Oya4T4RZ38DJt3cbCkVhl1" crossorigin="anonymous">
  <style>
    body {
      background-color: #f4f7fc;
      font-family: 'Arial', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
    }
    .container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 30px;
      width: 100%;
      max-width: 600px;
    }
    h2 {
      color: #007bff;
      font-size: 1.8rem;
      text-align: center;
      margin-bottom: 30px;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
    .form-control {
      border-radius: 8px;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.12);
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .mb-3 {
      margin-bottom: 20px; 
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Cambiar Contraseña</h2>
    <form action="cambiar_contraseña.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="currentPassword" class="form-label">Contraseña Actual</label>
        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
      </div>
      <div class="mb-3">
        <label for="newPassword" class="form-label">Nueva Contraseña</label>
        <input type="password" class="form-control" id="newPassword" name="newPassword" required minlength="8">
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirmar Nueva Contraseña</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Cambiar Contraseña</button>
    </form>
  </div>
</body>
</html>
