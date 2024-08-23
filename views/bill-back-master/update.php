<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BillBackMaster */

$this->title = Yii::t('app', 'Update Bill Back Master: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bill Back Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="bill-back-master-update">
   <div class="bill-back-master-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
