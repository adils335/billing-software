<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sites */

$this->title = Yii::t('app', 'Create Sites');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company'), 'url' => ['../contract-company']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'view'), 'url' => ['../contract-company/view','id'=>$company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sites-create">
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
