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
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <a class="navbar-brand" href="index.php">Agile Planner</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="developers.php">Developers</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="systems.php">Systems</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="weeks.php">Weeks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="velocity.php">Planned Velocities</a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="epics.php">Epics</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="backlog.php">Backlog</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="effort.php">Effort</a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Reporting
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="reporting_bydeveloper.php">By Developer</a>
          <a class="dropdown-item" href="reporting_bysprint.php">By Sprint</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="reporting_byepic.php">By Epic - coming soon</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="https://github.com/roelmann/agileplanning/issues">TO-DO</a>
      </li>
    </ul>
  </div>
</nav>
<p class="alert alert-danger" style="margin-top:60px"><strong>THIS IS BETA FOR TESTING, COMMENT AND FURTHER DEVELOPMENT</strong><br>Tested in Chrome<br>Unknown Bug in Firefox means contenteditable tables are unreliable for integer values (treating as string and preventing database save???)<br>Untested in IE</p>
