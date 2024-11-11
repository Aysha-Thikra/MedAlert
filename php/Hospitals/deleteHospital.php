<?php
require '../db_connection.php';

if (isset($_GET['hospitalId'])) {
    $hospitalId = $_GET['hospitalId'];

    $sql = "DELETE FROM hospitals WHERE hospitalId=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $hospitalId);

    if ($stmt->execute()) {
        echo "<script>
                alert('Hospital deleted successfully');
                window.location.href='../../php/Hospitals/HospitalDashBoard.php';
              </script>";
    } else {
        echo "Error deleting record: " . $db->error;
    }

    $stmt->close();
} else {
    echo "No hospital ID provided.";
}

$db->close();
?>
