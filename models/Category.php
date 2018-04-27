<?php

namespace app\models;

use GuzzleHttp\Client;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "Category".
 *
 * @property int $id
 * @property int $parentId
 * @property string $name
 * @property string $url
 * @property int $status
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentId'], 'integer'],
            [['name', 'url'], 'required'],
            [['name', 'url'], 'string', 'max' => 64],
            [['status'],'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parentId' => 'Parent ID',
            'name' => 'Name',
            'url' => 'Url',
        ];
    }

    public static function importDB($selectors, $parentId)
    {
        foreach ($selectors as $selector)
        {
            $selector=pq($selector);
            $aMas=$selector->children('a');
            foreach ($aMas as $a)
            {
                $a=pq($a);
                $category=new self();
                $category->name=trim($a->text());
                $category->parentId=$parentId;
                $category->url=$a->attr('href');
                $category->save();
                if($ul=$a->next('ul')->children())
                {
                    if($ul->count())
                    {
                        $category->status=1;
                        $category->save();
                        self::importDB($ul, $category->id);
                    }
                }
                    $path=str_replace(strrchr($category->url,'/'),'',$category->url);
                 $result=FileHelper::createDirectory('image/'.$path);


            }
        }
        return;
    }


    public static function parse()
    {
        $client=new Client();
        $request=$client->request('GET','https://oz.by/');
        $document=\phpQuery::newDocumentHTML($request->getBody());
        $categories=pq('.main-nav__list');
        $categories->find('.main-nav__list__li_inner')->remove();
        $categories->find('#storesTab')->remove();
        $categories->find('.global-ppnavlist__promo')->remove();
        $categories->find('.global-ppnavlist__bar')->remove();
        $categories->find('div')->contentsUnwrap();
       $categories->find('div.global-ppnavlist__header-cont')->contentsUnwrap();
      self::importDB($categories->children(),0);

        \phpQuery::unloadDocuments();
        return $categories;

    }


    public static function getMenu()
    {
        $categories=self::find()->asArray()->all();
        foreach ($categories as $category)
        {
            $menu[$category['parentId']][]=$category;
        }
        return $menu;
    }



    public static function getMenuForParse()
    {
        $categories=self::find()->where(['status'=>'-1'])->asArray()->all();
        foreach ($categories as $category)
        {
            $menu[$category['parentId']][]=$category;
        }
        return $menu;
    }

    public static function count()
    {
        return self::find()->count();
    }




}
