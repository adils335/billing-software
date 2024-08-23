<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BillBackMaster */

$this->title = Yii::t('app', 'Create Bill Back Master');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bill Back Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-back-master-create">
   <div class="bill-back-master-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
