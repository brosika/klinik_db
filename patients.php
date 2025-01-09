<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$message = "";

// Proses penambahan data pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $age = (int)$_POST['age'];
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);

    if (!empty($name) && !empty($age)) {
        $stmt = $conn->prepare("INSERT INTO patients (name, age, address, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $age, $address, $phone);

        if ($stmt->execute()) {
            $message = "Pasien berhasil ditambahkan!";
        } else {
            $message = "Terjadi kesalahan: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "Nama dan umur harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pasien</title>
    <style>
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

        .container {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            color: #0056b3;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .message {
            text-align: center;
            font-size: 1.2em;
            color: green;
            margin-bottom: 20px;
        }

        .error {
            text-align: center;
            font-size: 1.2em;
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header>
    <h1>Data Pasien</h1>
</header>
<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="patients.php">Data Pasien</a>
    <a href="logout.php">Logout</a>
</nav>
<div class="container">
    <h2>Form Tambah Pasien</h2>
    <?php if (!empty($message)) { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <label for="name">Nama:</label>
        <input type="text" id="name" name="name" required>

        <label for="age">Umur:</label>
        <input type="number" id="age" name="age" required>

        <label for="address">Alamat:</label>
        <textarea id="address" name="address"></textarea>

        <label for="phone">Telepon:</label>
        <input type="text" id="phone" name="phone">

        <button type="submit">Tambah Pasien</button>
    </form>
    <h2>Daftar Pasien</h2>
    <?php
    // Menampilkan data pasien
    $query = "SELECT * FROM patients";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Dibuat Pada</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . $row['age'] . "</td>
                    <td>" . htmlspecialchars($row['address']) . "</td>
                    <td>" . $row['phone'] . "</td>
                    <td>" . $row['created_at'] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada data pasien.</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
