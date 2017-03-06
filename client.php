<?php

$url = 'http://localhost/server-client/server.php?method=getWheather';
//$url = 'http://localhost/server-client/server.php?method=getStatus';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, 'a:b');

$icerik = curl_exec($ch);
curl_close($ch);

var_dump(json_decode($icerik, TRUE));