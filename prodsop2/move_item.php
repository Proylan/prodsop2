<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the JSON data sent from the client
        $data = json_decode(file_get_contents('php://input'));

        if ($data && isset($data->sourceFile) && isset($data->destinationFolder)) {
            $sourceFile = $data->sourceFile;
            $destinationFolder = $data->destinationFolder;

            if ($sourceFile && $destinationFolder) {
                // Perform the move operation
                if (rename($sourceFile, $destinationFolder . '/' . basename($sourceFile))) {
                    // Success: The file/folder has been moved
                    echo json_encode(['message' => 'OK']);
                } else {
                    // Unable to move the file/folder
                    echo json_encode(['error' => 'Unable to move the file/folder']);
                }
            } else {
                // Invalid source file or destination folder
                echo json_encode(['error' => 'Invalid source file or destination folder']);
            }
        } else {
            // Invalid data sent from the client
            echo json_encode(['error' => 'Invalid data sent from the client']);
        }
    } else {
        // Unsupported HTTP method
        echo json_encode(['error' => 'Unsupported HTTP method']);
    }
?>