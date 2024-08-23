<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SiteDues */

$this->title = Yii::t('app', 'Update Site Dues: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Site Dues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="site-dues-update">
<div class="site-dues-index box box-primary"> 
		
		<div class="box-header with-border"> 


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
