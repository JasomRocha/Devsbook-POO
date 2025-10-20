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
    public $bithdate;
    public $avatar;
    public $cover;
    public $token;
}

interface UserDao
{
    public function findByToken($token);
    public function findByEmail($id);
    public function update(User $u);
}