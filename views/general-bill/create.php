<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementBill */

$this->title = Yii::t('app', 'Create General Bill');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'General Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-bill-create">

    <?= $this->render('_form', [
        'model' => $model,
        'agreement' => $agreement,
		'billItem' => $billItem,
		'billTax' => $billTax,
		'billDeduction' => $billDeduction,
    ]) ?>

</div>
