<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BillingCompany */

$this->title = Yii::t('app', 'Create Billing Party');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Billing Parties'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-company-create">
<div class="billing-company-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
