<!DOCTYPE html>
<html>
<head>
    <title>PSG Operations</title>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
     
    
     .folder-link {
            color: #ffff88 !important;
            cursor: pointer;
        }

       
        .file-link {
            color: lightblue !important;
            cursor: pointer;
        }



        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 20%;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .pdf-container-open {
  margin-left: 400px; /* Adjust this value to match the width of your side nav */
  transition: margin-left 0.3s ease;

  
}

    </style>
</head>
<body>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <ul id="file-link">
        <?php
        include 'folder.php'; // Include the folder.php file to display subfolders and PDF links
        ?>
    </ul>
</div>

<span class="list-icon" onclick="openNav()"><img src="img/list.png" style="height: 40px;"></span>


<!-- Container to display the PDFs -->
<div id="pdf-container">
    
</div>

<script src="scripts.js"></script>
<script src="dragDrop.js"></script>
</body>
</html>
    