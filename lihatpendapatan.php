<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Forecast</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

   <h1 style="text-align:center;">Financial Forecast</h1>

    <!-- Dropdown for selecting the starting year -->
    <div style="text-align:center;">
        <label for="year">Select Year: </label>
        <select id="year" name="year">
            <!-- You can dynamically populate years if needed -->
            <option value="2024">2025</option>
            <option value="2024">2024</option>
             <option value="2023">2023</option>
            <option value="2022">2022</option>
            <!-- Add more years as needed -->
        </select>
        <button id="loadData">Load Forecast</button>
    </div>

    <!-- Table to display the forecast data -->
    <table id="forecastTable">
        <thead>
            <tr>
                <th>Year</th>
                <th>Income Forecast</th>
                <th>Outcome Forecast</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#loadData').click(function() {
                // Get selected year from the dropdown
                var selectedYear = $('#year').val();

                // Make an AJAX request to the PHP script
                $.ajax({
                    url: 'controller/movingAverageResult.php',  // Replace with the actual path to your PHP script
                    type: 'POST',
                    data: { tahun: selectedYear },  // Send selected year as POST data
                    dataType: 'json',
                    success: function(data) {
                        // Clear the existing table rows
                        $('#forecastTable tbody').empty();

                        // Loop through the data and populate the table
                        if (data && data.years.length > 0) {
                            for (var i = 0; i < data.years.length; i++) {
                                var year = data.years[i];
                                var income = data.income[i];
                                var outcome = data.outcome[i];

                                // Add a new row to the table
                                $('#forecastTable tbody').append(
                                    '<tr>' +
                                        '<td>' + year + '</td>' +
                                        '<td>' + income + '</td>' +
                                        '<td>' + outcome + '</td>' +
                                    '</tr>'
                                );
                            }
                        } else {
                            $('#forecastTable tbody').append(
                                '<tr><td colspan="3">No data available for the selected year.</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        alert('An error occurred while fetching the data.');
                    }
                });
            });
        });
    </script>

</body>
</html>
