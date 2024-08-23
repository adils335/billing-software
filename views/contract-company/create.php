<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContractCompany */

$this->title = Yii::t('app', 'Create Contract Company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contract Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-company-create">
<div class="contract-company-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        // 'modelsAddresses' =>$modelsAddresses,
    ]) ?>

</div>
</div>
</div>
