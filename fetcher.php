<?php
/*************************************************************************
 * NOTICE OF COPYRIGHT                                                  *
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

require_once("dbcontroller.php");
$db_handle = new DBController(); // Set up database connection.
$epicid = $_POST['epicid'];
if (isset($epicid) && $epicid !== '' && $epicid !=='all') {
    $sqldropdown = "SELECT * from task WHERE type = 'UserStory' AND epicid = ".$epicid;
} else {
    $sqldropdown = "SELECT * from task WHERE type = 'UserStory' AND epicid >= 0";
}

echo '<option value="all">All User Stories</option>';
$taskdropdown = $db_handle->runQuery($sqldropdown);
foreach ($taskdropdown as $k=>$v) {
    echo "<option value=".$taskdropdown[$k]['id'].">".$taskdropdown[$k]['id'].": ".$taskdropdown[$k]['title']."</option>";
}
exit;
?>
