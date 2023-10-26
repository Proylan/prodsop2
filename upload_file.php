<?php
// Check if the subfolder parameter is set
if (isset($_GET['subfolder'])) {
    $subfolder = $_GET['subfolder'];

    // Directory where uploaded files will be stored
    $uploadDir = 'C:/xampp/htdocs/prodsop2/' . $subfolder . '/';

    // Create the directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Check if the uploaded file is a PDF
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (strtolower($fileExtension) !== 'pdf') {
            echo "Only PDF files are allowed for upload.";
        } else {
            $originalFileName = $file['name'];
            $tempFilePath = $file['tmp_name'];

            $destination = $uploadDir . $originalFileName;

            // Check if the file already exists
            if (file_exists($destination)) {
                echo "A file with the same name already exists. Please rename the file and try again.";
            } else {
                if (move_uploaded_file($tempFilePath, $destination)) {
                    echo "File uploaded successfully.";
                } else {
                    echo "Error uploading file.";
                }
            }
        }
    } else {
        echo "No file uploaded.";
    }
} else {
    echo "Subfolder not specified.";
}
?>
