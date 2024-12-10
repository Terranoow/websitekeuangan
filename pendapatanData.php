<?php
class pendapatanData {
    private $conn;
    
    // Constructor: Establish the database connection
    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    // Method to insert pendapatan
    public function insertPendapatan($nominal, $tanggal, $keterangan) {
        $stmt = $this->conn->prepare("INSERT INTO pendapatan (nominal, tanggal, keterangan) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $nominal, $tanggal, $keterangan);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Method to get all pendapatan records
    public function getPendapatan() {
        $sql = "SELECT * FROM pendapatan";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    // Destructor: Close the connection
    public function __destruct() {
        $this->conn->close();
    }
}
?>
