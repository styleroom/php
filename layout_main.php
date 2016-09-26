<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo  Yii::$app->language ?>">
<head>
    <meta charset="<?php echo  Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="<?php echo str_replace(['&quot;','&laquo;','&raquo;'], '', $this->title); ?>">
    <meta property="og:description" content="<?php echo Yii::$app->pv->descr; ?>">
    <meta property="og:image" content="<?php echo Yii::$app->pv->ogimg; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content= "<?php echo Yii::$app->pv->url; ?>">    
    <?php echo  Html::csrfMetaTags() ?>
    <title><?php echo str_replace(['&quot;','&laquo;','&raquo;'], '', $this->title); ?></title>
    <meta name="description" content="<?php echo Yii::$app->pv->descr; ?>">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    
    <?php echo $content; ?>
    
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <a href="/"><?php echo Yii::$app->name; ?></a></p>

        <p class="pull-right"><?php echo  Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>    
    
<?php echo $this->render('metrika'); ?>  
    
</body>
</html>
<?php $this->endPage() ?>
