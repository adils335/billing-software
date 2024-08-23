<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementRateSchedule */

$this->title = Yii::t('app', 'Create Agreement Rate Schedule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement'), 'url' => ['/agreement/view','id'=>$agreement->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-rate-schedule-create">

    <?= $this->render('_form', [
        'model' => $model,
		'agreement' => $agreement,
    ]) ?>

</div>
