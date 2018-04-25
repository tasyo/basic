<?php
/**
 * Created by PhpStorm.
 * User: Comp
 * Date: 05.04.2018
 * Time: 10:51
 */

namespace app\controllers;


use app\models\Category;
use yii\web\Controller;

class CategoryController  extends Controller
{
    public function actionParse()
    {
        $categories=Category::parse();
//        return $this->render('parse', compact('categories'));
        $this->redirect('/');
    }

    public function actionMenu()
    {
        $menu=Category::getMenu();
        return $this->renderPartial('menu',compact('menu'));

    }

}