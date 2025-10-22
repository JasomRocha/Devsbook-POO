<?php
require 'dao/UserDaoMysql.php';

// Objeto específico para autenticação
class Auth {
    private $pdo; // Objeto de acesso a dados do php
    private $base; // url base configurada
    private $dao;
    // Recebe o PDO e a URL base
    public function __construct($pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
        $this->dao = new UserDaoMysql($this->pdo);
    }
    public function checkToken() {

        if(!empty($_SESSION['token'])) { // verifica se o token da sessao nao é vazio
            $token = $_SESSION['token'];
            $user = $this->dao->findByToken($token);
            // faz uma consulta no banco se existir esse token retorna o usuario
            if($user){
                return $user; // se existir o token no banco, retorna o usuario que possui esse token
            }
        }
        header("Location: ".$this->base."/login.php"); // se nao existir o token no banco, retorna para login
        exit;
    }

    public function validateLogin($email, $password) {
        $user = $this->dao->findByEmail($email);

        if($user){
            if(password_verify($password, $user->password)){
                $token = md5(time().rand(0, 9999));
                $_SESSION['token'] = $token; // Atualizo token da sessão

                $user->token = $token;
                $this->dao->update($user); // Atualizo o token do usuario

                return true;
            }
        }
        return false;
    }

    public function emailExists($email) {

        return (bool)$this->dao->findByEmail($email);
    }

    public function registerUser($name, $email, $password, $birthdate) {


        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = md5(time().rand(0, 9999));

        $newUser = new User();

        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->password = $hash;
        $newUser->birthdate = $birthdate;
        $newUser->token = $token;

        $this->dao->insert($newUser);

        $_SESSION['token'] = $token;
    }

}