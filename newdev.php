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
include('includes/head.php');

require_once("dbcontroller.php");
$table = $_GET['t'];

if (isset($_POST['submit'])) {
    $db_handle = new DBController();

    // get form data, making sure it is valid
    $firstname = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['firstname']));
    $lastname = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['lastname']));
    $username = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['username']));
    $icon = mysqli_real_escape_string($db_handle->conn, htmlspecialchars($_POST['icon']));
    $error = '';

    // check to make sure both fields are entered
    if ($firstname == '' || $lastname == '' || $username == '') {
        // generate error message
        echo '<p class="alert alert-danger">ERROR: Please fill in all required fields!</p>';
        echo '<p class="text-danger">* Firstname: '.$firstname.'</p>';
        echo '<p class="text-danger">* Lastname: '.$lastname.'</p>';
        echo '<p class="text-danger">* Username: '.$username.'</p>';
        echo '<p>icon: '.$icon.'</li>';
        echo '<p class="alert alert-primary">The page will refresh in 10seconds.
                Or click to <a href="developers.php" class="btn btn-primary">Return to Developers list</a></p>';
        header('Refresh: 10; url=developers.php');

    } else {
        // save the data to the database
        $sql = "INSERT " . $table . " SET firstname='$firstname', lastname='$lastname', username='$username', icon='$icon'";
        $dev = $db_handle->executeUpdate($sql);

        // once saved, redirect back to the view page
        header('Refresh: 0; url=developers.php');
    }
}
include('includes/foot.php');
?>
