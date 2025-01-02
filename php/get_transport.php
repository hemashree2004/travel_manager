<?php
include 'database.php';

$destinationID = $_GET['destination_id'];

$query = $conn->prepare("
    SELECT TransportID, TransportMode, Cost
    FROM Transportation
    WHERE DestinationID = :destinationID
");
$query->execute([':destinationID' => $destinationID]);

echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
?>
