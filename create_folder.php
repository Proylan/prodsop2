<?php
// Get the folder name and subfolder parameters
$folderName = $_GET['folderName'];
$subfolder = $_GET['subfolder'];

// Check if both parameters are provided
if ($folderName && $subfolder) {
    $targetDir = 'C:/xampp/htdocs/prodsop2/' . $subfolder;
    $folderPath = $targetDir . '/' . $folderName;

    // Create the folder
    if (!is_dir($folderPath)) {
        if (mkdir($folderPath, 0755, true)) {
            echo 'Folder created successfully.';
           
        } else {
            echo 'Error creating folder.';
        }
    } else {
        echo 'Folder already exists.';
    }
} else {
    echo 'Missing parameters: folderName and/or subfolder.';
}
?>
