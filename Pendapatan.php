<?php
require("dbconfig.php");
require("pendapatanData.php");  // Include the Pendapatan class

// Create Pendapatan object
$pendapatan = new pendapatanData($servername, $username, $password, $dbname);

// Insert Pendapatan if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tombolsubmit'])) {
    $nominal = $_POST['nominal'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['transaksi'];
    
    $success = $pendapatan->insertPendapatan($nominal, $tanggal, $keterangan);
}

// Retrieve Pendapatan records
$pendapatanRecords = $pendapatan->getPendapatan();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pendapatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            color: #f44336;
            font-size: 18px;
            text-align: center;
            padding: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .form-control, .btn-submit {
            padding: 12px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .alert-success {
            color: green;
            font-size: 16px;
            text-align: center;
        }

        .alert-danger {
            color: red;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Form to Add Pendapatan -->
        <div class="card">
            <div class="card-header">
                <h3>Tambah Pendapatan</h3>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="nominal">Nominal</label>
                        <input type="number" name="nominal" class="form-control" placeholder="Nominal" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="transaksi">Keterangan</label>
                        <input type="text" name="transaksi" class="form-control" placeholder="Keterangan" required>
                    </div>

                    <button type="submit" name="tombolsubmit" class="btn-submit w-100">Simpan</button>
                </form>
            </div>
        </div>

        <!-- Display Success Message -->
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">
                Data pendapatan berhasil disimpan!
            </div>
        <?php endif; ?>

        <!-- Display Pendapatan Table -->
        <div class="table-container">
            <?php
            if ($pendapatanRecords) {
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>ID Pendapatan</th><th>Nominal</th><th>Tanggal</th><th>Keterangan</th></tr></thead>";
                echo "<tbody>";

                while ($row = $pendapatanRecords->fetch_assoc()) {
                    echo "<tr><td>" . $row["idpendapatan"] . "</td><td>" . number_format($row["nominal"], 0, ',', '.') . "</td><td>" . date("d-m-Y", strtotime($row["tanggal"])) . "</td><td>" . $row["keterangan"] . "</td></tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p class='no-data'>Data tidak ditemukan</p>";
            }
            ?>
        </div>
    </div>

    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; Your Website 2023</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
