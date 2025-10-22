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

    public function getRelationsFrom($id)
    {
       $users = [$id];

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
}