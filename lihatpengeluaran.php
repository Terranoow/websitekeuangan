<!doctype html>
<html>
<head>
    <style>
        table,th,td {
            border: 1px solid blue;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<?php
    require("dbconfig.php");

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM pengeluaran";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    echo "<table border=1>";
    echo "<tr><th>ID Pengeluaran</th><th>Nominal</th><th>Tanggal</th><th>Keterangan</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        //echo "id: " . $row["id"]. " - Name: " . $row["nama"]. " " . $row["kelas"]. "<br>";
        echo "<tr><td>" . $row["idpengeluaran"]. "</td><td>".$row["nominal"]. "</td><td>" . $row["tanggal"]. "</td><td>" . $row["keterangan"]. "</td></tr>";
    }
    } else {
    echo "data tidak ditemukan";
    }
    echo "</table>";
    mysqli_close($conn);
?>
</body>
</html>