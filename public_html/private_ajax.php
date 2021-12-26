<?php
header('Content-type: text/html; charset=utf-8');
session_start();
include('chatcore.php');


if(isset($_POST['deletemess']) and $_POST['deletemess']!=''){
    dbq("DELETE FROM `private` WHERE `id` = '".f6($_POST['deletemess'])."'"); 
}

if(isset($_POST['message']) and $_POST['message']!=''){
    dbq(
        "INSERT INTO `private` SET `login` = '".f6($_SESSION['username'])."',
        `message` = '".f6($_POST['message'])."',
        `reciever` = '".f6($_POST['whereto'])."',
        `date` = NOW() "
    );  
}

dbq("UPDATE `users` SET `datechat` = NOW() WHERE `login` = '".f6($_SESSION['username'])."'");

 $count = dbq("SELECT COUNT(`id`) FROM `private` "); 
 $num =  $count[0]['COUNT(`id`)'];

if(isset($_POST['count'])){
    $loadnum = ($num > $_POST['count']) ? $_POST['count'] : $num-1;
    $count = dbq("SELECT COUNT(`id`) FROM `private` ");
    $a = dbq("SELECT * FROM `private` WHERE (`reciever` =  '".f6($_POST['whereto'])."' AND `login` = '".f6($_SESSION['username'])."') OR (`reciever` =  '".f6($_SESSION['username'])."' AND `login` = '".f6($_POST['whereto'])."') LIMIT ".($num-$loadnum).", ".$loadnum);
} 

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