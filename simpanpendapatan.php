<?php
    require('dbconfig.php');

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }

    // ambil data dari form
    $nominal = $_POST['nominal'];
    $tanggal = $_POST['tanggal'];
    $transaksi = $_POST['transaksi'];

    $sql = "INSERT INTO pendapatan (nominal,tanggal,keterangan) VALUES ('" . $nominal . "','" . $tanggal . "','" . $transaksi . "')";

    if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    
     
    }
    
    mysqli_close($conn);

   /* echo '<br><a href="formentrysiswa.html">form isian</a>'; */


?>