<?php
class Post {
    public $id;
    public $id_user;
    public $type; // text ou photo
    public $created_at;
    public $body;
}

interface PostDao
{
  public function insert(Post $post);
}