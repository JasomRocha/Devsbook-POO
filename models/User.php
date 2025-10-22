<?php
/**
 * @property int    $id
 * @property string $city
 * @property string $email
 * @property string $name
 * @property string $avatar
 *
 */
class User {
    public $id;
    public $email;
    public $password;
    public $name;
    public $city;
    public $avatar;
    public $cover;
    public $token;
    public $birthdate;
    public $work;
}

interface UserDao
{
    public function findByToken($token);
    public function findByEmail($id);
    public function update(User $u);
    public function insert(User $u);
}