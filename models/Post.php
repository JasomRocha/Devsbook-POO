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
  public function delete($id, $id_user);
  public function getHomeFeed($id_user);
  public function getUserFeed($id, $mine_id);
  public function getPhotosFrom($id_user);
}