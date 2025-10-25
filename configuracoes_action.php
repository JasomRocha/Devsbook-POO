<?php
require_once "config.php";
require_once "models/Auth.php";
require_once "dao/postDaoMysql.php";

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$userDao = new UserDaoMysql($pdo);

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$birthdate = filter_input(INPUT_POST, 'birthdate', FILTER_SANITIZE_STRING);
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
$work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$password_confirmation = filter_input(INPUT_POST, 'password_confirmation', FILTER_SANITIZE_STRING);

if($name && $email){
    $userInfo->name = $name;
    $userInfo->city = $city;
    $userInfo->work = $work;

    // Valida o e-mail do usuario
    if($userInfo->email != $email){
        if($userDao->findByEmail($email) === false){
            $userInfo->email = $email;
        } else {
            $_SESSION['flash'] = 'Email já cadastrado';
            header('Location: /configuracoes.php');
        }

    }

    // valida o aniversário do usuario
    $birthdate = explode('/', $birthdate);
    if(count($birthdate) !== 3) {
        $_SESSION['flash'] = 'Data de nascimento inválida';
        header("Location: ".$base."/configuracoes.php");
        exit;
    }
    $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
    if(strtotime($birthdate) === false) {
        $_SESSION['flash'] = 'Data de nascimento inválida';
        header("Location: ".$base."/configuracoes.php");
        exit;
    }
    $userInfo->birthdate = $birthdate;

    if(!empty($password) ){
        if($password === $password_confirmation){
            $userInfo->password = password_hash($password, PASSWORD_DEFAULT);
        }else{
            $_SESSION['flash'] = 'Senhas diferentes';
            header("Location: ".$base."/configuracoes.php");
            exit;
        }
    }

    // atualizar a foto de perfil
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])){
        $newAvatar = $_FILES['avatar'];

        if(in_array($newAvatar['type'], array('image/jpeg', 'image/png', 'image/gif'))){
            $avatarWidth = 200;
            $avatarHeight = 200;

            list($widthOrig, $heightOrig) = getimagesize($newAvatar['tmp_name']);
            $ratio = $widthOrig / $heightOrig;

            $newWidth = $avatarWidth;
            $newHeight = $avatarHeight / $ratio;

            if($newHeight < $avatarHeight){
                $newHeight = $avatarHeight;
                $newWidth = $newHeight * $ratio;
            }

            $x = $avatarWidth - $newWidth;
            $y = $avatarHeight - $newHeight;
            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;

            $finalImage = imagecreatetruecolor($avatarWidth, $avatarHeight);

            switch($newAvatar['type']){
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($newAvatar['tmp_name']);
                break;
                case 'image/png':
                    $image = imagecreatefrompng($newAvatar['tmp_name']);
                break;
            }
            imagecopyresampled(
              $finalImage, $image,
              $x, $y, 0, 0,
              $newWidth, $newHeight, $widthOrig, $heightOrig
            );

            $avatarName = md5(time().rand(0,9999)).'.jpg';
            imagejpeg($finalImage, './media/avatars/'.$avatarName, 100);
            $userInfo->avatar = $avatarName;
        }
    }
     // Atualizar a foto de capa
    if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])){
        $newCover = $_FILES['cover'];

        if(in_array($newCover['type'], array('image/jpeg', 'image/png', 'image/gif'))){
            $coverWidth = 850;
            $coverHeight = 313;

            list($widthOrig, $heightOrig) = getimagesize($newCover['tmp_name']);
            $ratio = $widthOrig / $heightOrig;

            $newWidth = $coverWidth;
            $newHeight = $coverHeight / $ratio;

            if($newHeight < $coverHeight){
                $newHeight = $coverHeight;
                $newWidth = $newHeight * $ratio;
            }

            $x = $coverWidth - $newWidth;
            $y = $coverHeight - $newHeight;
            $x = $x<0 ? $x/2 : $x;
            $y = $y<0 ? $y/2 : $y;

            $finalImage = imagecreatetruecolor($coverWidth, $coverHeight);

            switch($newCover['type']){
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($newCover['tmp_name']);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($newCover['tmp_name']);
                    break;
            }
            imagecopyresampled(
                $finalImage, $image,
                $x, $y, 0, 0,
                $newWidth, $newHeight, $widthOrig, $heightOrig
            );

            $coverName = md5(time().rand(0,9999)).'.jpg';
            imagejpeg($finalImage, './media/covers/'.$coverName, 100);
            $userInfo->cover = $coverName;
        }
    }
    $userDao->update($userInfo);
}





header("Location: /configuracoes.php");
exit;