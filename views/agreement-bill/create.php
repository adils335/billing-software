<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementBill */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement'), 'url' => ['/agreement/view','id'=>$agreement->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-bill-create">

    <?= $this->render('_form', [
        'model' => $model,
        'agreement' => $agreement,
		'billItems' => $billItems,
		'billTaxes' => $billTaxes
    ]) ?>

</div>
