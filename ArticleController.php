<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\Html;
use yii\data\Pagination;
use yii\data\ArrayDataProvider;
use frontend\components\LibController;

/**
 * article controller
 */
class ArticleController extends LibController
{
    
    // articles per page
    public $range = 3;
    
    # ------------------------------------------------------------------------

    /**
     * main articles page
     * @param numder $page page number (1,2,3 etc)
     */
    public function actionIndex($page=false)
    {
        $limit_str = "0,$this->range";
        
        if ($page) {
            $limit_str = $this->range * $page - $this->range.",".$this->range;
        }

        // count visible articles
        $count = Yii::$app->db->createCommand(
                "SELECT COUNT(id_art) FROM rus_article WHERE `view` = 'yes'"
                )->queryScalar();
        
        $pages = new Pagination(['totalCount' => $count]);   
        $pages->defaultPageSize = $this->range;
        
        // selected by the limit condition
        $sql = "SELECT *
                FROM `rus_article`
                WHERE `rus_article`.`view` = 'yes'
                ORDER BY `rus_article`.`id_art` DESC
                LIMIT $limit_str;";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        
        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'defaultPageSize' => $this->range,
            ]
        ]);
        
        // set page meta
        Yii::$app->pv->descr = 'Статьи по Русской Истории в виде описания исторических дат, периодов, эпизодов, событий и людей.';
        Yii::$app->view->title = 'Статьи по Русской Истории';
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;        
        
        return $this->render('index', ['data'=>$provider,'pages' => $pages]);
    }
    
    # ------------------------------------------------------------------------
    
    /**
     * display one article for user
     * @param string $link article url
     */
    public function actionOne($link)
    {
        $sql = "SELECT 
                    `rus_article`.*,
                    `rus_category`.`name` as 'cat_name',
                    `rus_category`.`url` as 'cat_url'
                FROM `rus_article`
                LEFT JOIN `rus_category` USING (`id_cat`)
                WHERE `rus_article`.`view` = 'yes' 
                AND `rus_article`.`url` = '$link'";
        
        $data = Yii::$app->db->createCommand($sql)->queryOne();
        
        // set page meta
        Yii::$app->pv->descr = Yii::$app->pv->anons($data['content'],150);
        Yii::$app->view->title = $data['name'];
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;
        
        return $this->render('one', ['data'=>$data]);
    }
    
    # ------------------------------------------------------------------------    
    
    /**
     * articles for category page
     * @param string $category category url
     * @param number $page page number
     */
    public function actionCategory($category, $page=false)
    {
        $sql = "SELECT * FROM `rus_category` WHERE `rus_category`.`url` = '$category';";        
        $data = Yii::$app->db->createCommand($sql)->queryOne();
        
        // set page meta
        Yii::$app->pv->descr = $data['descr'];
        Yii::$app->view->title = $data['name'];
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;

        $limit_str = "0,$this->range";
        
        if ($page) {
            $limit_str = $this->range * $page - $this->range.",".$this->range;
        }

        $count = Yii::$app->db->createCommand("
            SELECT 
                COUNT(id_art) 
            FROM rus_article 
            LEFT JOIN rus_category USING(id_cat)
            WHERE rus_category.url = '$category'
            AND rus_article.view = 'yes'")->queryScalar();
        
        $pages = new Pagination(['totalCount' => $count]);   
        $pages->defaultPageSize = $this->range;
        
        $sql_art = "SELECT 
                        rus_article.*,
                        rus_category.url as cat_url
                    FROM rus_article
                    LEFT JOIN rus_category USING(id_cat)
                    WHERE rus_category.url = '$category'
                    ORDER BY `rus_article`.`id_art` DESC
                    LIMIT $limit_str;";
        
        $data_art = Yii::$app->db->createCommand($sql_art)->queryAll();
        
        $provider = new ArrayDataProvider([
            'allModels' => $data_art,
            'pagination' => [
                'defaultPageSize' => $this->range,
            ]
        ]);

        return $this->render('category', ['data'=>$provider,'pages' => $pages]);
    }
    
    # ------------------------------------------------------------------------
    
    /**
     * manipulates before every controller action
     */
    public function beforeAction($action)
    {

        // added first, parent link to breadcrumbs
        if (in_array($this->action->id, ['one','category'])) {
            $article = Html::a('Статьи по Русской Истории',['/article']);
            Yii::$app->view->params['breadcrumbs'][1] = $article;            
        }

        if (!parent::beforeAction($action)) {
            return false;
        }

        return true;
    }
}
