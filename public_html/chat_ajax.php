<?php
header('Content-type: text/html; charset=utf-8');
session_start();
include('chatcore.php');

//удаление сообщения
if(isset($_POST['deletemess']) and $_POST['deletemess']!=''){
    dbq("DELETE FROM `message` WHERE `id` = '".f6($_POST['deletemess'])."'"); 
}
//новое сообщение
if(isset($_POST['message']) and $_POST['message']!=''){
    dbq(
        "INSERT INTO `message` SET `login` = '".f6($_SESSION['username'])."',
        `message` = '".f6($_POST['message'])."',
        `date` = NOW() "
    );  
}
//обновление статуса пользователя
dbq("UPDATE `users` SET `datechat` = NOW() WHERE `login` = '".f6($_SESSION['username'])."'");
//подсчет записей(для авто скроллинга)
 $count = dbq("SELECT COUNT(`id`) FROM `message` "); 
 $num =  $count[0]['COUNT(`id`)'];

if(isset($_POST['count'])){
    $loadnum = ($num > $_POST['count']) ? $_POST['count'] : $num-1;
    //колличество записей в таблице `message`
    $count = dbq("SELECT COUNT(`id`) FROM `message` ");
    //выгрузка сообщений общего чата
    $a = dbq("SELECT * FROM `message` LIMIT ".($num-$loadnum).", ".$loadnum);
} 
//сравнивание unix timestamp в момент когда пользователь находился на сайте с обычным unix time для определения разницы в секундах и установлению корректоного статуса
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
