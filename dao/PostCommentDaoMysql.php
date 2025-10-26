<?php
require_once 'models/PostComment.php';
require_once "UserDaoMysql.php";

class PostCommentDaoMysql implements PostCommentDao{
    private $pdo;

    public function __construct(PDO $driver){
        $this->pdo = $driver;
    }
    public function getComments($id_post): array{
        $array = [];

        $sql = $this->pdo->prepare("SELECT * FROM postcomments WHERE id_post = :id_post");
        $sql->bindValue(':id_post', $id_post);
        $sql->execute();
        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $userDao = new UserDaoMysql($this->pdo);

            foreach($data as $item){
                $comment = new PostComment();
                $comment->id = $item['id'];
                $comment->id_post = $item['id_post'];
                $comment->id_user = $item['id_user'];
                $comment->body = $item['body'];
                $comment->created_at = $item['created_at'];
                $comment->user = $userDao->findById($item['id_user']);

                $array[] = $comment;
            }
        }
        return $array;
    }
    public function addComment(PostComment $pc){
        $sql = $this->pdo->prepare("INSERT INTO postcomments (id_post, id_user, created_at, body ) VALUES (:id_post, :id_user,  :created_at, :body)");

        $sql->bindValue(':id_post', $pc->id_post);
        $sql->bindValue(':id_user', $pc->id_user);
        $sql->bindValue(':created_at', $pc->created_at);
        $sql->bindValue(':body', $pc->body);

        $sql->execute();
    }
}


