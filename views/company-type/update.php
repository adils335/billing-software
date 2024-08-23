<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyType */

$this->title = Yii::t('app', 'Update Company Type: {name}', [
    'name' => $model->type,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="company-type-update">
<div class="company-type-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
