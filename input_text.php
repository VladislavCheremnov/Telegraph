<?php
use App\Entities\TelegraphText;
use App\Entities\FileStorage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'autoload.php';
require_once "vendor/autoload.php";

function exception_handler($exception) 
{
    if ($exception->getMessage() !== ''){
        echo '<div style="width: 400px; height: 400px; background-color: pink;">';
        echo '<p style="font-weight: bold;">' .$exception->getMessage(). PHP_EOL.'</p>';
        echo '</div>';
    }
}
  
set_exception_handler('exception_handler');

$isSend = false;
$isSendMail = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['author'] === '' || $_POST['text'] === '') {
        $err = "Заполните поля 'Автор' и 'Текст сообщения'!";
    } else {
        $telegraphText = new TelegraphText($_POST['author'], 'path');
        $telegraphText->editText($_POST['title'], $_POST['text']);
        
        $save = new FileStorage();
        $save->create($telegraphText);
        $isSend = true;

        if ($_POST['email'] !== '') {
    
            $mail = new PHPMailer(true);
            try {
                $mail->addAddress($_POST['email'], $_POST['author']);
                $mail->Subject = $_POST['title'];
                $mail->Body = $_POST['text'];
                $mail->send();
                $isSendMail = 'Копия сообщения отправлена на указанную вами почту!';
            } catch (Exception $e) {
                echo  "Mailer Error: " . $mail->ErrorInfo;
            } 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegraph</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container wrap__position">
        <form class="form form-flex" action="input_text.php" method="post">
            <? if($isSend): ?>
                <div class="form_successful">
                    <p>Ваше сообщение успешно отправлено!</p>
                    <p><?=$isSendMail;?></p>
                </div>
            <? endif;?>
            <input class="form__input form__input-style" type="text" name="author" placeholder="Автор">
            <input class="form__input form__input-style" type="text" name="email" placeholder="Email">
            <label class="form__label" for="text">Ваше восхитительное сообщение :)</label>
            <input class="form__input form__input-style" type="text" name="title" placeholder="Заголовок">
            <? if($err !== ''): ?>
                <div class="form_error">
                    <p><?=$err?></p>
                </div>
            <? endif;?>
            <textarea class="form__textarea form__textarea-style" id="text" rows="15" cols="55" name="text" placeholder="Текст сообщения"></textarea>
            <button class="form__btn form__btn-style btn-reset" type="submit">Отправить сообщение</button>
        </form>
    </div>
</body>
</html>