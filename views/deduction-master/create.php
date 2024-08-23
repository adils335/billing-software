<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeductionMaster */

$this->title = Yii::t('app', 'Create Deduction Master');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Deduction Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deduction-master-create">
   <div class="deduction-master-create box box-primary"> 
        
        <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
