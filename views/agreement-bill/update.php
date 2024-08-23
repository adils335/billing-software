<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementBill */

$this->title = Yii::t('app', 'Update: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement'), 'url' => ['/agreement/view','id'=>$agreement->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agreement-bill-update">

    <?= $this->render('_form', [
        'model' => $model,
        'agreement' => $agreement,
		'billItem' => $billItem,
		'billTax' => $billTax,
		'billPenality' => $billPenality,
    ]) ?>

</div>
