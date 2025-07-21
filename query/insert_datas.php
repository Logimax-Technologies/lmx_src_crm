<?php
session_start();

$old_db = $_SESSION['old_db'] ?? ['host' => '', 'username' => '', 'password' => '', 'database' => ''];
$new_db = $_SESSION['new_db'] ?? ['host' => '', 'username' => '', 'password' => '', 'database' => ''];

$old_db_connection = new mysqli($old_db['host'], $old_db['username'], $old_db['password'], $old_db['database']);
if ($old_db_connection->connect_error) {
    die("Connection failed to old database: " . $old_db_connection->connect_error);
}

$new_db_connection = new mysqli($new_db['host'], $new_db['username'], $new_db['password'], $new_db['database']);
if ($new_db_connection->connect_error) {
    die("Connection failed to new database: " . $new_db_connection->connect_error);
}

$tables = [];
$res = $old_db_connection->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

$insert_queries = [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <div class="btn btn-primary" onclick="window.location.href='config.php'" style="width:100%;margin-bottom:5px">
        Home
    </div>
    <title>Data Migration (Insert Select Queries)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search filter
            document.getElementById('tableSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('table tbody tr');
                rows.forEach(row => {
                    const tableName = row.children[0].textContent.toLowerCase();
                    row.style.display = tableName.includes(searchTerm) ? '' : 'none';
                });
            });

            // Handle checkbox change to regenerate queries
            document.getElementById('withTruncate').addEventListener('change', function() {
                const shouldTruncate = this.checked;
                document.querySelectorAll('tr[data-query]').forEach(tr => {
                    const baseQuery = tr.getAttribute('data-query');
                    const finalQuery = shouldTruncate ?
                        `TRUNCATE TABLE ${tr.dataset.target};\n\n${baseQuery}` :
                        baseQuery;

                    tr.querySelector('code').textContent = finalQuery;
                    tr.querySelector('button').setAttribute('onclick', `copyToClipboard(${JSON.stringify(finalQuery)})`);
                });

                // Update hidden textarea with all queries
                let allQueries = [];
                document.querySelectorAll('tr[data-query]').forEach(tr => {
                    const baseQuery = tr.getAttribute('data-query');
                    const finalQuery = this.checked ?
                        `TRUNCATE TABLE ${tr.dataset.target};\n${baseQuery}` :
                        baseQuery;
                    allQueries.push(finalQuery);
                });
                document.getElementById('allQueries').value = allQueries.join("\n\n");
            });
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert("Query copied to clipboard!");
            });
        }

        function copyAllQueries() {
            const allQueries = document.getElementById("allQueries").value;
            navigator.clipboard.writeText(allQueries).then(() => {
                alert("All queries copied to clipboard!");
            });
        }
    </script>

</head>

<body class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Migration</h3>
        <div class="p-2 rounded d-inline-flex align-items-center bg-danger text-white">
            <input class="form-check-input me-2" type="checkbox" id="withTruncate" style="accent-color: white;">
            <label class="form-check-label mb-0" for="withTruncate">With Truncate?</label>
        </div>


    </div>

    <div class="mb-3">
        <input type="text" id="tableSearch" class="form-control" placeholder="Search table name...">
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Table</th>
                <th>Insert Select Query</th>
                <th>Copy</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($tables as $table) {
                $res_old = $old_db_connection->query("SHOW COLUMNS FROM `$table`");
                $columns = [];
                while ($row = $res_old->fetch_assoc()) {
                    $columns[] = $row['Field'];
                }

                $column_names = "`" . implode("`, `", $columns) . "`";

                $insert_query = "INSERT INTO $new_db[database].`$table` ($column_names) SELECT $column_names FROM `$old_db[database]`.`$table`;";
                $insert_queries[] = $insert_query;

                // Escape for JS
                $escaped_query = htmlspecialchars($insert_query, ENT_QUOTES);
                $target_table = "$new_db[database].`$table`";

                echo "<tr data-query=\"$escaped_query\" data-target=\"$target_table\">
            <td>$table</td>
            <td><code>$insert_query</code></td>
            <td><button class='btn btn-sm btn-primary' onclick='copyToClipboard(\"$escaped_query\")'>Copy</button></td>
        </tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="mb-3">
        <button class="btn btn-success" onclick="copyAllQueries()">Copy All Queries</button>
    </div>

    <textarea id="allQueries" class="form-control d-none" rows="10"><?php
                                                                    echo htmlspecialchars(implode("\n\n", $insert_queries));
                                                                    ?></textarea>

</body>

</html>
<?php
$old_db_connection->close();
$new_db_connection->close();
?>