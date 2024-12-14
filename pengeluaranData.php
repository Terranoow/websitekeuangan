<?php
class pengeluaranData {
    private $conn;
    
    // Constructor: Establish the database connection
    public function __construct($servername, $username, $password, $dbname) {
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    
    // Method to insert pengeluaran
    public function insertPengeluaran($nominal, $tanggal, $keterangan) {
        $stmt = $this->conn->prepare("INSERT INTO pengeluaran (nominal, tanggal, keterangan) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $nominal, $tanggal, $keterangan);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Method to get all pengeluaran records
    public function getPengeluaran() {
        $sql = "SELECT * FROM pengeluaran";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function updatePengeluaran($id, $nominal, $tanggal, $keterangan, $jenis) {
        $sql = "UPDATE pengeluaran SET nominal = ?, tanggal = ?, keterangan = ?, jnsPengeluaran = ? WHERE idpengeluaran = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issi", $nominal, $tanggal, $keterangan, $jenis , $id);
        return $stmt->execute();
    }
    
    // Delete Pengeluaran
    public function deletePengeluaran($id) {
        $sql = "DELETE FROM pengeluaran WHERE idpengeluaran = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Destructor: Close the connection
    public function __destruct() {
        $this->conn->close();
    }
}
?>
