<?php 
session_start(); // Required to access logged-in user data
include 'db.php'; 

// 1. Fetch User Data from Database
$display_name = "User"; // Default fallback
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT first_name, middle_initial, last_name, nickname FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($user) {
        $full_name = $user->first_name . " " . (!empty($user->middle_initial) ? $user->middle_initial . ". " : "") . $user->last_name;
        $display_name = !empty($user->nickname) ? $full_name . " (" . $user->nickname . ")" : $full_name;
    }
}

// Time-based Greeting Logic
date_default_timezone_set('Asia/Manila');
$hour = date('H');
if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Procurement Data System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root { --pds-green: #198754; --pds-dark: #1a1d20; }
        body { 
            background-color: #f4f7f6; 
            font-family: 'Inter', sans-serif;
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }

        /* --- NEW HERO V2 STYLE --- */
        .hero-section { 
            background: #ffffff; 
            border-radius: 30px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            padding: 4rem 2rem;
        }

        .hero-title {
            font-weight: 800;
            color: var(--pds-dark);
            letter-spacing: -1px;
            line-height: 1.2;
            margin-bottom: 2rem;
        }

        .frame-v2 {
            position: relative;
            display: inline-block;
            padding: 12px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .frame-v2:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 60px rgba(25, 135, 84, 0.15);
        }

        .hero-image {
            border-radius: 18px;
            max-height: 480px;
            width: 100%;
            object-fit: cover;
        }

        .badge-pds {
            background: rgba(25, 135, 84, 0.1);
            color: var(--pds-green);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 1rem;
        }

        /* --- MAINTAINED ORIGINAL FOOTER STYLE --- */
        .footer-custom {
            background-color: #1a1d20; 
            color: white;
            padding: 1.5rem 0;
            border-top: 3px solid #198754; 
            margin-top: auto;
        }
        .footer-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0;
            letter-spacing: 0.5px;
        }
        .footer-subtitle {
            color: #198754 !important; /* Green Subtitle */
            font-size: 0.85rem;
            margin-bottom: 0;
        }
        .footer-link-icon {
            color: #e0e0e0;
            font-size: 1.1rem;
            margin: 0 5px;
            padding: 5px 10px;
            border: 1px solid #444;
            border-radius: 4px;
            transition: 0.3s ease;
            display: inline-block;
        }
        .footer-link-icon:hover {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }
        .footer-label {
            font-size: 0.8rem;
            color: #bbb;
            text-transform: none;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="mb-5">
        <span class="badge-pds text-uppercase">System Overview</span>
        <h2 class="fw-light text-secondary"><?php echo $greeting; ?>, <span class="fw-bold text-dark text-uppercase"><?php echo htmlspecialchars($display_name); ?>!</span></h2>
        <p class="text-muted">Welcome back to the BEPO-PESO Centralized Procurement Portal.</p>
    </div>

    <div class="hero-section text-center mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h3 class="hero-title">
                    Bohol Employment and Placement Office <br>
                    <span class="text-success">&</span> Public Employment Services Office
                </h3>
                
                <div class="frame-v2">
                    <img src="./image/family.jpg" class="img-fluid hero-image" alt="Bepo-Peso Family">
                </div>

                <div class="mt-4 pt-2">
                    <hr class="w-25 mx-auto opacity-10">
                    <p class="text-muted small">
                        <i class="bi bi-people-fill me-1"></i> Dedicated to Public Service and Excellence
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="footer-custom">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center text-md-start">
                <p class="footer-title">PROCUREMENT DATA SYSTEM</p>
            </div>

            <div class="col-md-6 text-center">
                <span class="footer-label me-2">Social Media:</span>
                <a href="#" class="footer-link-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="mailto:official@bepo-peso.gov" class="footer-link-icon"><i class="fas fa-envelope"></i></a>
                
                <span class="footer-label ms-3 me-2">Contact to developer:</span>
                <a href="mailto:dev@example.com" class="footer-link-icon"><i class="bi bi-envelope-paper"></i></a>
            </div>

            <div class="col-md-3 text-center text-md-end mt-3 mt-md-0">
                <p class="footer-subtitle fw-bold">Bepo-Peso alright Reserved @ 2026</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>