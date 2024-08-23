<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementGaurantyType */

$this->title = Yii::t('app', 'Create Agreement Gauranty Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement Gauranty Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-gauranty-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
