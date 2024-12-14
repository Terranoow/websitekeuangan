<?php
require("dbconfig.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $nominal = $_POST['nominal'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("UPDATE pengeluaran SET nominal = ?, tanggal = ?, keterangan = ? WHERE idpengeluaran = ?");
    $stmt->bind_param("issi", $nominal, $tanggal, $keterangan, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
