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
  public function getHomeFeed($id_user);
  public function getUserFeed($id);
  public function getPhotosFrom($id_user);
}