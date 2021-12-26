<?php
//формируем массив неповторяющихся случайных чисел
$rand[0]=rand(1,99);
$rand[1]=rand(1,99);
while ($rand[1]==$rand[0]) $rand[1]=rand(1,99);
$rand[2]=rand(1,99);
while ($rand[2]==$rand[0] || $rand[2]==$rand[1]) $rand[2]=rand(1,99);
$rand[3]=rand(1,99);
while ($rand[3]==$rand[0] || $rand[3]==$rand[1] || $rand[3]==$rand[2]) $rand[3]=rand(1,99);
$rand[4]=rand(1,99);
while ($rand[4]==$rand[0] || $rand[4]==$rand[1] || $rand[4]==$rand[2] || $rand[4]==$rand[3]) $rand[4]=rand(1,99);

//определяетм случайный режим загадки
$randact=rand(1,3);

//заготовка для капчи
$captcha = "<em>";

//наибольшее число
if ($randact == 1){
	$answ = max($rand);
	$captcha .= 'Найдите наибольшее число среди';
	foreach ($rand as $num){
		$captcha .= ' '.$num;
	}
}

//наименьшее число
elseif ($randact == 2){
	$answ = min($rand);
	$captcha .= 'Найдите наименьшее число среди';
	foreach ($rand as $num){
		$captcha .= ' '.$num;
	}
}

//среднее число
elseif ($randact == 3){
	$abcrand = $rand;
	sort($abcrand);
	$answ = $abcrand[2];
	$captcha .= 'Найдите среднее число среди';
	foreach ($rand as $num){
		$captcha .= ' '.$num;
	}
}

//формируем капчу
$captcha .= '</em><br/><input type="number" name="captcha" min="1" max="500" />';

//отправляем в сессию хеш капчи усложненый формулой x*x*x+x*x
$_SESSION['captcha'] = md5($answ*$answ+$answ*$answ*$answ);
?>