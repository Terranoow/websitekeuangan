<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "websitekeuangan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$year = $_POST['tahun'];

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to calculate moving averages and forecast for multiple years
$sql = "
WITH quarterly_totals AS (
    -- Calculate total income and total outcome per quarter
    SELECT 
        CONCAT(YEAR(tanggal), '_', 'Q', FLOOR((MONTH(tanggal) - 1) / 3) + 1) AS quarter,
        YEAR(tanggal) AS year,
        nominal,
        'pendapatan' AS table_type
    FROM pendapatan
    WHERE tanggal BETWEEN (SELECT MIN(tanggal) FROM pendapatan) 
                      AND (SELECT MAX(tanggal) FROM pendapatan)

    UNION ALL

    SELECT
        CONCAT(YEAR(tanggal), '_', 'Q', FLOOR((MONTH(tanggal) - 1) / 3) + 1) AS quarter,
        YEAR(tanggal) AS year,
        nominal,
        'pengeluaran' AS table_type
    FROM pengeluaran
    WHERE tanggal BETWEEN (SELECT MIN(tanggal) FROM pengeluaran) 
                      AND (SELECT MAX(tanggal) FROM pengeluaran)
),
quarterly_income_outcome AS (
    -- Calculate total income and outcome per quarter
    SELECT 
        quarter, 
        year,
        SUM(CASE WHEN table_type = 'pendapatan' THEN nominal ELSE 0 END) AS income,
        SUM(CASE WHEN table_type = 'pengeluaran' THEN nominal ELSE 0 END) AS outcome
    FROM quarterly_totals
    GROUP BY year, quarter
),
last_year_profit AS (
    SELECT 
        quarter, 
        year,
        SUM(CASE WHEN table_type = 'pendapatan' THEN nominal ELSE 0 END) AS income,
        SUM(CASE WHEN table_type = 'pengeluaran' THEN nominal ELSE 0 END) AS outcome
    FROM quarterly_totals
    WHERE year = $year
    GROUP BY year
),
moving_averages AS (
    -- Calculate 4-period moving average for income and outcome
    SELECT 
        quarter,
        year,
        income,
        outcome,
        -- 4-quarter moving average for income
        AVG(income) OVER (ORDER BY year, quarter ROWS BETWEEN 3 PRECEDING AND CURRENT ROW) AS ma_income,
        -- 4-quarter moving average for outcome
        AVG(outcome) OVER (ORDER BY year, quarter ROWS BETWEEN 3 PRECEDING AND CURRENT ROW) AS ma_outcome
    FROM quarterly_income_outcome
)
SELECT 
    $year + 1 AS forecast_year,
    ma.ma_income AS forecast_income,
    ma.ma_outcome AS forecast_outcome,
    ls.income AS last_year_income,
    ls.outcome AS last_year_outcome,
(ma.ma_income - ma.ma_outcome) AS forecast_profit,  -- forecast_profit
    (ls.income - ls.outcome) AS last_year_profit,      -- last_year_profit
    CASE 
        WHEN (ls.income - ls.outcome) != 0 THEN 
            ((ma.ma_income - ma.ma_outcome) - (ls.income - ls.outcome)) / (ls.income - ls.outcome) * 100
        ELSE 
            NULL
    END AS growth_percentage  -- growth_percentage
FROM moving_averages ma
INNER JOIN last_year_profit ls ON ls.quarter = ma.quarter
WHERE ma.year = $year 
ORDER BY ma.quarter desc
LIMIT 1
";

// Execute the query
$result = $conn->query($sql);

// Initialize arrays for data
$years = [];
$income = [];
$outcome = [];
$forProfit = [];
$lastProfit = [];
$growth =[];

// Fetch data and populate arrays
while($row = $result->fetch_assoc()) {
    $years[] = $row['forecast_year'];
    $income[] = $row['forecast_income'];
    $outcome[] = $row['forecast_outcome'];
    $forProfit[] = $row['forecast_profit'];
    $lastProfit[] = $row['last_year_profit'];
    $growth[] = $row['growth_percentage'];
}

// Close connection
$conn->close();

// Prepare the data as a JSON response
$data = [
    'years' => $years,
    'income' => $income,
    'outcome' => $outcome,
    'forProfit' => $forProfit,
    'lastProfit' => $lastProfit,
    'growth' => $growth
];

// Output data as JSON
echo json_encode($data);
?>
