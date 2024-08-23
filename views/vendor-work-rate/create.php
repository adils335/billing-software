<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\VendorWorkRate */

$this->title = Yii::t('app', 'Create Work Rate');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Rates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-work-rate-create">
   <div class="vendor-work-rate-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
