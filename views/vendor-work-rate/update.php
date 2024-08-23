<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VendorWorkRate */

$this->title = Yii::t('app', 'Update Work Rate: {name}', [
    'name' => $record->vendor->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Rates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $record->vendor->name, 'url' => ['view', 'vendor_id' => $record->vendor_id,'work_type'=>$record->work_type]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vendor-work-rate-update">
   <div class="vendor-work-rate-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
