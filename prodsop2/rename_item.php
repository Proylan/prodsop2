<?php
// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if the necessary POST data is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['path']) && isset($_POST['newName'])) {
    $path = sanitizeInput($_POST['path']);
    $newName = sanitizeInput($_POST['newName']);

    // Ensure that the path is valid and the item exists
    if (file_exists($path)) {
        // Get the item's directory and check if it's a folder or a file
        $itemDir = dirname($path);
        $isFolder = is_dir($path);

        // Construct the new path with the desired name
        $newPath = $itemDir . DIRECTORY_SEPARATOR . $newName;

        // Check if the new name is not empty and doesn't contain invalid characters
        if (!empty($newName) && preg_match('/^[a-zA-Z0-9._-]+$/', $newName)) {
            // Attempt to rename the folder or file
            if (rename($path, $newPath)) {
                // Return a success response
                $response = array('success' => true);
                echo json_encode($response);
                
                exit;
            } else {
                
                $response = array('success' => false, 'error' => 'Failed to rename the item.');
                echo json_encode($response);
                exit;
            }
        } else {
            
            $response = array('success' => false, 'error' => 'Invalid new name.');
            echo json_encode($response);
            exit;
        }
    } else {
        
        $response = array('success' => false, 'error' => 'Item does not exist.');
        echo json_encode($response);
        exit;
    }
} else {
    
    $response = array('success' => false, 'error' => 'Invalid request.');
    echo json_encode($response);
    exit;
}
?>
