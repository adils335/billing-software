<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Account */

$this->title = Yii::t('app', 'Update Account: {name}', [
    'name' => $model->name,
]);

if($model->employee_id)
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee'), 'url' => ['employee/view','id'=>$model->employee_id]];
elseif($model->vendor_id)
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor'), 'url' => ['vendor/view','id'=>$model->vendor_id]];
elseif($model->worker_id)
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker'), 'url' => ['worker/view','id'=>$model->worker_id]];
elseif($model->worker_vendor_id)
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Vendor'), 'url' => ['worker-vendor/view','id'=>$model->worker_vendor_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="account-update">
   <div class="account-update box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
  
      </div> 
   </div>
</div>
