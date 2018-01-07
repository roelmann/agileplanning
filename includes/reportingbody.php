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

                foreach($tasknamelist as $eff) {
                    $tasks = explode(':',$eff);
                    $epictitle = trim($tasks[0]);
                    $systemname = trim($tasks[1]);
                    $taskid = trim($tasks[2]);
                    $tasktitle = trim($tasks[3]);
                    $devid = trim($tasks[4]);
                    $devname = trim($tasks[5]);

                    $taskname = $epictitle.': '.$systemname.': '.$tasktitle.': '.$devname;
                    ?>
                    <tr class="table-row">
                        <td contenteditable="false" colspan=2><?php echo $taskname; ?></td>
                        <?php
                        foreach($weeks as $kw=>$vw) {
                            $sql = "SELECT * from " . $table . " WHERE taskid= ".$taskid." AND weekid = ".$weeks[$kw]['id'] . " AND developerid = ".$devid;
                            $efftlist = $db_handle->runQuery($sql);
                            $efftid=$efftlist[0]['id'];
                            $eft=$efftlist[0]['effort'];
                            $class='';
                            if ($eft > 0) {
                                $class = 'table-success font-weight-bold';
                            }
                            ?>
                            <td contenteditable="false" class="<?php echo $class; ?>"><?php echo $eft; ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
