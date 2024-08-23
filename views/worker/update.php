<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Worker */

$this->title = Yii::t('app', 'Update Worker: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="worker-update">
    <div class="worker-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
