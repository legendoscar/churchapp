<?php
header('Content-Type: application/json');

require "../includes/db-config.php";
$sqlQuery = "SELECT report_id, `year`, amount FROM weekly_chart_reports";
$result = mysqli_query($conn, $sqlQuery);

$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

mysqli_close($conn);

echo json_encode($data);
?>