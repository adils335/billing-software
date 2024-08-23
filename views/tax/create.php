<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tax */

$this->title = Yii::t('app', 'Create Tax');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Taxes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-create">
<div class="tax-index box box-primary"> 
		
		<div class="box-header with-border">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
