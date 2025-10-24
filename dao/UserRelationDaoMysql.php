<?php
require_once 'models/UserRelation.php';
class UserRelationDaoMysql implements UserRelationDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function insert(UserRelation $u)
    {
        // TODO: Implement insert() method.
    }

    public function getFollowing($id): array
    {
       $users = [];
        // Pega os usuarios que o User segue
       $sql = $this->pdo->prepare("SELECT user_to FROM userrelations WHERE user_from = :user_from");
       $sql->bindParam(':user_from', $id);
       $sql->execute();

       if($sql->rowCount() > 0){
           $data = $sql->fetchAll();
           foreach($data as $d){
               $users[] = $d['user_to'];
           }
       }
       return $users;
    }

    public function getFollowers($id): array
    {
        $users = [];
        // Pega os usuarios que o seguem o User
        $sql = $this->pdo->prepare("SELECT user_from FROM userrelations WHERE user_to = :user_to");
        $sql->bindParam(':user_to', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $data = $sql->fetchAll();
            foreach($data as $d){
                $users[] = $d['user_from'];
            }
        }
        return $users;
    }


}