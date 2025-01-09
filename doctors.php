<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];

    // Persiapkan query untuk menambahkan dokter
    $sql = "INSERT INTO doctors (name, specialization, phone) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Cek apakah query berhasil disiapkan
    if ($stmt === false) {
        die('Error preparing query: ' . $conn->error);
    }

    // Bind parameter ke query
    $stmt->bind_param("sss", $name, $specialization, $phone);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<div class='success-message'>Dokter berhasil ditambahkan!</div>";
    } else {
        echo "<div class='error-message'>Error: " . $stmt->error . "</div>";
    }

    // Tutup statement
    $stmt->close();
}

// Menampilkan daftar dokter yang ada di database
$sql = "SELECT * FROM doctors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color:  #003d82;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px  #003d82;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"], input[type="number"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .table-container {
            margin-top: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .success-message {
            color: green;
            text-align: center;
            margin: 10px 0;
        }

        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
        header {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
        }

        nav {
            background-color:  #003d82;
            padding: 10px 0;
            text-align: center;
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
<header>
    <h1>Tambah Dokter</h1>
</header>
<header>
<nav>
    <a href="dashboard.php">Dashboard</a>
    
    <a href="doctors.php">Data Dokter</a>
    <a href="logout.php">Logout</a>
</nav>
</header>
<div class="container">
    <h2>Form Tambah Dokter</h2>
    <form method="POST" action="">
        <label for="name">Nama Dokter:</label>
        <input type="text" id="name" name="name" required>

        <label for="specialization">Spesialisasi:</label>
        <input type="text" id="specialization" name="specialization" required>

        <label for="phone">Telepon:</label>
        <input type="text" id="phone" name="phone" required>

        <button type="submit">Tambah Dokter</button>
    </form>

    <div class="table-container">
        <h3>Daftar Dokter</h3>
        <?php
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Spesialisasi</th>
                        <th>Telepon</th>
                        <th>Dibuat Pada</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['specialization']) . "</td>
                        <td>" . htmlspecialchars($row['phone']) . "</td>
                        <td>" . $row['created_at'] . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada data dokter.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
