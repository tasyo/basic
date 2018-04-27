<?php
/**
 * Created by PhpStorm.
 * User: Comp
 * Date: 05.04.2018
 * Time: 10:51
 */

namespace app\controllers;


use app\models\Category;
use yii\filters\AjaxFilter;
use yii\web\Controller;

class CategoryController  extends Controller
{
    public function behaviors()
    {
        return[
          'class'=>AjaxFilter::className()
        ];

    }


    public function actionParse()
    {
        $categories=Category::parse();
        return 'Получено '.Category::count().' записей';
    }


    public function actionCount()
    {
        return Category::count();
    }

}