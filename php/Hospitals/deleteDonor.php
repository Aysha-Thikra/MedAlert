<?php
require '../db_connection.php';

if (isset($_GET['donorId'])) {
    $donorId = $_GET['donorId'];

    $sql = "DELETE FROM donors WHERE Donorid=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $donorId);

    if ($stmt->execute()) {
        echo "<script>
                alert('Donor deleted successfully');
                window.location.href='../../php/Hospitals/HospitalDashBoard.php';
              </script>";
    } else {
        echo "Error deleting record: " . $db->error;
    }

    $stmt->close();
} else {
    echo "No donor ID provided.";
}

$db->close();
?>
