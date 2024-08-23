<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = Yii::t('app', 'Update Payment: {name}', [
    'name' => $ref_no,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $ref_no, 'url' => ['view', 'ref_no' => $ref_no]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="payment-update">
   <div class="payment-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
