<?php
require_once 'models/PostLike.php';

class PostLikeDaoMysql implements PostLikeDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }
    public function getLikeCount($id_post){
        $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM postlikes WHERE id_post = :id_post");
        $sql->bindValue(':id_post', $id_post);
        $sql->execute();

        $data = $sql->fetch();
        return $data['c'];
    }
    public function isLiked($id_post, $id_user): bool{
        $sql = $this->pdo->prepare("SELECT * FROM postlikes WHERE id_post = :id_post AND id_user = :id_user");
        $sql->bindValue(':id_post', $id_post);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        return $sql->rowCount() > 0;
    }
    public function likeToggle($id_post, $id_user){
        if($this->isLiked($id_post, $id_user)){
            $sql = $this->pdo->prepare("DELETE FROM postlikes WHERE id_post = :id_post AND id_user = :id_user");
        }else{
            $sql = $this->pdo->prepare("INSERT INTO postlikes (id_post, id_user) VALUES (:id_post, :id_user)");
        }
        $sql->bindValue(':id_post', $id_post);
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();
    }
}

