<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$message = "";

// Proses penambahan janji temu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = (int)$_POST['patient_id'];
    $doctor_name = htmlspecialchars($_POST['doctor_name']);
    $appointment_date = htmlspecialchars($_POST['appointment_date']);
    $status = htmlspecialchars($_POST['status']);

    if (!empty($patient_id) && !empty($doctor_name) && !empty($appointment_date)) {
        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $patient_id, $doctor_name, $appointment_date, $status);

        if ($stmt->execute()) {
            $message = "Janji temu berhasil ditambahkan!";
        } else {
            $message = "Terjadi kesalahan: " . $conn->error;
        }

        $stmt->close();
    } else {
        $message = "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Janji Temu</title>
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

        nav {
            background-color: #003d82;
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #007bff;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px  #007bff;
        }

        h2 {
            text-align: center;
            color:   #003d82;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="datetime-local"], select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
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

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color:  #003d82;
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
    </style>
</head>
<body>

<header>
    <h1>Janji Temu</h1>
</header>

<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="appointments.php">Janji Temu</a>
    <a href="logout.php">Logout</a>
</nav>

<div class="container">
    <h2>Form Tambah Janji Temu</h2>
    <?php if (!empty($message)) { 
        echo "<p class='" . (strpos($message, 'berhasil') !== false ? "success-message" : "error-message") . "'>$message</p>"; 
    } ?>

    <form method="POST" action="">
        <label for="patient_id">ID Pasien:</label>
        <select id="patient_id" name="patient_id" required>
            <option value="">Pilih Pasien</option>
            <?php
            // Mengambil data pasien untuk dropdown
            $patient_query = "SELECT id, name FROM patients";
            $patients = $conn->query($patient_query);
            while ($row = $patients->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . " (ID: " . $row['id'] . ")</option>";
            }
            ?>
        </select>

        <label for="doctor_name">Nama Dokter:</label>
        <input type="text" id="doctor_name" name="doctor_name" required>

        <label for="appointment_date">Tanggal dan Waktu Janji:</label>
        <input type="datetime-local" id="appointment_date" name="appointment_date" required>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="scheduled">Scheduled</option>
            <option value="completed">Completed</option>
            <option value="canceled">Canceled</option>
        </select>

        <button type="submit">Tambah Janji Temu</button>
    </form>

    <h2>Daftar Janji Temu</h2>
    <?php
    // Menampilkan data janji temu
    $query = "
        SELECT a.id, p.name AS patient_name, a.doctor_name, a.appointment_date, a.status
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        ORDER BY a.appointment_date DESC
    ";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>ID</th>
                <th>Nama Pasien</th>
                <th>Nama Dokter</th>
                <th>Tanggal dan Waktu</th>
                <th>Status</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . htmlspecialchars($row['patient_name']) . "</td>
                    <td>" . htmlspecialchars($row['doctor_name']) . "</td>
                    <td>" . $row['appointment_date'] . "</td>
                    <td>" . ucfirst($row['status']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Tidak ada janji temu.</p>";
    }

    $conn->close();
    ?>
</div>

</body>
</html>
