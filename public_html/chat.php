<?php 
//устанавливаем кодировку
header('Content-type: text/html; charset=utf-8');
//открывем сессию
session_start();
//подключаем функции
include('chatcore.php');
//проверяем существует ли сессия, если нет, то отправляем на автоавторизацию (в chatauth.php с параметром GET['auto'])
if(!isset($_SESSION['username'])){
	header('Location: chatreg.php?auto');
	exit;
}
//hello teesting this git bruh
?>
<!DOCTYPE>
<html>
    <head>
        <title>WhatsArm</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="jquery-2.2.3.js"></script>
        <script>
            //получаем в JS имя текущего юзера из сессии
            iamuser = '<?php echo $_SESSION['username']; ?>';
            //создаем массив со смайлами, можно добавлять новые, названия сопадают с именем_файла.gif из папки smile
            smile = ['blum','blush','cray','derisive','dirol','grin','ireful','laugh2','mosking','yes3'];
            //начальное число выгружаемых сообщений
            messagenum = 0;
            
            //функция связи с сервером
            function Chat(mode, id=''){
                //получаем введенное сообщение
                var m = document.getElementById("message").value;
                //если отправляем сообщение, то очищаем форму и делаем scrollDown
                if (mode == 1) {document.getElementById("message").value= ''; scroll()}
                //получаем область сообщений и увеличиваем число сообщений на 10 если scrollTop
                upscroll = document.getElementById("out");
                down=true;
                if (upscroll.scrollTop == 0) {messagenum+=10; down=false}
                //AJAX
                $.ajax({
                    url: 'chat_ajax.php',
                    type: 'POST',
                    cache: false,
                    data: mode ? ({message: m, count:messagenum, deletemess:id}): ({message:'', count:messagenum, deletemess:''}),
                    success: function(callback) {
                        ret = JSON.parse(callback);
                        src = document.getElementById("out").innerHTML;
                        document.getElementById("out").innerHTML = '';
                        for (key in ret) {
                            text = '';
                            text += '<strong><a href = "http://vrezhrfl.beget.tech/private.php?user='+ret[key]['login']+'" >'+ret[key]['login']+'</a></strong> ';
                            if (ret[key]['time'] == 'online') text += '('+ret[key]['time']+')<br>';
							else text += '(был в сети: '+ret[key]['time']+')<br>';
                            text += emtoimg(ret[key]['message']) + '<br>';
                            text += '<em>'+ret[key]['date'] + '</em>';
                            text += ret[key]['login'] == iamuser ? '<em><a onclick="mdelete('+ret[key]['id']+')"> Удалить сообщение</a></em><br><br>' : '<br><br>';
                            document.getElementById("out").innerHTML += text;
                        }
                        if (mode == 0 && document.getElementById("out").innerHTML != src){
                            if (down) {scroll(); sound();}
                        }
                        //КОСТЫЛЬ - чтобы сначала прокрутить вниз
                        if (messagenum == 10) {scroll(); messagenum = 20}
                    }
                });
            }
            
            //функция удаления сообщения по id
            function mdelete(a){
                Chat(1, a);
            }
            
          //  function redirect(reciever){
             //   window.location.href = "http://vrezhrfl.beget.tech/private.php" + reciever;
          //  }
            
            //функция воспроизведения звуков
            function sound(){
                var audio = new Audio();
                audio.src = 'new.ogg';
                audio.autoplay = true;
            }
            
            // скрол вниз
            function scroll(){
                var autoscroll = document.getElementById("out");
                autoscroll.scrollTop = autoscroll.scrollHeight;
            }

            // запуск функции Chat(0), каждую секунду, после загрузки всей старницы
            window.onload = function(){
                setInterval("Chat(0)", 1000);
            }

            // функция добавляющая мнемоник смайлика в форму
            function emozi(type){
                document.getElementById('message').value = document.getElementById('message').value + type;
            }
            
            // функция заменяющая мнемоник смайлика в строке на картинку
            function emtoimg(message){
                for (i in smile){
                    message = message.replaceAll(
                        ':'+smile[i]+':',
                        '<img src = smile/'+smile[i]+'.gif>'
                    );
                }
                return message;
            }
        </script>
    </head>
    <body>
        <?php if(isset($_SESSION['username'])) { ?><p>Вы вошли как <?php echo $_SESSION['username'] ?></p><?php } ?>
        <?php if (isset($_SESSION['username'])) { ?><p><a href="chatauth.php?out"><input type = "button" id = "exit" value = "Выйти"></a></p><?php } ?>
        <form>
            <div id = "texto">
                <p id = "out"></p>
                <textarea id="message" maxlength="1000" placeholder = "Без матов, пожалуйста."></textarea>
                <input type="button" id = "ok" value = "Отправить" onclick="Chat(1);scroll();"/> 
            </div>
            <div id = "smile">
                 <script>
                    for (n in smile) {
                        document.write("<img src=smile/"+smile[n]+".gif onclick=emozi(':"+smile[n]+":')>");
                    }
                </script>
            </div>
        </form>
    </body>
</html>