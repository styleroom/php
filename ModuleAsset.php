<?php
namespace frontend\modules\adminka\assets;

use Yii;
use frontend\assets\AppAsset;

/**
 * module asset
 */
class ModuleAsset extends AppAsset
{
    public $jsOptions = [ 'position' => \yii\web\View::POS_HEAD ];    
    
    public function init ()
    {
        parent::init();
        
        // added additional js files
        array_push($this->js, 'http://cdn.vpvd.ru/ckeditor/moono-color/full/ckeditor.js');
        array_push($this->js, 'advanced/frontend/modules/adminka/web/jscss/translit.js');
        
        // enabled js-script for CKEditor
        Yii::$app->view->registerJs(
                'CKEDITOR.config.allowedContent = true;',
                \yii\web\View::POS_HEAD
                );
    }
}
