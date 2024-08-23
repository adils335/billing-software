<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyType */

$this->title = Yii::t('app', 'Create Company Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-type-create">
<div class="company-type-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
