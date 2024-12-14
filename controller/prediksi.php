<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "websitekeuangan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query
$sql = "
SELECT 
    quarter, 
    year,
    SUM(CASE WHEN t.table_type = 'pendapatan' THEN nominal ELSE 0 END) AS income,
    SUM(CASE WHEN t.table_type = 'pengeluaran' THEN nominal ELSE 0 END) AS outcome,
    SUM(CASE WHEN t.table_type = 'pendapatan' THEN nominal ELSE 0 END) OVER (PARTITION BY year) AS total_income_per_year,
    SUM(CASE WHEN t.table_type = 'pengeluaran' THEN nominal ELSE 0 END) OVER (PARTITION BY year) AS total_outcome_per_year,
    (SUM(CASE WHEN t.table_type = 'pendapatan' THEN nominal ELSE 0 END) OVER (PARTITION BY year) / 4) AS income_sma,
    (SUM(CASE WHEN t.table_type = 'pengeluaran' THEN nominal ELSE 0 END) OVER (PARTITION BY year) / 4) AS outcome_sma
FROM (
    SELECT
        CONCAT(YEAR(tanggal),'_' ,'Q', FLOOR((MONTH(tanggal) - 1) / 3) + 1) AS quarter,
        YEAR(tanggal) AS year,
        nominal,
        'pendapatan' AS table_type
    FROM pendapatan
    WHERE tanggal BETWEEN (SELECT MIN(tanggal) FROM pendapatan) 
                      AND (SELECT MAX(tanggal) FROM pendapatan)

    UNION ALL

    SELECT
        CONCAT(YEAR(tanggal), '_','Q', FLOOR((MONTH(tanggal) - 1) / 3) + 1) AS quarter,
        YEAR(tanggal) AS year,
        nominal,
        'pengeluaran' AS table_type
    FROM pengeluaran
    WHERE tanggal BETWEEN (SELECT MIN(tanggal) FROM pengeluaran) 
                      AND (SELECT MAX(tanggal) FROM pengeluaran)
) t
GROUP BY year, quarter
ORDER BY year, quarter;
";

// Execute the query
$result = $conn->query($sql);

// Initialize arrays for data
$quarters = [];
$income = [];
$outcome = [];
$income_sma = [];
$outcome_sma = [];

// Fetch data and populate arrays
while($row = $result->fetch_assoc()) {
    $quarters[] = $row['quarter'];
    $income[] = $row['income'];
    $outcome[] = $row['outcome'];
    $income_sma[] = $row['income_sma'];
    $outcome_sma[] = $row['outcome_sma'];
}

// Close connection
$conn->close();

// Prepare the data as a JSON response
$data = [
    'quarters' => $quarters,
    'income' => $income,
    'outcome' => $outcome,
    'income_sma' => $income_sma,
    'outcome_sma' => $outcome_sma
];

// Output data as JSON
echo json_encode($data);
?>
