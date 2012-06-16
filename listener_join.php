<?php

header("icecast-auth-user: 1");

include('includes/bootstrap.php');

$db->join($_POST['client'], $_POST['ip'], $_POST['mount'], $_POST['agent'], $_POST['referrer'], $_POST['server'], $_POST['port']);