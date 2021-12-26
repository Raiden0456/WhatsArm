<?php 
//устанавливаем кодировку
header('Content-type: text/html; charset=utf-8');
//открывем сессию
session_start();
//подключаем функции
include('chatcore.php');

//выход из аккаунта (удаляем сессию и куки) если пришли с параметром GET['out']
if (isset($_GET['out'])) {
	if (isset($_SESSION['username'])) {
		setcookie('username', 'fake', time()-86400, '/');
		setcookie('password', 'fake', time()-86400, '/');
		unset($_SESSION['username']);
		$_SESSION['info'] = 'Выход из аккаунта выполнен';
		header('Location: chatauth.php');
		exit;
	}
}

//автоавторизация если пришли с параметром GET['auto']
if (isset($_GET['auto'])){
    //проверяем существуют ли куки
	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
	    //проверяем существует ли такой юзер в базе, который указан в куках
		if ($user = dbq("SELECT * FROM `users` WHERE `login` = '".f6($_COOKIE['username'])."' LIMIT 1")) {
		    //проверяем совпадает ли его пароль с тем, который указан в куках
			if ($user[0]['password'] == $_COOKIE['password']) {
			    //проверки пройдены, создаем сессию, обновляем куки
				$_SESSION['username'] = $user[0]['login'];
				$_SESSION['info'] = 'Вы вошли как '.$_SESSION['username'];
				setcookie('username', $_SESSION['username'], time()+1209600, '');
				setcookie('password', $_COOKIE['password'], time()+1209600, '');
				header('Location: chat.php');
				exit;
			} else {
			    //иначе отправляет в chatreg.php
				header('Location: chatreg.php');
				exit;
			}
		}
	} else {
	    //иначе отправляет в chatreg.php
		header('Location: chatreg.php');
		exit;
	}	
}

//если нажата кнопка 'войти'
if (isset($_POST['submit'])){
    //проверяем введен ли логин и пароль
	if (!isset($_POST['login']) || !isset($_POST['password']) || empty($_POST['login']) || empty($_POST['password'])){
		$_SESSION['info'] = 'Не введен логин или пароль';
		header('Location: chatauth.php');
		exit;
	}
	//проверяем существует ли такой юзер в базе
	if ($user = dbq("SELECT * FROM `users` WHERE `login` = '".f6($_POST['login'])."' LIMIT 1")) {
	    //проверяем совпадает ли его пароль
		if ($user[0]['password'] == md5($_POST['password'])) {
			$_SESSION['username'] = $user[0]['login'];
			setcookie('username', $_SESSION['username'], time()+1209600, '');
			setcookie('password', md5($_POST['password']), time()+1209600, '');
			header('Location: chat.php');
			exit;
		} else {
			$_SESSION['info'] = 'Пароль неверный';
			header('Location: chatauth.php');
			exit;
		}	
	} else {
		$_SESSION['info'] = 'Такого пользователя не существует';
		header('Location: chatauth.php');
		exit;
	}
}
?>
<!DOCTYPE>
<html>
    <head>
        <title>Авторизация</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <meta name="keywords" content="">
        <meta name="description" content="">
    <script>
    </script>
    </head>
    
    <body>
        <div align="center">
             <div>Авторизация</div>
            <?php echo $_SESSION['info']; unset($_SESSION['info'])?><br/>
            <form action="" method="POST">
                <input type="text" name="login" maxlength="16" placeholder="Логин"/><br/>
                <input type="text" name="password" maxlength="16" placeholder="Пароль"/><br/>
                <input  type="submit" name="submit" value="Войти"/><br/>
                <a href="chatreg.php"><input type="button" value="Регистрация"/></a>
            </form>
        </div>
    </body>
</html>