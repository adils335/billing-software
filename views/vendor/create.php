<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vendor */

$this->title = Yii::t('app', 'Create Vendor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-create">
   <div class="vendor-index box box-primary"> 
		
		<div class="box-header with-border"> 


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
