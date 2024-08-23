<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WorkType */

$this->title = Yii::t('app', 'Create Work Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-type-create">
   <div class="work-type-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
