                        // <<<<<<<PDF container and Display code>>>>>>

// Variable to keep track of the currently open PDF file
let currentPDF = null;

// Function to open a PDF file in the PDF container
function openPDF(pdfFile) {
    // Check if a PDF is currently open
    if (currentPDF === pdfFile) {
        // If the clicked PDF is the same as the currently open one, close it
        closePDF();
    } else {
        // If a different PDF is clicked, open it
        currentPDF = pdfFile;
        const pdfContainer = document.getElementById("pdf-container");
        pdfContainer.innerHTML = '<iframe src="' + pdfFile + '" style="width: 100%; height: 1000px;"></iframe>';
        pdfContainer.style.display = "block"; // Show the PDF container
    }
}

// Function to close the PDF container
function closePDF() {
    currentPDF = null;
    document.getElementById("pdf-container").innerHTML = ''; // Remove the PDF content
    document.getElementById("pdf-container").style.display = "none"; // Hide the PDF container
}

function loadPDF(pdfURL) {
    var pdfContainer = document.getElementById('pdf-container');
    pdfContainer.innerHTML = '<iframe src="' + pdfURL + '" style="width: 100%; height: 1000px; "></iframe>';
}

/////////////////////////////////////////////////////////////////////////////


// Function to open the side nav
function openNav() {
    document.getElementById("mySidenav").style.width = "400px";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    document.getElementById("pdf-container").classList.add("pdf-container-open");
}

 window.addEventListener('load', openNav); //side nav show default remove it u want the side nav close after running


// Function to close the side nav
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.body.style.backgroundColor = "white";
    document.getElementById("pdf-container").classList.remove("pdf-container-open");
}

// Toggle subfolders when a folder link is clicked
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('folder-link')) {
        e.preventDefault(); // Prevent the default link behavior
        const folderContent = e.target.nextElementSibling;
        if (folderContent) {
            folderContent.style.display = (folderContent.style.display === 'none') ? 'block' : 'none';
        }
    }
});

 // Event Listeners for Uploading Files
 document.querySelectorAll('.upload-file-button').forEach(button => {
    button.addEventListener('click', () => {
        const subfolder = button.getAttribute('data-subfolder');
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        

        fileInput.addEventListener('change', async (event) => {
            const selectedFile = event.target.files[0];
            if (selectedFile) {
                const formData = new FormData();
                formData.append('file', selectedFile);

                try {
                    const response = await fetch('upload_file.php?subfolder=' + subfolder, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.text();
                    console.log(data);
                    location.reload;
                } catch (error) {
                    console.error('Error uploading file:', error);
                }
            }
        });

        fileInput.click(); // Trigger the file input click event
        location.reload;
    });
});


// Event listeners for creating folders
document.querySelectorAll('.create-folder-button').forEach(button => {
    button.addEventListener('click', function () {
        const subfolder = button.getAttribute('data-subfolder');
        const folderName = prompt('Enter a name for the new folder:');

        if (folderName && subfolder) {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log('Folder created:', folderName);
                        location.reload(); // Reload the webpage
                    } else {
                        console.error('Error creating folder:', xhr.statusText);
                    }
                }
            };
            xhr.open('GET', 'create_folder.php?folderName=' + encodeURIComponent(folderName) + '&subfolder=' + encodeURIComponent(subfolder), true);
            xhr.send();
        }
    });
});






                            // <<Drag and drop code>>


// JS Function for moving file location
function moveFile(filePath){
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('File deleted:', filePath);
                alert('File deleted.');
                location.reload();
            } else {
                console.error('Error deleting file:', xhr.statusText);
                alert('Error deleting file.');
            }
        }
    };
    xhr.open('POST', 'move_item.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('filePath=' + encodeURIComponent(filePath));
}
document.addEventListener('DOMContentLoaded', function () {
    const fileList = document.getElementById('file-link');
    var fileData = {
        path: '',
        target: ''
    }

    fileList.addEventListener('dragstart', (e) => {
        e.dataTransfer.setData('text/plain', e.target.getAttribute('data-file'));
        fileData.path = e.target.getAttribute('data-file');
    });

    fileList.addEventListener('dragover', (e) => {
        e.preventDefault();
    });

    fileList.addEventListener('drop', (e) => {
        e.preventDefault();
        fileData.target = e.target.getAttribute('path-folder');

        console.log(fileData.path + '\n' + fileData.target);
        fetch('move_item.php', {
            method: 'POST',
            body: JSON.stringify({ sourceFile: fileData.path, destinationFolder: fileData.target }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update the UI as needed
            if (data.message === 'OK') {
                console.log('File moved successfully.');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                console.error('Error moving file:', data.error);
            }
        })
        .catch(error => {
            console.error('Error moving file:', error);
        });
    });
});

