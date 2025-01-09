<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Klinik</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        nav {
            background-color: #0056b3;
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #003d82;
        }

        .image-section {
            text-align: center;
            margin: 20px 0;
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .container h2 {
            text-align: center;
            color: #0056b3;
        }
    </style>
</head>
<body>
<header>
    <h1>Selamat Datang di Klinik Abel</h1>
</header>
<nav>
    <a href="patients.php">Data Pasien</a>
    <a href="appointments.php">Janji Temu</a>
    <a href="doctors.php">Data Dokter</a>
    <a href="logout.php">Logout</a>
</nav>
<div class="image-section">
    <img src="gambar.jpg" alt="Gambar Klinik">
</div>

</body>
</html>
