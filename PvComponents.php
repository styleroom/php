<?php
namespace frontend\components;

use Yii;
use yii\base\Component;

/**
 * component-helper
 */
class PvComponent extends Component
{
    
    public $url;
    public $url_site;
    public $descr = false;
    public $ogimg = 'http://img-fotki.yandex.ru/get/52127/13223519.15d/0_ad87d_d5ee1bb6_orig.jpg';
    public $ip;
    public $itsme = false;
    public $pupil = false;
    
    # ------------------------------------------------------------------------
    
    /**
     * create article anons
     * @param string $content article fulltext
     * @param number $length number of characters including spaces
     */
    public function anons ($content, $length = 300)
    {
        $anons = '';
        $anons = substr( $content, 0, $length );
        $anons = trim( strip_tags($anons) );
        $anons = str_replace(array("\r\n","\n"), '', $anons);
        $anons = substr( $anons, 0, strrpos($anons, ' ') );
        return $anons;
    }
    
    # ------------------------------------------------------------------------

    /**
     * initialize class properties
     */
    public function init()
    {
        parent::init();
        
        // page meta
        $this->url_site = 'http://'.$_SERVER['HTTP_HOST'];
        $this->url = $this->url_site.Yii::$app->request->url;
        $this->descr = $this->descr ? $this->descr : Yii::$app->view->title;
        
        // check its me
        $this->ip = getenv('REMOTE_ADDR');
        if (strpos($this->ip, '46.39.') !== false) {
            $this->itsme = true;
        }
        
        // check pupil
        if (isset(Yii::$app->session['pupil']['auth']['full_name'])) {
            $this->pupil = true;
        }
    }    
    
    # ------------------------------------------------------------------------
    
    /**
     * generate page exeption for access deniedn user
     */
    public function checkMe() 
    {
        if ($this->itsme === false) {
            throw new \yii\web\HttpException(403, 'Вам запрещен доступ на эту страницу');
        }
    }    
    
    # ------------------------------------------------------------------------
    
    /**
     * added new authorize information
     * @param array $model_attr $model->attributes
     */    
    public function pupilAuth($model_attr) 
    {
        $auth = [];
        $auth['auth'] = $model_attr;
        $auth['auth']['full_name'] = "{$model_attr['last_name']} {$model_attr['first_name']}";
        $auth['auth']['ip'] = getenv('REMOTE_ADDR');
        return $auth;
    }
    
    
    # ------------------------------------------------------------------------
    
    /**
     * added new exam piece
     * @param array $model_attr $model->attributes
     */
    public function pupilExam($model_attr) 
    {
        $exam = [];
        $exam['exam'] = $model_attr;
        return $exam;
    }
	
}
