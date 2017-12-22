<?php
/*************************************************************************
 * NOTICE OF COPYRI|GHT                                                  *
 * Agile Planner - Copyright (C) 2017 onwards: R Oelmann                 *
 *                 oelmann.richard@gmail.com                             *
 *                                                                       *
 * This program is free software; you can redistribute it and/or modify  *
 * it under the terms of the GNU General Public License as published by  *
 * the Free Software Foundation; either version 3 of the License, or     *
 * (at your option) any later version.                                   *
 *                                                                       *
 * This program is distributed in the hope that it will be useful,       *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 * GNU General Public License for more details:                          *
 *                                                                       *
 *          http://www.gnu.org/copyleft/gpl.html                         *
 *                                                                       *
 *************************************************************************/
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>Agile Planning</title>
        <!-- Fontawesome4 CSS: Online -->
        <script src="https://use.fontawesome.com/7bf60e6cd2.js"></script>

        <!-- Bootsrap4 jQuery: Online link: NOTE NOT THE SLIM VERSION -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <!-- Bootstrap4 Popper.js: Online link -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <!-- Bootstrap4 CSS: Online link -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <!-- Bootstrap4 JS: Online link -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

        <!--Bootswatch -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0-beta.2/sandstone/bootstrap.min.css">

        <!-- Local styles -->
        <link rel="stylesheet" href="styles/styles.css">

        <script>
        function showEdit(editableObj) {
            $(editableObj).css("background","#FFF");
        }
        function saveToDatabase(editableObj,table,column,id) {
            $(editableObj).css("background","#FFF url(pix/loaderIcon.gif) no-repeat right");
            $.ajax({
                url: "saveedit.php?t="+table,
                type: "POST",
                data:'column='+column+'&editval='+editableObj.innerHTML+'&id='+id,
                success: function(data){
                    $(editableObj).css("background","#FDFDFD");
                }
           });
        }
        </script>

    </head>
    <body>
        <div class="page-wrapper container-fluid">
            <?php include('navbar.php'); ?>