<?php

namespace app\models;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\UploadedFile;
use Yii;

/**
 * This is the model class for table "Product".
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $categoryId
 * @property string $image
 */
class Product extends \yii\db\ActiveRecord
{
    private static $currentUrl='no';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name',  'price','image', 'categoryId'], 'required'],
            [['id',  'categoryId'], 'integer'],
            [ ['name','image'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'url' => 'Url',
            'price' => 'Price',
            'categoryId' => 'Category ID',
        ];
    }


    static function getCountPage($url)
    {
        $client=new Client();
        $request=$client->request('GET',$url);
        $document=\phpQuery::newDocumentHTML($request->getBody());
        $countPage=pq('.g-pagination__list>li:last')->text();
        \phpQuery::unloadDocuments();
        return $countPage;
    }
//    public static function parse()
//    {
//        $url='https://oz.by/boardgames/topic1104059.html/';
//        $client=new Client();
//        $request=$client->request('GET',$url);
//        $document=\phpQuery::newDocumentHTML($request->getBody());
//        $countPage=pq('.g-pagination__list>li:last')->text();
//        for ($i=1;$i<=$countPage;$i++)
//        {
//            if($i>1)
//            {
//                $request=$client->request('GET',$url.'?page='.$i);
//                $document=\phpQuery::newDocumentHTML($request->getBody());
//            }
//            $listProduct=pq('#goods-table');
//            foreach ($listProduct->children() as $li)
//            {
//                $li=pq($li);
//                $product=new self();
//                $product->name=$li->find('.item-type-card__title')->text();
//                $product->url=$li->find('.item-type-card__link')->attr('href');
//                $product->price=(float)trim($li->find('.item-type-card__btn')->text());
//                $product->id=$li->attr('data-value');
//                $product->categoryId='0';
//                $product->save();
//            }
//            \phpQuery::unloadDocuments();
//        }
//
//        return$listProduct;
//
//    }

    private static function parseAsync($url, $categoryId,$categoryName)
    {

        $countPage=self::getCountPage($url);
        $client=new Client();
        for ($i=1;$i<=$countPage;$i++)
        {
            $request=new Request('GET',$url.'?page='.$i);
            $promise=$client->sendAsync($request)->then(function ($response) use($categoryId,$categoryName)
            {
                $document=\phpQuery::newDocumentHTML($response->getBody());
                $listProduct=pq('#goods-table');
                $listProduct->find('.viewer-type-card__li_bnr')->remove();
                foreach ($listProduct->children() as $li)
                {

                    $li=pq($li);
                    $id=$li->attr('data-value');
                    if(self::findOne($id))
                    {
                        continue;
                    }

                    $product=new self();
                    $product->id=$id;
                    $product->name=$li->find('.item-type-card__title')->text();
                    $product->price=(double)str_replace(',','.', trim($li->find('.item-type-card__btn')->text()));
                    $product->categoryId=$categoryId;

                    $transaction=Yii::$app->db->beginTransaction();
                    $url=$li->find('.viewer-type-list__img')->attr('src');
                    $urlProduct=$li->find('.item-type-card__link')->attr('href');
                    self::$currentUrl=$urlProduct;
                    $path='image'
                        .str_replace(strrchr($urlProduct,'/'),'',$urlProduct)
                        .'/'.$product->id.'.jpg';
                    $product->image=$path;

                    if(copy($url,$path)&& $product->save())
                    {
                        $transaction->commit();
                    }
                    else
                    {
                        $transaction->rollBack();
                    }
                }
                \phpQuery::unloadDocuments();
            });
            $promise->wait();
        }

        return 'Ok';
    }
    public static function parseAll()
    {
        $categories=self::getCategories(Category::getMenu());
        foreach ($categories as $category)
        {
            $url='https://oz.by'.$category['url'];
            self::parseAsync($url,$category['id'],$category['name']);
        }
    }



    static function getCategory()
    {
        return Category::find()->where(['status'=>-1])->one();
    }
    static function getCategories($categoryArray,$parentId=0)
    {
        static $categories;
        foreach ($categoryArray[$parentId] as $category)
        {
            if(isset($categoryArray[(int)$category['id']]))
            {
                $cat=self::findOne($category['id']);
                $cat->status=1;
                $cat->save();
                self::getCategories($categoryArray,$category['id']);
            }
            else
            {
                $categories[]=$category;
            }

        }
        return $categories;
    }

    public static function count()
    {
        return self::find()->count();
    }


}
