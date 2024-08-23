<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Designation */

$this->title = Yii::t('app', 'Create Designation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Designations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="designation-create">
<div class="designation-index box box-primary"> 
		
		<div class="box-header with-border">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
