<?php
require '../db_connection.php';

if (isset($_GET['campaignerId'])) {
    $campaignerId = $_GET['campaignerId'];

    $sql = "DELETE FROM campaigners WHERE campaignersId=?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $campaignerId);

    if ($stmt->execute()) {
        echo "<script>
                alert('Campaigner deleted successfully');
                window.location.href='../../php/Hospitals/HospitalDashBoard.php';
              </script>";
    } else {
        echo "Error deleting record: " . $db->error;
    }

    $stmt->close();
} else {
    echo "No campaigner ID provided.";
}

$db->close();
?>
