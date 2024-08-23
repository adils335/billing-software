<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ControllerAction */

$this->title = Yii::t('app', 'Create Controller Action');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Controller Actions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="controller-action-create">
<div class="contract-company-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'actionArray' => $actionArray,
        'controllers' => $controllers,
    ]) ?>

</div>
</div>
</div>
