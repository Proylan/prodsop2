<!DOCTYPE html>
<html>
<head>
    <title>PSG Operations</title>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">


    <style>
        

        /* Sidebar Header Styles */
        .sidenav-header {
            text-align: center;
            padding: 5px;
            background-color: #222; /* Header background color */
        }

        .sidenav-header h2 {
            color: #fff; /* Header text color */
        }

      
    </style>   
</head>
<body>

<div id="mySidenav" class="sidenav">
        <div class="sidenav-header">
            <h2>ProductionSOP</h2>
        </div>

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
    