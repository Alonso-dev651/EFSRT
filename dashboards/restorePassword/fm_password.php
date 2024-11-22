<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/src/images/Logo.ico" type="image/x-icon">
  <title>Cambio de Contraseña</title>
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

    /* Responsividad */
    @media (max-width: 768px) {
      .container {
        padding: 30px;
        max-width: 100%;
      }

      h2 {
        font-size: 1.8rem;
      }

      p {
        font-size: 0.9rem;
      }

      form input,
      form select {
        font-size: 0.9rem;
        padding: 10px;
      }

      form .login-button {
        padding: 12px;
        font-size: 0.9rem;
      }
    }

    @media (max-width: 480px) {
      .container {
        padding: 20px;
      }

      .container img {
        max-width: 200px;
      }

      h2 {
        font-size: 1.6rem;
      }

      p {
        font-size: 0.8rem;
      }

      form input,
      form select {
        font-size: 0.85rem;
        padding: 8px;
      }

      form .login-button {
        padding: 10px;
        font-size: 0.85rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <img src="https://proyecto.live-ra.com/src/images/logo-jose-pardo.png" alt="Logo Jose Pardo">
    <h2>Cambiar Contraseña</h2>
    <form action="cambiar_contraseña.php" method="POST">
      <div>
        <label for="email" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div>
        <label for="currentPassword" class="form-label">Contraseña Actual</label>
        <input type="password" id="currentPassword" name="currentPassword" required>
      </div>
      <div>
        <label for="newPassword" class="form-label">Nueva Contraseña</label>
        <input type="password" id="newPassword" name="newPassword" required minlength="8">
      </div>
      <div>
        <label for="confirmPassword" class="form-label">Confirmar Nueva Contraseña</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
      </div>
      <button type="submit" class="login-button">Cambiar Contraseña</button>
    </form>
  </div>
</body>
</html>