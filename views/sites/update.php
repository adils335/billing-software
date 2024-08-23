<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */

$this->title = Yii::t('app', 'Update Sites: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sites-update">
<div class="sites-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'company_id' => $company_id
    ]) ?>

</div>
</div>
</div>
