<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AllowanceMaster */

$this->title = Yii::t('app', 'Create Allowance Master');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Allowance Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allowance-master-create">
   <div class="allowance-master-create box box-primary"> 
        
        <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
