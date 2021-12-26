<?php 
header('Content-type: text/html; charset=utf-8');
session_start();
include('chatcore.php');
if(!isset($_SESSION['username']) && !isset($_GET['noauth'])){
	header('Location: chatauth.php?auto');
	exit;
}
?>
<!DOCTYPE>
<html>
    <head>
        <title>Чат-комната</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <style>

        </style>
        <script src="jquery-2.2.3.js"></script>
        <script src="jslibrary.js"></script>

        <script>
            function Chat(mode){
                    var m = document.getElementById("message").value; //Native
                    $.ajax({
                        url: 'chat_ajax.php',
                        type: 'POST',
                        cache: false,
                        data: mode ? ({message: m}): ({message:''}),
                        success: function(callback) {
                            ret = JSON.parse(callback);
                            src = document.getElementById("out").innerHTML;
                            document.getElementById("out").innerHTML = '';
                            for (key in ret) {
                                text = '';
                                text += '<strong>'+ret[key]['login']+'</strong><br>';
                                text += ret[key]['message'] + '<br>';
                                text += '<em>'+ret[key]['date'] + '</em><br><br>';
                                document.getElementById("out").innerHTML += text;
                            }
                            if (mode == 0 && document.getElementById("out").innerHTML != src) sound();
                        }
                    });
            }
            function sound(){
                var audio = new Audio();
                audio.src = 'new.ogg';
                audio.autoplay = true;
            }
            window.onload = function(){
               
                setInterval("Chat(0)", 1000);
            }
        </script>
    </head>

    <body>
        
        <div align="center">

        <?php if(isset($_SESSION['username'])) { ?><p>Вы вошли как <?php echo $_SESSION['username'] ?></p><?php } ?>

        <p><a href="chatreg.php">зарегистрироваться</a></p>

        <p>
        <?php if (isset($_SESSION['username'])) { ?><a href="chatauth.php?out">выйти</a>

        <?php } else { ?><a href="chatauth.php">авторизироваться</a><?php } ?>
        </p>

        <?php if (isset($_SESSION['username'])) ?>
        
        <form>
            <textarea id="message" maxlength="1000"></textarea><br/>
            <button class = "ok" onclick="Chat(1);scroll();">Send</button>
            <p id = "out"></p>
        </form>

    </body>
    

</html>