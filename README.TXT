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


Documentation for Agile Planning sheets:
****************************************

Database: agileplan
===================
Tables:
    public $tbl_dev = 'developers';
    public $tbl_eff = 'effort';
    public $tbl_epic = 'epic';
    public $tbl_sys = 'system';
    public $tbl_bklg = 'task';
    public $tbl_vel = 'velocity';
    public $tbl_wks = 'weeks';

    developers - list of developers in team
        id
        firstname
        lastname
        username
        icon - currently unused in code
        imageurl - currently unused in code

    effort - primary table storing plans for who and what is done each week
        id
        taskid - foreign key link to task table
        developerid - foreign key link to developer table
        weekid - foreign key link to week table
        effort - estimated effort planned by 'dev' on 'task' in 'week'

    epic - list of epics being worked on
        id
        systemid - foreign key link to systems table
        title
        description
        deadline
        notes
        icon - currently unused in code

    system - list of systems
        id
        system
        productowner
        seniordeveloper (this is not restricted to developers list as may be external eg Sunrise)
        customercontact

    task - product backlog list
        id
        type - UserStory, Task, SubTask
        epicid -  foreign key link to epics table
        title
        completion
        description
        deadline
        parent - internal link within backlog. Identifies parent user story for tasks
        notes
        Releasability
        Risk
        DependenciesUpstream
        DependenciesDownstream
        businessvalue - calculated within php code

    velocity - planned effort points available for each dev in a given week
        id
        developerid
        weekid
        plannedvelocity

    weeks - which week is in which sprint and what major uni wide events are in that week
        id
        weekcommencing
        sprint
        events

Available pages:
================
    Developers
    Systems
    Weeks
    Planned Velocities
    Epics
    Backlog
    Effort
    Reporting
        by developer
        by sprint
        by various


