<?php
require 'dao/UserDaoMysql.php';

// Objeto específico para autenticação
class Auth {
    private $pdo; // Objeto de acesso a dados do php
    private $base; // url base configurada

    // Recebe o PDO e a URL base
    public function __construct($pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
    }
    public function checkToken() {
        if(!empty($_SESSION['token'])) { // verifica se o token da sessao nao é vazio
            $token = $_SESSION['token'];

            $userDao = new UserDaoMysql($this->pdo); //
            $user = $userDao->findByToken($token);
            if($user){
                return $user;
            }
        }
        header("Location: ".$this->base."/login.php");
        exit;
    }

    public function validateLogin($email, $password) {
        $userDao = new UserDaoMysql($this->pdo);
        $user = $userDao->findByEmail($email);
        if($user){

            if(password_verify($password, $user->password)){
                $token = md5(time().rand(0, 9999));
                $_SESSION['token'] = $token;
                $userDao->update($user);
                return true;
            }

        }
        return false;
    }

}