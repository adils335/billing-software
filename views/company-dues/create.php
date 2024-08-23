<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyDues */

$this->title = Yii::t('app', 'Create Company Dues');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Company Dues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-dues-create">
<div class="company-dues-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
