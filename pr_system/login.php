<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Procurement Data System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            z-index: 10;
        }

        .logo-img {
            width: 100px;
            margin-bottom: 1rem;
        }

        .system-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.2rem;
        }

        .system-subtitle {
            font-size: 1.1rem;
            font-weight: 600;
            color: #666;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }

        .btn-signin {
            background-color: #0056b3;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            transition: 0.3s;
        }

        .btn-signin:hover {
            background-color: #004494;
        }

        .footer-note {
            font-size: 0.85rem;
            color: #888;
            margin-top: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <img src="./image/bepo.png" alt="Logo" class="logo-img">
    
    <div class="system-title text-uppercase">Procurement Data System</div>
    <div class="system-subtitle">BEPO-PESO</div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger py-2 mb-3" style="font-size: 0.85rem;">
            Invalid ID Number or Password.
        </div>
    <?php endif; ?>
    
    <form action="login_auth.php" method="POST">
        <div class="mb-3 text-start">
            <label for="id_number" class="form-label">ID Number</label>
            <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Enter your ID Number" required>
        </div>

        <div class="mb-4 text-start">
            <label for="password" class="form-label">Password</label>
            <div class="password-container">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                <i class="bi bi-eye toggle-password" id="togglePassword"></i>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-signin text-uppercase">Sign in</button>
    </form>

    <div class="footer-note">
        <p class="mb-0">Bepo-Peso alright Reserved @ 2026</p>
        <small>Standard Procurement Management Portal</small>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>