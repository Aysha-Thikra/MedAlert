<?php
require '../db_connection.php';

if (isset($_GET['kidneyTransplantAdvertisementId'])) {
    $kidneyTransplantAdvertisementId = $_GET['kidneyTransplantAdvertisementId'];

    $sql = "DELETE FROM kidneytransplantadvertisement WHERE kidneyTransplantAdvertisementId=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $kidneyTransplantAdvertisementId);

    if ($stmt->execute()) {
        echo "<script>
                alert('Advertisement deleted successfully');
                window.location.href='../../php/Hospitals/HospitalDashBoard.php';
              </script>";
    } else {
        echo "Error deleting record: " . $db->error;
    }

    $stmt->close();
} else {
    echo "No advertisement ID provided.";
}

$db->close();
?>
