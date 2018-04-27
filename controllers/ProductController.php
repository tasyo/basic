<?php
/**
 * Created by PhpStorm.
 * User: Comp
 * Date: 06.04.2018
 * Time: 10:52
 */

namespace app\controllers;


use app\models\Category;
use app\models\Product;
use yii\filters\AjaxFilter;
use yii\web\Controller;

class ProductController extends Controller
{
    public function behaviors()
    {
        return [
          'class'=>AjaxFilter::className()
        ];
    }


    public function actionParse()
    {
       $products=Product::parseAll();
        return 'Получено '.Product::count().' записей';
    }




}