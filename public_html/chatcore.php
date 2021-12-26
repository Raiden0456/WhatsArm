<?php

//функция работы с БД
function dbq ($query=''){
    //устанавливаем соединение (хост, юзер, пароль, база)
	$link = mysqli_connect("localhost", "vrezhrfl_chat", "4at_B@se2001", "vrezhrfl_chat");
	//устанавливаем соединение (кодировку передачи)
	mysqli_set_charset($link,'utf8');
	//отправляем запрос
	$res = mysqli_query($link,$query);
	//если запрос вернул false, закрываем соединение, выводим текст ошибки, отстанавливаем php
	if ($res===false) {
		$error = mysqli_error($link);
		mysqli_close($link);
		exit($error);
	}
	//если запрос вернул true (в случае INSERT, UPDATE, DELETE), запрос прошел успешно, возвращаем false
	elseif ($res===true){
		mysqli_close($link);
		return false;
	}
	//если запрос вернул данные (в случае SELECT), возвращаем массив с данными
	else {
		while ($row = mysqli_fetch_assoc($res)){
			$data[]=$row;
		}
		mysqli_close($link);
		return $data;
		}
}

//функция для обработки запроса, защита от SQL-инъекций
function f6($text){
	return trim(addslashes(strip_tags($text)));
}

//функция проверки на допустимые символы ('A-Z','a-z','0-9','_')
function charcheck ($text){
	if (preg_match('|^[-A-Za-z0-9_]*$|',$text)) return true;
	else return false;
}

?>