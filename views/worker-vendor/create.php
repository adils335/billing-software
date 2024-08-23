<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WorkerVendor */

$this->title = Yii::t('app', 'Create Worker Vendor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-vendor-create">
    <div class="sites-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
