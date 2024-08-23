<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleRateMaster */

$this->title = Yii::t('app', 'Update Schedule Rate');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schedule Rate'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="schedule-rate-master-update">
<div class="schedule-rate-master-index box box-primary"> 
        
        <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
