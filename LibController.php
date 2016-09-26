<?php
namespace frontend\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

/**
 * controller-library
 * all controllers start with him
 */
class LibController extends Controller 
{
    
    /**
     * create top menu dropdown item for site categories
     */
    public function menuTopCategory()
    {
        
        // parent array
        $arr = [
            'label' => 'Статьи', 
            'url' => ['#'],
            'active' => $this->id == 'article',
        ];
        
        $items = [];
        
        // selected not empty cageries items
        $sql = 'SELECT 
                    rus_article.id_cat,
                    COUNT(*) AS c,
                    rus_category.name,
                    rus_category.url
                FROM rus_article
                LEFT JOIN rus_category USING(id_cat)
                GROUP BY id_cat
                HAVING c > 0
                ORDER BY rus_category.id_cat DESC'; 
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        
        foreach ($data as $val) {
            $items[] = [
                'label' => $val['name'],
                'url' => [
                    '/article/category', 'category' => $val['url']
                    ]
                ];
        }
        
        // added to the beginning of an array
        array_unshift(
                $items, 
                ['label' => 'Главная','url' => ['/article/index'],],
                ['label' => false,'options' => ['class' => 'divider'],]
            );
        
        $arr['items'] = $items;

        return $arr;
    }    
    
    # ------------------------------------------------------------------------
    
    /**
     * create url for next age step url
     * @param number $step current exam step
     */
    public function nextAgeUrl($step)
    {
        $age = Yii::$app->request->get('age');
        
        $next_url = Url::to([
            $this->id.'/'.$this->action->id,
            'age' => $age,
            'step' => $step + 1
        ]);
        
        if ($step == $this->all_steps) {
            $next_url = Url::to([
                $this->id.'/finish']);
        }
        
        return $next_url;
    }
    
    # ------------------------------------------------------------------------
    
    /**
     * slice an array piece
     * from result array and current step
     * @param array $res array after query
     * @param number $step current exam step
     */
    public function sliceArr ($res,$step)
    { 
        $st = $step - 1;
        $start_slice = $st * $this->per_step;
        $arr = array_slice($res, $start_slice, $this->per_step);
        return $arr;
    }
    
    # ------------------------------------------------------------------------
    
    /**
     * print debug information
     * @param array $arr array for display
     * @param boolean $show show/hide argument
     */
    public function printArr($arr, $show=true)
    {
        $pre1 = '';
        $pre2 = '';
        
        if ($show === false) {
            $pre1 = '<!-- ';
            $pre2 = ' -->';
        }
        
        echo $pre1 . "<pre>\n\n";
        print_r($arr);
        echo "\n\n</pre>" . $pre2;
    }
}
