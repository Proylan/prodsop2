<?php
// Function to display PDF files in a folder
function displayFilesInFolder($folder) {
    $pdfFiles = glob($folder . '/*.pdf');
    if (!empty($pdfFiles)) {
        foreach ($pdfFiles as $pdfFile) {
            $pdfFileName = pathinfo($pdfFile, PATHINFO_FILENAME); // remove extension

            echo '<li>';
            echo '<a href="javascript:void(0);" class="file-link" draggable="true"  data-file="' . $pdfFile . '" onclick="openPDF(\'' . $pdfFile . '\')" oncontextmenu="showFileContextMenu(event, \'' . $pdfFile . '\', true); return false;">' . $pdfFileName . '</a>';
            echo '</li>';
        }
    } else {
        echo '<li style="margin-top: 10px;"><span style="color: white;">No files in this folder.</span></li>';
    }
}

// Function to generate the folder structure
function generateFolderStructure($path, $subfolderPath = '', $openFolders = false) {
    // Get a list of directories within the specified path
    $directories = array_diff(glob($path . '/*', GLOB_ONLYDIR), array('.', '..'));
    $count = 1;

    $hasContents = false; // Flag to track if the folder has contents

    // button outside subfolder
    if (empty($subfolderPath)) {
        echo '<div class="search-container">
                 <input type="text" id="search-bar" placeholder="Search..." oninput="searchItems(this.value)">
                </div>';
        echo '<div id="search-results"></div>';

        echo '<button class="create-folder-button" data-subfolder="' . $path . '"><i class="fas fa-folder"></i> Create Folder</button>';
    }

    // Loop through each directory found
    foreach ($directories as $dir) {
        // Check if the folder has contents
        $pdfFiles = glob($dir . '/*.pdf');
        $hasContents = !empty($pdfFiles);

        // Output a clickable folder link with its name
        echo '<a path-folder="' . $dir . '" class="nav-link folder-link" href="#" oncontextmenu="showContextMenu(event, \'' . $dir . '\', true); return false;">' . basename($dir) . '</a>';

        echo '<ul class="files" style="' . (($openFolders || $hasContents) ? 'display: block;' : 'display: none;') . '">';
        $count++;

        // Display buttons for creating files and folders
        echo '<li>';
        echo '<button class="create-folder-button" data-subfolder="' . $dir . '"><i class="fas fa-folder"></i></button>';
        echo '<button class="upload-file-button" data-subfolder="' . $dir . '"><i class="fas fa-cloud-upload-alt"></i></button>';
        echo '</li>';

        // Display PDF files in the folder
        displayFilesInFolder($dir);

        // Recursively generate subfolders
        generateFolderStructure($dir, true, ($openFolders || $hasContents)); // Call the function recursively with the updated flag
        echo '</ul>'; // Close the list for files and buttons within this folder
    }
}
// Function to rename a folder or file
function renameItem($path, $newPath) {
    $result = array('success' => false, 'error' => '');

    if (rename($path, $newPath)) {
        $result['success'] = true;
    } else {
        $result['error'] = 'Failed to rename the item.';
    }

    return $result;
}

// Function to delete a folder and its contents
function deleteFolder($folderPath) {
    $result = array('success' => false, 'error' => '');

    if (is_dir($folderPath)) {
        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            } elseif (is_dir($file)) {
                deleteFolder($file);
            }
        }

        if (rmdir($folderPath)) {
            $result['success'] = true;
        } else {
            $result['error'] = 'Failed to delete the folder.';
        }
    } else {
        $result['error'] = 'Folder does not exist.';
    }

    return $result;
}

// Function to delete a PDF file
function deletePDFFile($pdfFile) {
    $result = array('success' => false, 'error' => '');

    if (file_exists($pdfFile)) {
        if (unlink($pdfFile)) {
            $result['success'] = true;
        } else {
            $result['error'] = 'Failed to delete the PDF file.';
        }
    } else {
        $result['error'] = 'PDF file does not exist.';
    }

    return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'rename') {
        $path = $_POST['path'];
        $newName = $_POST['newName'];
        $isFolder = is_dir($path);

        $newPath = dirname($path) . '/' . $newName;

        $result = renameItem($path, $newPath);

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    } elseif ($_POST['action'] === 'delete') {
        $path = $_POST['path'];
        $isFolder = $_POST['isFolder'];

        if ($isFolder === 'true') {
            $result = deleteFolder($path);
        } else {
            $result = deletePDFFile($path); // Call the new delete function for PDF files
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
}

$rootDir = 'docs/'; // root folder directory
generateFolderStructure($rootDir, false, false); // Ito ay nagpapakita ng mga subfolders nang bukas o sarado depende sa laman
?>

<!-- Context menu for folder and file operations -->
<div id="context-menu" style="position: absolute; display: none;">
    <button id="rename-button" onclick="renameItem()">Rename</button>
    <button id="delete-folder-button" onclick="deleteItem(true)">Delete Folder</button>
</div>

<!-- Context menu for PDF file operations -->
<div id="file-context-menu" style="position: absolute; display: none;">
    <button id="rename-file-button" onclick="renamePDFFile()">Rename PDF File</button>
    <button id="delete-file-button" onclick="deletePDFFile()">Delete PDF File</button>
  
</div>

<script>

    // Function to show context menu for folder and file operations
    function showContextMenu(event, path, isFolder) {
        event.preventDefault();
        const contextMenu = document.getElementById('context-menu');
        contextMenu.style.left = event.clientX + 'px';
        contextMenu.style.top = event.clientY + 'px';
        contextMenu.style.display = 'block';

        const renameButton = document.getElementById('rename-button');
        renameButton.dataset.path = path;
        renameButton.dataset.isFolder = isFolder;

        const deleteFolderButton = document.getElementById('delete-folder-button');
        deleteFolderButton.dataset.path = path;
        deleteFolderButton.dataset.isFolder = isFolder;
        

        document.addEventListener('click', hideContextMenu);
    }

    // Function to show context menu for PDF files
    function showFileContextMenu(event, pdfFile) {
        event.preventDefault();
        const fileContextMenu = document.getElementById('file-context-menu');
        fileContextMenu.style.left = event.clientX + 'px';
        fileContextMenu.style.top = event.clientY + 'px';
        fileContextMenu.style.display = 'block';

        const renameFileButton = document.getElementById('rename-file-button');
        renameFileButton.dataset.pdfFile = pdfFile;

        const deleteFileButton = document.getElementById('delete-file-button');
        deleteFileButton.dataset.pdfFile = pdfFile;

        const newWindow = document.getElementById('open-in-new-window');
      

        document.addEventListener('click', hideFileContextMenu);
    }

    // Function to hide the context menu
    function hideContextMenu() {
        const contextMenu = document.getElementById('context-menu');
        contextMenu.style.display = 'none';
        document.removeEventListener('click', hideContextMenu);
    }

    // Function to hide the context menu for PDF files
    function hideFileContextMenu() {
        const fileContextMenu = document.getElementById('file-context-menu');
        fileContextMenu.style.display = 'none';
        document.removeEventListener('click', hideFileContextMenu);
    }

    // Function to rename a folder or file
    function renameItem() {
        const renameButton = document.getElementById('rename-button');
        const path = renameButton.dataset.path;
        const isFolder = renameButton.dataset.isFolder === 'true';

        const newName = prompt(`Rename ${isFolder ? 'folder' : 'file'} to:`, path.split('/').pop());

        if (newName !== null) {
            const newPath = path.substring(0, path.lastIndexOf('/') + 1) + newName;
            // You can perform renaming here using AJAX or any server-side logic
            // For example, you can use a fetch request to a PHP script to rename the folder or file:
            fetch('folder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=rename&path=${encodeURIComponent(path)}&newName=${encodeURIComponent(newName)}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`${isFolder ? 'Folder' : 'File'} renamed: ${newName}`);
                    location.reload(); // Refresh the page
                } else {
                    console.error(`Error renaming ${isFolder ? 'folder' : 'file'}:`, data.error);
                }
            })
            .catch(error => {
                console.error(`Error renaming ${isFolder ? 'folder' : 'file'}:`, error);
            });
        }
    }

    // Function to rename a PDF file
    function renamePDFFile() {
        const renameFileButton = document.getElementById('rename-file-button');
        const pdfFile = renameFileButton.dataset.pdfFile;
        const pdfFileName = pdfFile.split('/').pop();
        const newNameWithExtension = prompt(`Rename PDF file to:`, pdfFileName);

        if (newNameWithExtension !== null) {
            // Extract the file extension from the original file name
            const fileExtension = pdfFileName.split('.').pop();
            
            // Remove the existing file extension (if any) from the new name
            const newNameWithoutExtension = newNameWithExtension.replace(/\.[^/.]+$/, "");

            // Combine the new name and the file extension
            const newName = newNameWithoutExtension + '.' + fileExtension;

            const newPath = pdfFile.substring(0, pdfFile.lastIndexOf('/') + 1) + newName;

            // You can perform renaming here using AJAX or any server-side logic
            // For example, you can use a fetch request to a PHP script to rename the PDF file:
            fetch('folder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=rename&path=${encodeURIComponent(pdfFile)}&newName=${encodeURIComponent(newName)}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`PDF File renamed: ${newName}`);
                    location.reload(); // Refresh the page
                } else {
                    console.error(`Error renaming PDF file:`, data.error);
                }
            })
            .catch(error => {
                console.error(`Error renaming PDF file:`, error);
            });
        }
    }

    function deletePDFFile() {
        const deleteFileButton = document.getElementById('delete-file-button');
        const pdfFile = deleteFileButton.dataset.pdfFile;

        if (confirm('Are you sure you want to delete this PDF file?')) {
            // You can perform deletion here using AJAX or any server-side logic
            // For example, you can use a fetch request to a PHP script to delete the PDF file:
            fetch('folder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&path=${encodeURIComponent(pdfFile)}&isFolder=false`, // Set isFolder to false
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`PDF File deleted: ${pdfFile}`);
                    location.reload(); // Refresh the page
                } else {
                    console.error('Error deleting PDF file:', data.error);
                }
            })
            .catch(error => {
                console.error('Error deleting PDF file:', error);
            });
        }
    }

    // Function to delete a folder or file
    function deleteItem(isFolder) {
        let deleteButton;
        if (isFolder) {
            deleteButton = document.getElementById('delete-folder-button');
        } else {
            deleteButton = document.getElementById('delete-file-button');
        }

        const path = deleteButton.dataset.path;

        if (confirm(`Are you sure you want to delete this ${isFolder ? 'folder' : 'file'}?`)) {
            // You can perform deletion here using AJAX or any server-side logic
            // For example, you can use a fetch request to a PHP script to delete the folder or file:
            fetch('folder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete&path=${encodeURIComponent(path)}&isFolder=${isFolder}`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`${isFolder ? 'Folder' : 'File'} deleted: ${path}`);
                    location.reload(); // Refresh the page
                } else {
                    console.error(`Error deleting ${isFolder ? 'folder' : 'file'}:`, data.error);
                }
            })
            .catch(error => {
                console.error(`Error deleting ${isFolder ? 'folder' : 'file'}:`, error);
            });
        }
    }

    // Function to search for files and folders
    function searchItems(searchQuery) {
    searchQuery = searchQuery.toLowerCase();
    const allFileLinks = document.querySelectorAll('.file-link');
    const allFolderLinks = document.querySelectorAll('.folder-link');

    // Function to search within subfolders and their contents
    function searchInSubfolder(subfolder, searchQuery) {
        const subfolderLinks = subfolder.querySelectorAll('.file-link, .folder-link');
        subfolderLinks.forEach(link => {
            const itemName = link.textContent.toLowerCase();
            if (itemName.includes(searchQuery)) {
                link.style.display = 'block';
            } else {
                link.style.display = 'none';
            }
        });
    }

    // Loop through all file and folder links and display those that match the search query
    allFileLinks.forEach(link => {
        const pdfFileName = link.textContent.toLowerCase();
        if (pdfFileName.includes(searchQuery)) {
            link.style.display = 'block';
        } else {
            link.style.display = 'none';
        }
    });

    allFolderLinks.forEach(link => {
        const folderName = link.textContent.toLowerCase();
        if (folderName.includes(searchQuery)) {
            link.style.display = 'block';
        } else {
            link.style.display = 'none';
        }
    });

    // Now, loop through all subfolders and their contents
    const subfolders = document.querySelectorAll('.files');
    subfolders.forEach(subfolder => {
        searchInSubfolder(subfolder, searchQuery);
    });
}



    // Add an event listener to the search button
    const searchButton = document.getElementById('search-button');
    searchButton.addEventListener('click', () => {
        const searchQuery = document.getElementById('search-bar').value;
        searchItems(searchQuery);
    });

    // Add an event listener to the search input to clear the search and show all items
    const searchBar = document.getElementById('search-bar');
    searchBar.addEventListener('input', () => {
        const searchQuery = searchBar.value;
        if (searchQuery === '') {
            // Show all items when the search input is empty
            const allFileLinks = document.querySelectorAll('.file-link');
            const allFolderLinks = document.querySelectorAll('.folder-link');
            allFileLinks.forEach(link => link.style.display = 'block');
            allFolderLinks.forEach(link => link.style.display = 'block');
        }
    });
</script>
