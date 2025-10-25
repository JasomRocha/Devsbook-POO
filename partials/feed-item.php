<?php
require_once 'feed-item-script.php';

$actionPhrase = '';
switch($item->type){
    case 'text':
        $actionPhrase = 'fez um post';
        break;
    case 'image':
        $actionPhrase = 'postou uma foto';
        break;
}
?>

<div class="box feed-item" data-id="<?=$item->id?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="/perfil.php?id=<?=$item->user->id?>"><img src="media/avatars/<?= $item->user->avatar ?>" /></a>
            </div>
            <div class="feed-item-head-info">
                <a href=""><span class="fidi-name"><?=$item->user->name?></span></a>
                <span class="fidi-action"><?=$actionPhrase?></span>
                <br/>
                <span class="fidi-date"><?=date('d/m/Y', strtotime($item->created_at));?></span>
            </div>
            <div class="feed-item-head-btn">
                <img src="assets/images/more.png" />
            </div>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
           <?=nl2br($item->body)?>
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?= $item->liked ? 'on' : '' ?>"> <?= $item->likeCount ?> </div>
            <div class="msg-btn"><?= count($item->comments)?></div>
        </div>
            <div class="feed-item-comments">
                <div class="fic-answer row m-height-10 m-width-20">
                    <div class="fic-item-photo">
                        <a href="/perfil.php"><img src="media/avatars/<?=$userInfo->avatar?>" /></a>
                    </div>
                    <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
                </div>
        </div>
    </div>
</div>


