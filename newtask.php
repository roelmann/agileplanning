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

// Get head and navbar.
include('includes/head.php');
echo '<div class="page-wrapper container-fluid page-newtask">';
include('includes/navbar.php');

// Call initial database queries.
require_once("dbcontroller.php");
$table = $_GET['t'];

if (isset($_POST['submit'])) {
    $db_handle = new DBController();

    // Get form data, making sure it is valid.
    $type = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['type']));
    $epicid = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['epicid']));
    $parent = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['parent']));
    $title = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['title']));
    $completion = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['completion']));
    $description = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['description']));
    $deadline = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['deadline']));
    $notes = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['notes']));
    $MoSCoW = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['MoSCoW']));
    $Releasability = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['Releasability']));
    $Risk = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['Risk']));
    $DependenciesUpstream = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['DependenciesUpstream']));
    $DependenciesDownstream = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['DependenciesDownstream']));
    $error = '';

    // Check to make sure both fields are entered.
    if ($type == '' || $title == '') {
        // Generate error message.
        echo '<p class="alert alert-danger">ERROR: Please fill in all required fields!</p>';
        echo '<p class="text-danger">* Type: '.$type.'</p>';
        echo '<p class="text-danger">Title: '.$title.'</p>';
        echo '<p class="alert alert-primary">The page will refresh in 10seconds.
                Or click to <a href="backlog.php" class="btn btn-primary">Return to Backlog list</a></p>';
        // Redirect back to the view page.
        ?>
        <script type="text/javascript">
            location.replace("backlog.php");
        </script>
        <?php

    } else {
        // Save the data to the database.
        $sql = "INSERT " . $table . " SET type='$type', epicid='$epicid', parent='$parent', title='$title', completion='$completion', description='$description', deadline='$deadline', notes='$notes', MoSCoW='$MoSCoW', Releasability='$Releasability', Risk='$Risk', DependenciesUpstream='$DependenciesUpstream', DependenciesDownstream='$DependenciesDownstream'";
        $dev = $db_handle->executeUpdate($sql);

        // Once saved, redirect back to the view page.
        ?>
        <script type="text/javascript">
            location.replace("backlog.php");
        </script>
        <?php
    }
}
include('includes/foot.php');
?>
