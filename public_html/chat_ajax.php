<?php
header('Content-type: text/html; charset=utf-8');
session_start();
include('chatcore.php');


if(isset($_POST['deletemess']) and $_POST['deletemess']!=''){
    dbq("DELETE FROM `message` WHERE `id` = '".f6($_POST['deletemess'])."'"); 
}

if(isset($_POST['message']) and $_POST['message']!=''){
    dbq(
        "INSERT INTO `message` SET `login` = '".f6($_SESSION['username'])."',
        `message` = '".f6($_POST['message'])."',
        `date` = NOW() "
    );  
}

dbq("UPDATE `users` SET `datechat` = NOW() WHERE `login` = '".f6($_SESSION['username'])."'");

 $count = dbq("SELECT COUNT(`id`) FROM `message` "); 
 $num =  $count[0]['COUNT(`id`)'];

if(isset($_POST['count'])){
    $loadnum = ($num > $_POST['count']) ? $_POST['count'] : $num-1;
    $count = dbq("SELECT COUNT(`id`) FROM `message` ");
    $a = dbq("SELECT * FROM `message` LIMIT ".($num-$loadnum).", ".$loadnum);
} 

/*
elseif (isset($_POST['last']) and $_POST['last']!='no'){
    $a = dbq("SELECT * FROM `message` WHERE `date` > ".f6($_POST['last']));
}
*/

/*
$b  = dbq("SELECT `login`, UNIX_TIMESTAMP(datechat) FROM `users`");

foreach ($a as $row){
    foreach ($b as $row2){
        if ($row['login'] == $row2['login']){
            if($row2['UNIX_TIMESTAMP(datechat)'] <= time()+10) 
            $row['online'] =true;
        }
    } 
}
*/
//$a = dbq("SELECT * FROM `message`");

$b  = dbq("SELECT `login`, `datechat`, UNIX_TIMESTAMP(datechat) FROM `users`");
foreach ($a as &$message){
    foreach ($b as $user){
        if ($message['login'] == $user['login']){
            if($user['UNIX_TIMESTAMP(datechat)'] > time()-15) $message['time'] = 'online';
			else $message['time'] = $user['datechat'];
			
        }
    } 
}

echo json_encode($a);
?>