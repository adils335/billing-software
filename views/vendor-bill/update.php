<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VendorBill */

$this->title = Yii::t('app', 'Update Vendor Bill: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vendor-bill-update">

   <div class="vendor-bill-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?= $this->render('_form', [
        'model' => $model,
		'billItem' => $billItem,
		'billTax' => $billTax,
		'billDeduction' => $billDeduction,
    ]) ?>

</div>
</div>
</div>
