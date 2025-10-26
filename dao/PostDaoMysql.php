<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationDaoMysql.php';
require_once 'dao/UserDaoMysql.php';
require_once 'dao/PostLikeDaoMysql.php';
require_once 'dao/PostCommentDaoMysql.php';

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

    public function getHomeFeed($user_id): array{
        $array = [];

        $urDao = new UserRelationDaoMysql($this->pdo);
        $userList = $urDao->getFollowing($user_id);
        $userList[]= $user_id;
        $sql = $this->pdo->query("SELECT * FROM posts  WHERE $user_id IN (".implode(',', $userList).") ORDER BY created_at DESC");

        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);

            $array = $this->_postListToObject($data, $user_id);
        }
        return $array;
    }

    public function getUserFeed($user_id, $mine_id): array{
        $array = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id_user = :user_id ORDER BY created_at DESC");
        $sql->bindValue(':user_id', $user_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $array = $this->_postListToObject($data, $user_id, $mine_id);
        }
        return $array;
    }

    public function getPhotosFrom($id_user): array{
        $array = [];

        $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id_user = :id_user AND type = 'photo' ORDER BY created_at DESC");
        $sql->bindValue(':id_user', $id_user);
        $sql->execute();

        if($sql->rowCount() > 0){
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $array = $this->_postListToObject($data, $id_user);
        }

        return $array;
    }

    private function _postListToObject($postList, $id_user, $mine_id = null): array
    {
        $posts = [];
        $userDao = new UserDaoMysql($this->pdo);
        $postLikeDao = new PostLikeDaoMysql($this->pdo);
        $postCommentDao = new PostCommentDaoMysql($this->pdo);

        foreach($postList as $post){
            $newPost = new Post();
            $newPost->id = $post['id'];
            $newPost->type = $post['type'];
            $newPost->created_at = $post['created_at'];
            $newPost->body = $post['body'];
            $newPost->mine = false;

            if($post['id_user'] === $id_user){
                $newPost->mine = true;
            }

            $newPost->user = $userDao->findById($post['id_user']);

            //Informações sobre LIKE
            $newPost->likeCount = $postLikeDao->getLikeCount($newPost->id);
            $newPost->liked = $postLikeDao->isLiked($newPost->id, $mine_id ?: $id_user);
            $newPost->comments = $postCommentDao->getComments($newPost->id);

            // Informação sobre comments
            $posts[] = $newPost;
        }
        return $posts;
    }
}