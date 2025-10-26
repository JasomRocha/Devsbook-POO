<?php
require_once 'models/UserRelation.php';
class UserRelationDaoMysql implements UserRelationDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function insert(UserRelation $u)
    {
        $sql = $this->pdo->prepare("INSERT INTO userrelations (user_from, user_to) VALUES (:user_from, :user_to)");
        $sql->bindValue(':user_from', $u->user_from);
        $sql->bindValue(':user_to', $u->user_to);
        $sql->execute();
    }

    public function delete(UserRelation $u)
    {
        $sql = $this->pdo->prepare("DELETE FROM userrelations WHERE user_from = :user_from AND user_to = :user_to");
        $sql->bindValue(':user_from', $u->user_from);
        $sql->bindValue(':user_to', $u->user_to);
        $sql->execute();
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

    public function isFollowing($id1, $id2): bool{
        $sql = $this->pdo->prepare("SELECT * FROM userrelations WHERE user_from = :user_from AND user_to = :user_to");
        $sql->bindParam(':user_from', $id1);
        $sql->bindParam(':user_to', $id2);
        $sql->execute();

        return $sql->rowCount() > 0;
    }


}