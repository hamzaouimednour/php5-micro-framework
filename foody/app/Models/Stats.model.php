<?php 
$user_ip = Handler::getUserIp();

$stats_website = PATH_VIEWS . 'stats-website.json';

Handler::counter($user_ip, $stats_website);

?>