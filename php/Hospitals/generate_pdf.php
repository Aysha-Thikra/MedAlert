<?php
require('../libs/fpdf186/fpdf.php'); // Include the FPDF library

// Database connection
include('../db_connection.php');

// Create instance of the PDF class
$pdf = new FPDF('P', 'mm', 'A4'); // 'P' for Portrait, 'mm' for millimeters, 'A4' for A4 paper size
$pdf->AddPage();

// Insert Company Logo
$pdf->Image('../../Images/logo.png', 10, 10, 50); // Adjust the path and dimensions as needed
$pdf->Ln(20); // Line break after logo

// Set title
$pdf->SetFont('Arial', 'B', 24);
$pdf->SetTextColor(127, 1, 1); // Dred color for title
$pdf->Cell(0, 20, 'MedAlert Database Report', 0, 1, 'C');
$pdf->Ln(15); // Line break

// Add header
$pdf->SetFont('Arial', 'I', 16);
$pdf->SetTextColor(215, 0, 4); // D2red color for header
$pdf->Cell(0, 15, 'Generated on: ' . date('F j, Y, g:i a'), 0, 1, 'C');
$pdf->Ln(15); // Line break

// Function to create a table header
function tableHeader($pdf, $headers) {
    $pdf->SetFillColor(127, 1, 1); // Dred background for header
    $pdf->SetTextColor(255, 255, 255); // White text color
    $pdf->SetFont('Arial', 'B', 12);

    foreach ($headers as $header) {
        $pdf->Cell(40, 10, $header, 1, 0, 'C', true);
    }
    $pdf->Ln();
}

// Function to create a table row
function tableRow($pdf, $data) {
    $pdf->SetTextColor(0, 0, 0); // Black text color
    $pdf->SetFont('Arial', '', 12);

    foreach ($data as $cell) {
        $pdf->Cell(40, 10, $cell, 1);
    }
    $pdf->Ln();
}

// Fetch and display totals
$totalDonors = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM donors"))['total'];
$totalCampaigners = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM campaigners"))['total'];
$totalHospitals = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM hospitals WHERE userLevel = 1"))['total'];

// Display totals in a table
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(127, 1, 1); // Dred color for section headers
$pdf->Cell(0, 10, 'Totals:', 0, 1, 'L');
$pdf->Ln(10); // Line break

$tableHeaders = ['Type', 'Total'];
tableHeader($pdf, $tableHeaders);
tableRow($pdf, ['Donors', $totalDonors]);
tableRow($pdf, ['Campaigners', $totalCampaigners]);
tableRow($pdf, ['Hospitals', $totalHospitals]);

$pdf->Ln(15); // Line break

// Function to create a card view
function createCard($pdf, $title, $id, $name, $details) {
    $pdf->SetFillColor(127, 1, 1); // Dred background for card header
    $pdf->SetTextColor(255, 255, 255); // White text color
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 15, $title, 0, 1, 'L', true);

    $pdf->SetFillColor(240, 240, 240); // Light gray background for the card body
    $pdf->Rect(10, $pdf->GetY(), 190, 50, 'F'); // Fill rectangle

    $pdf->SetTextColor(0, 0, 0); // Black text color
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'ID: ' . $id, 0, 1, 'L');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Name: ' . $name, 0, 1, 'L');

    // Details
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, 'Details:\n' . $details);
    
    // Line break for spacing between cards
    $pdf->Ln(15); // Increase spacing between cards
}

// Fetch and display Donors Data
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(127, 1, 1); // Dred color for section headers
$pdf->Cell(0, 10, 'Donors:', 0, 1, 'L');
$pdf->Ln(10); // Line break

$donorQuery = "SELECT Donorid, CONCAT(firstName, ' ', lastName) AS fullName, NICnumber, weight, bloodGroup, dateOfBirth, gender, address, personalContact, emergencyContact, email, eligibilityStatus FROM donors";
$donorResult = mysqli_query($db, $donorQuery);
while ($row = mysqli_fetch_assoc($donorResult)) {
    $details = "NIC: {$row['NICnumber']}\nWeight: {$row['weight']} kg\nBlood Group: {$row['bloodGroup']}\nDOB: {$row['dateOfBirth']}\nGender: {$row['gender']}\nAddress: {$row['address']}\nPersonal Contact: {$row['personalContact']}\nEmergency Contact: {$row['emergencyContact']}\nEmail: {$row['email']}\nEligibility: {$row['eligibilityStatus']}";
    createCard($pdf, 'Donor', $row['Donorid'], $row['fullName'], $details);
}

// Fetch and display Hospitals Data
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(127, 1, 1); // Dred color for section headers
$pdf->Cell(0, 10, 'Hospitals:', 0, 1, 'L');
$pdf->Ln(10); // Line break

$hospitalQuery = "SELECT hospitalId, hospitalName, address, contact, email FROM hospitals WHERE userLevel = 1";
$hospitalResult = mysqli_query($db, $hospitalQuery);
while ($row = mysqli_fetch_assoc($hospitalResult)) {
    $details = "Address: {$row['address']}\nContact: {$row['contact']}\nEmail: {$row['email']}";
    createCard($pdf, 'Hospital', $row['hospitalId'], $row['hospitalName'], $details);
}

// Fetch and display Kidney Transplant Recipients Data
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(127, 1, 1); // Dred color for section headers
$pdf->Cell(0, 10, 'Kidney Transplant Recipients:', 0, 1, 'L');
$pdf->Ln(10); // Line break

$recipientQuery = "SELECT kidneyTransplantAdvertisementId, patientName, age, description, contact, email, adBanner FROM kidneytransplantadvertisement";
$recipientResult = mysqli_query($db, $recipientQuery);
while ($row = mysqli_fetch_assoc($recipientResult)) {
    $details = "Age: {$row['age']}\nDescription: {$row['description']}\nContact: {$row['contact']}\nEmail: {$row['email']}";
    createCard($pdf, 'Recipient', $row['kidneyTransplantAdvertisementId'], $row['patientName'], $details);
}

// Add footer
$pdf->SetY(-20);
$pdf->SetFont('Arial', 'I', 12);
$pdf->SetTextColor(128, 128, 128); // Gray color for footer
$pdf->Cell(0, 10, 'Page ' . $pdf->PageNo(), 0, 0, 'C');

// Output the PDF to the browser
$pdf->Output('D', 'Database_Report.pdf');
?>
