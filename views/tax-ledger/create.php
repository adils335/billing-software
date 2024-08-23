<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TaxLedger */

$this->title = Yii::t('app', 'Create Tax Ledger');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tax Ledgers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-ledger-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
