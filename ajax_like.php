<?php
require_once "config.php";
require_once "models/Auth.php";
require_once 'dao/PostLikeDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


if(!empty($id)) {
    $postLikeDaoMysql = new PostLikeDaoMysql($pdo);
    $postLikeDaoMysql->likeToggle($id, $userInfo->id);
}
