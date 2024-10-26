<?php
session_start();
require_once "config.php";
require('fpdf186/fpdf.php'); 

if (!isset($_SESSION["user"])) {
    header("Location: userlogin.php");
    exit();
}

if (isset($_GET['seemore'])) {
    $serial_no = $_GET['seemore'];   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM borrow_record AS bw, users as u, books as b WHERE bw.SERIAL_NO = '$serial_no' and bw.BOOK_ID = b.BOOK_ID and bw.USER_ID = u.USER_ID AND bw.ACCEPT=1 and bw.RECEIVED=0";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 40);
        $pdf->Cell(0, 10, 'Book Bar', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'U', 16);
        $pdf->Cell(0, 4, 'User Details', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'User Name: ' . $row['NAME'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'User ID: ' . $row['USER_ID'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Email: ' . $row['E_MAIL'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Address: ' . $row['ADDRESS'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Phone Number: ' . $row['PHONE_NUMBER'], 0, 1);
        $pdf->SetFont('Arial', 'U', 16);
        $pdf->Cell(0, 4, 'Book Details', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Book Name: ' . $row['BOOK_NAME'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Book ID: ' . $row['BOOK_ID'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Gener: ' . $row['GENER'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Author: ' . $row['AUTHOR'], 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Accepted Date: ' . $row['ACCEPT_DATE'], 0, 1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(0, 4, 'Note:Collect the book within 24 hours otherwise you will have to send a re-request to borrow the book', 0, 1,);
        $pdf->Output();
    } else {
        echo 'No book found with that serial number!';
    }
    mysqli_close($conn);
} else {
    echo 'Invalid request.';
}
