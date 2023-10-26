<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tanggapin ang file path mula sa POST data
    $pdfFile = $_POST['pdfFile'];

    // I-validate ang file path para sa seguridad
    // Maaring idagdag ang mga karagdagang security checks dito

    if (deletePDFFile($pdfFile)) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'error' => 'Unable to delete PDF file.'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Function para sa pag-delete ng PDF file
function deletePDFFile($pdfFile) {
    if (file_exists($pdfFile)) {
        if (unlink($pdfFile)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
?>
