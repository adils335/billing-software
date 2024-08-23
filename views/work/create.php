<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Work */

$this->title = Yii::t('app', 'Create Work');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Works'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-create">
   <div class="work-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
