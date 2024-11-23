<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/src/images/Logo.ico" type="image/x-icon">
  <title>Formulario de Cambio de Contraseña</title>
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-color: #FEFDE8;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 10px;
    }
    .container {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      max-width: 450px;
      width: 100%;
      text-align: center;
    }
    .container img {
      max-width: 250px;
      margin-bottom: 20px;
    }

    h2 {
      font-size: 2rem;
      color: #2a3f54;
      margin-bottom: 10px;
    }

    p {
      font-size: 1rem;
      color: #4c4c4c;
      margin-bottom: 30px;
    }

    form {
      padding: 25px;
    }

    form label {
      font-size: 0.9rem;
      color: #4c4c4c;
      display: block;
      margin-top: 15px;
      text-align: left;
    }

    form small {
      font-size: 0.75rem;
      color: #888;
      margin-top: 2px;
    }

    form input,
    form select {
      width: 100%;
      padding: 12px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
      font-size: 1rem;
      background-color: #fff;
    }

    form input:focus,
    form select:focus {
      outline: none;
      border-color: #4db5ff;
      box-shadow: 0 0 5px rgba(77, 181, 255, 0.5);
    }

    form .login-button {
      width: 100%;
      padding: 14px;
      margin-top: 20px;
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: bold;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    form .login-button:hover {
      background-color: #004080;
      transform: scale(1.03);
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Cambiar Contraseña</h2>
    <form action="cambiar_contraseña.php" method="POST">
      <div>
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="newPassword">Nueva Contraseña</label>
        <input type="password" id="newPassword" name="newPassword" required minlength="8">
      </div>
      <div class="mb-3">
        <label for="confirmPassword">Confirmar Nueva Contraseña</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
      </div>
      <button type="submit" class="login-button">Cambiar Contraseña</button>
    </form>
  </div>
</body>
</html>
