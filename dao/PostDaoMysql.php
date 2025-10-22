<?php
require_once 'models/Post.php';

class PostDaoMysql implements PostDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }

    public function insert(Post $post){
        $sql = $this->pdo->prepare('INSERT INTO posts (
                   id_user, type, created_at, body
                   ) VALUES ( 
                        :id_user, :type, :created_at, :body
                   )');
        $sql->bindValue(':id_user', $post->id_user);
        $sql->bindValue(':type', $post->type);
        $sql->bindValue(':created_at', $post->created_at);
        $sql->bindValue(':body', $post->body);

        $sql->execute();
    }
}