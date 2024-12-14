<?php
require("dbconfig.php");
require("pengeluaranData.php");  // Include the Pengeluaran class

// Create Pengeluaran object
$pengeluaran = new pengeluaranData($servername, $username, $password, $dbname);

// Insert Pengeluarabn if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tombolsubmit'])) {
    $nominal = $_POST['nominal'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['transaksi'];
    
    $success = $pengeluaran->insertPengeluaran($nominal, $tanggal, $keterangan);
}

// Handle Edit operation
if (isset($_POST['editNominal']) && isset($_POST['editTanggal']) && isset($_POST['editKeterangan']) && isset($_POST['editId'])) {
    $id = $_POST['editId'];
    $nominal = $_POST['editNominal'];
    $tanggal = $_POST['editTanggal'];
    $keterangan = $_POST['editKeterangan'];
    
    // Update Pengeluaranrecord
    $success = $pengeluaran->updatePengeluaran($id, $nominal, $tanggal, $keterangan);
}

// Handle Delete operation
if (isset($_POST['deleteId'])) {
    $id = $_POST['deleteId'];
    
    // Delete Pengeluaran record
    $success = $pengeluaran->deletePengeluaran($id);
}

// Retrieve Pengeluaran records
$pengeluaranRecords = $pengeluaran->getPengeluaran();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            color: #45a049;
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
        <!-- Form to Add Pengeluaran -->
        <div class="card">
            <div class="card-header">
                <h3>Tambah Pengeluaran</h3>
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
                Data pengeluaran berhasil disimpan!
            </div>
        <?php endif; ?>

        <!-- Display Pengeluaran Table -->
        <div class="table-container">
            <?php
            if ($pengeluaranRecords) {
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>ID Pengeluaran</th><th>Jenis</th><th>Nominal</th><th>Tanggal</th><th>Keterangan</th><th>Actions</th></tr></thead>";
                echo "<tbody>";

                while ($row = $pengeluaranRecords->fetch_assoc()) {
                    echo "<tr>
                    <td>" . $row["idpengeluaran"] . "</td>
                    <td>" . $row["jnsPengeluaran"] . "</td>
                    <td>" . number_format($row["nominal"], 0, ',', '.') . "</td>
                    <td>" . date("d-m-Y", strtotime($row["tanggal"])) . "</td>
                    <td>" . $row["keterangan"] . "</td>
                    
                    <!-- Edit Button -->
                    <td>
                        <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' 
                            data-id='" . $row["idpengeluaran"] . "' 
                            data-jnsPengeluaran='" . $row["jnsPengeluaran"] . "'
                            data-nominal='" . $row["nominal"] . "' 
                            data-tanggal='" . $row["tanggal"] . "' 
                            data-keterangan='" . $row["keterangan"] . "'>Edit</button>
                        <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                            data-id='" . $row["idpengeluaran"] . "'>Delete</button>
                    </td>
                    </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p class='no-data'>Data tidak ditemukan</p>";
            }
            ?>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                    <div class="mb-3">
                            <label for="editJenis" class="form-label">Jenis</label>
                            <input type="text" class="form-control" id="editJenis" name="editJenis" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNominal" class="form-label">Nominal</label>
                            <input type="number" class="form-control" id="editNominal" name="editNominal" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editTanggal" name="editTanggal" required>
                        </div>
                        <div class="mb-3">
                            <label for="editKeterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="editKeterangan" name="editKeterangan" required>
                        </div>
                        <input type="hidden" id="editId" name="editId">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this record?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="">
                        <input type="hidden" id="deleteId" name="deleteId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- JavaScript for handling modals -->
    <script>
        // Populate the Edit modal with current data
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const jenis  = button.getAttribute('data-jnsPengeluaran');
            const nominal = button.getAttribute('data-nominal');
            const tanggal = button.getAttribute('data-tanggal');
            const keterangan = button.getAttribute('data-keterangan');

            document.getElementById('editId').value = id;
            document.getElementById('editJenis').value = jenis;
            document.getElementById('editNominal').value = nominal;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editKeterangan').value = keterangan;
        });

        // Populate the Delete modal with the ID to be deleted
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            document.getElementById('deleteId').value = id;
        });
    </script>
</body>
</html>
