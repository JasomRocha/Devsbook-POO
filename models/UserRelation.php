<?php

class UserRelation
{
    public $id;
    public $user_from;
    public $user_to; // text ou photo
}

interface UserRelationDao
{
    public function insert(UserRelation $u);
    public function getRelationsFrom($id);
}