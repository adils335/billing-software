<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Uom */

$this->title = Yii::t('app', 'Create Uom');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Uoms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uom-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
