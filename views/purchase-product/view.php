<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PurchaseProduct $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-product-view box box-primary"> 
    <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </span>

    <br>
    <br>
    <div class="row">
        <div class="col-md-3"><strong>Company: </strong> <?= $model->company->name?></div>
        <div class="col-md-3"><strong>Session: </strong> <?= $model->session?></div>
        <div class="col-md-3"><strong>Invoice Number: </strong> <?= $model->invoice_no?></div>
        <div class="col-md-3"><strong>Invoice Date: </strong> <?= Yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y')?></div>    
    </div>
    <br>

    <div class="row">             
        <table class="table body">
            <thead>
                <tr>
                <th>Sno</th>
                <th>Items</th>
                <th>Uom</th>
                <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sn = 1; 
                foreach($model->purchaseProductItems as $Item):
                ?>
                <tr>
                <td><?= $sn++;?></td>
                <td><?= $Item->storeProducts->name;?></td>
                <td><?= $Item->uom->name;?></td>
                <td><?= $Item->quantity;?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>

    </div>

    </div>
</div>
