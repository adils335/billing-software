<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseBill */

$this->title = Yii::t('app', 'Create Purchase Bill');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Purchase Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-bill-create box box-primary">
	<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'itemsTax'=>$itemsTax
    ]) ?>
    
    </div>
</div>
