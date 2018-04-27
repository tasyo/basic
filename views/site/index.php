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
                [   'class'=>'btn btn-primary',
                    'id'=>'get-category'])?>
        </div>
        <div class="col-md-2 col-lg-2 ">
            <?=Html::a('Скачать продукты',
                ['product/parse'],
                [   'class'=>'btn btn-primary',
                    'id'=>'get-product' ])?>
        </div>
        <div class="col-md-2 col-lg-2">
            <?=Html::a('очистить базу данных',
                ['truncate-tables'],
                ['class'=>'btn btn-danger',
                  'id'=>'clear-database'])?>
        </div>
    </div>
    <h3>Info</h3>
    <p id="parseInfo"></p>
    <p id="parseI"></p>
    <div id="loader">
        <p>Подождите выполняется запрос.</p>
    </div>


</div>
