<?php

/* @var $this yii\web\View */
use yii\bootstrap\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">


    <div class="container">
        <div class="col-md-2 col-lg-2">
            <?=Html::a('Получить категории',
                ['category/parse'],
                ['class'=>'btn btn-primary'])?>
        </div>
        <div class="col-md-2 col-lg-2">
            <?=Html::a('Скачать продукты',
                ['product/parse'],
                [
                    'class'=>'btn btn-primary',
                    'id'=>'download'
                    ])?>
        </div>
        <div class="col-md-2 col-lg-2">
            <?=Html::a('очистить базу данных',
                ['truncate-tables'],
                ['class'=>'btn btn-danger'])?>
        </div>
    </div>
    <?php foreach (Yii::$app->session->getAllFlashes() as $type=>$messages):?>
        <?php foreach ($messages as $message):?>
            <div class="alert >"><?=$message?></div>
        <?php endforeach?>
    <?php endforeach?>
    <h3>Info</h3>
    <p id="parseInfo"></p>
    <p id="parseI"></p>
    <a id="stop" class="col-sm-2 btn btn-primary">Stop</a>


</div>
