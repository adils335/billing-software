<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SiteDues */

$this->title = Yii::t('app', 'Create Site Dues');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Site Dues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-dues-create">
<div class="site-dues-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
