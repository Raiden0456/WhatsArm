<?php 
//устанавливаем кодировку
header('Content-type: text/html; charset=utf-8');
//открывем сессию
session_start();
//подключаем функции
include('chatcore.php');

//если нажата кнопка 'зарегистрироваться'
if (isset($_POST['submit'])){
	
	//проверяем соответствие капчи
	if ($_SESSION['captcha'] != md5($_POST['captcha']*$_POST['captcha']+$_POST['captcha']*$_POST['captcha']*$_POST['captcha'])){
		$_SESSION['info'] = 'Неверно указано число';
		unset($_SESSION['captcha']);
		header('Location: chatreg.php');
		exit;
	}
	
	//проверяем введен ли логин и пароль
	if (!isset($_POST['login']) || !isset($_POST['password']) || empty($_POST['login']) || empty($_POST['password'])){
		$_SESSION['info'] = 'Не введен логин или пароль';
		header('Location: chatreg.php');
		exit;
	}
	
	//проверяем длину логина и пароля
	if (strlen($_POST['login'])<6 || strlen($_POST['password'])<6) {
		$_SESSION['info'] = 'Длина пароля или логина меньше 6 символов';
		header('Location: chatreg.php');
		exit;
	}
	if (strlen($_POST['login'])>36 || strlen($_POST['password'])>36) {
		$_SESSION['info'] = 'Длина пароля или логина больше 36 символов';
		header('Location: chatreg.php');
		exit;
	}
	
	//проверяем допустимые символы
	if (!charcheck($_POST['login']) || !charcheck($_POST['password'])) {
		$_SESSION['info'] = 'Недопустимые символы в пароле или логине';
		header('Location: chatreg.php');
		exit;
	}
	
	//проверяем повторяющиеся логины
	if (dbq("SELECT * FROM `users` WHERE `login` = '".f6($_POST['login'])."'")) {
		$_SESSION['info'] = 'Такое имя пользователя уже существует';
		header('Location: chatreg.php');
		exit;
	}
	
	//добавляем пользователя в базу, если проверки пройдены
	dbq("INSERT INTO `users` SET 
	`login` = '".f6($_POST['login'])."',
	`password` = '".md5(f6($_POST['password']))."',
	`date` = NOW(),
	`datechat` = NOW()
	");
	$_SESSION['info'] = 'Пользователь успешно зарегистрирован';
	header('Location: chatauth.php');
	exit;
}

//загружаем капчу
include('captcha.php');
?>
<!DOCTYPE>
<html>
    <head>
        <title>Регистрация</title>
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
            <div>Регистрация</div>
            <?php echo $_SESSION['info']; unset($_SESSION['info'])?><br/>
            <form action="" method="POST">
                <input type="text" name="login" maxlength="16" placeholder="Логин" /><br/>
                <input type="text" name="password" maxlength="16" placeholder="Пароль"/><br/>
                <?php echo $captcha?><br/>
                <input  type="submit" name="submit" value="Зарегистрироваться"/><br/>
                <a href="chatauth.php"><input type="button" value="Уже есть аккаунт? Войти"/></a>
            </form>
        </div>
    </body>
</html>