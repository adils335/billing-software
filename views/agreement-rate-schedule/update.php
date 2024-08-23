<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementRateSchedule */

$this->title = Yii::t('app', 'Update Agreement Rate Schedule for file: {name}', [
    'name' => $agreement->file_no,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement'), 'url' => ['/agreement/view','id'=>$agreement->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="agreement-rate-schedule-update">

    <?= $this->render('_form', [
        'model' => $model,
		'agreement' => $agreement,
    ]) ?>

</div>
