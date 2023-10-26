<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folderPath = $_POST['folderPath'];

  

    // Perform folder deletion
    if (deleteFolderRecursive($folderPath)) {
        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'error' => 'Unable to delete folder.'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Recursive function to delete a folder and its contents
function deleteFolderRecursive($folderPath) {
    if (is_dir($folderPath)) {
        $files = array_diff(scandir($folderPath), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir($folderPath . '/' . $file)) {
                deleteFolderRecursive($folderPath . '/' . $file);
            } else {
                unlink($folderPath . '/' . $file);
            }
        }
        rmdir($folderPath);
        return true;
    } elseif (is_file($folderPath)) {
        unlink($folderPath);
        return true;
    }
    return false;
}
?>
