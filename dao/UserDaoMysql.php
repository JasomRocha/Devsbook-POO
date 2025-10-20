<?php
require_once 'models/User.php';
class UserDaoMysql implements UserDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    // Recebe um array e monta um objeto usuario
    private function generateUser($array): User{
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->name = $array['name'] ?? '';
        $u->email = $array['email'] ?? '';
        $u->bithdate = $array['birthdate'] ?? '';
        $u->city = $array['city'] ?? '';
        $u->avatar = $array['avatar'] ?? '';
        $u->cover = $array['cover'] ?? '';
        $u->token = $array['token'] ?? '';
        $u->password = $array['password'] ?? '';

        return $u;
    }

    // Recebe o token da sessao e verifica se ele existe no BD
    public function findByToken($token){
        if(!empty($token)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $sql->bindValue(':token', $token);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                return $this->generateUser($data); // Retorna o usuario logado
            }
        }

        return false;
    }

    public function findByEmail($email){
        if(!empty($email)){
            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindValue(':email', $email);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetch(PDO::FETCH_ASSOC);
                return $this->generateUser($data); // Retorna o usuario logado
            }
        }

        return false;
    }

    public function update(User $u){
        $sql = $this->pdo->prepare("UPDATE users SET
            name = :name,
            email = :email,
            birthdate = :birthdate,
            city = :city,
            avatar = :avatar,
            token = :token,
            password = :password,
            work = :work,
            cover = :cover
            WHERE id = :id");

        $sql->bindValue(':name', $u->name);
        $sql->bindValue(':email', $u->email);
        $sql->bindValue(':birthdate', $u->bithdate);
        $sql->bindValue(':city', $u->city);
        $sql->bindValue(':avatar', $u->avatar);
        $sql->bindValue(':token', $u->token);
        $sql->bindValue(':password', $u->password);
        $sql->bindValue(':id', $u->id);
        $sql->execute();

        return true;
    }
}