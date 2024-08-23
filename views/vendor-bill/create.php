<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VendorBill */

$this->title = Yii::t('app', 'Create Vendor Bill');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-bill-create">

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
