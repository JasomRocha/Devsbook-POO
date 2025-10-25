<?php
require_once 'config.php';
require_once 'models/Auth.php';
require_once 'dao/postDaoMysql.php';

$auth = new Auth($pdo, $base);
$userInfo = $auth->checkToken();
$activeMenu = 'photos';
$postDao = new PostDaoMysql($pdo);
$userDao = new UserDaoMysql($pdo);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id){
    $id = $userInfo->id;
}

if($id != $userInfo->id){
    $activeMenu = '';
}

// Pega informações do usuário
$user = $userDao->findById($id, true);
if(!$user){
    header('Location:/');
    exit;
}

$dateFrom = new \DateTime($user->birthdate);
$dateTo = new \DateTime('today');
$user->ageYears = $dateFrom->diff($dateTo)->y;



require 'partials/header.php';
require 'partials/menu.php';
?>
<section class="feed">

    <div class="row">
        <div class="box flex-1 border-top-flat">
            <div class="box-body">
                <div class="profile-cover" style="background-image: url('media/covers/<?= $user->cover?>');"></div>
                <div class="profile-info m-20 row">
                    <div class="profile-info-avatar">
                        <img src="media/avatars/<?=$user->avatar?>" />
                    </div>
                    <div class="profile-info-name">
                        <div class="profile-info-name-text"><?=$user->name?></div>
                        <?php if(!empty($user->city)): ?>
                            <div class="profile-info-location"><?=$user->city?></div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-info-data row">
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($user->followers)?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?= count($user->following)?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->photos)?></div>
                            <div class="profile-info-item-s">Fotos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="column">

            <div class="box">
                <div class="box-body">
                    <div class="ui segment">
                        <h3 class="ui dividing header">Fotos de <?= htmlspecialchars($user->name) ?></h3>

                        <div class="full-user-photos">
                            <?php if (count($user->photos) > 0): ?>
                                <div class="ui four stackable cards">
                                    <?php foreach ($user->photos as $key => $photo): ?>
                                        <div class="ui card user-photo-item">
                                            <div class="image">
                                                <a href="#modal-<?= $key ?>" rel="modal:open">
                                                    <img src="media/uploads/<?= htmlspecialchars($photo->body) ?>" alt="Foto <?= $key + 1 ?>" />
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Modal -->
                                        <div id="modal-<?= $key ?>" class="ui small modal" style="display: none;">
                                            <div class="image content">
                                                <img class="ui centered large image" src="media/uploads/<?= htmlspecialchars($photo->body) ?>" />
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="ui placeholder segment center aligned">
                                    <div class="ui icon header">
                                        <i class="images outline icon"></i>
                                        Nenhuma foto disponível
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
    </div>

</section>

<?php
require "partials/footer.php";
?>

