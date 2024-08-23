<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContractCompany */

$this->title = Yii::t('app', 'Update Contract Company: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contract Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="contract-company-update">
<div class="contract-company-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        //'modelsAddresses' =>$modelsAddresses,
    ]) ?>


</div>

</div>
</div>
