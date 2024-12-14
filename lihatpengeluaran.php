<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengeluaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        td {
            border-bottom: 1px solid #ddd;
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

    </style>
</head>
<body>

<div class="container">
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
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<thead><tr><th>ID Pengeluaran</th><th>Nominal</th><th>Tanggal</th><th>Keterangan</th></tr></thead>";
        echo "<tbody>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["idpengeluaran"]. "</td><td>".$row["nominal"]. "</td><td>" . $row["tanggal"]. "</td><td>" . $row["keterangan"]. "</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    } else {
        echo "<p class='no-data'>Data tidak ditemukan</p>";
    }

    mysqli_close($conn);
    ?>
</div>

</body>
</html>
