<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\VendorBill */

$this->title = $model->session."/".$model->bill_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vendor-bill-view">
<div class="vendor-bill-view box box-primary">
              
    <div class="box-header">
<h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </span>
</h1>

    <div class="box-body">
        <div class="agreement-view"> 
            
               <?= $this->render('view/_bill', ['model' => $model])?>
            
        </div>
        
    </div>
</div>
</div>
</div>
