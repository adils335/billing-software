<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AgreementProduct $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agreement Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agreement-product-view box box-primary"> 
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
            <div class="col-md-2"><strong>State: </strong> <?= $model->state->state?></div>
            <div class="col-md-2"><strong>District: </strong> <?= $model->district->district?></div>
            <div class="col-md-4"><strong>Billing Company: </strong> <?= $model->billingCompany->name?></div>
            <div class="col-md-4"><strong>Agreement Number: </strong> <?= $model->agreement->agreement_no?></div>    
        </div>
        <br>
        <div class="row ">             
            <table class="table body">
                <thead>
                    <tr>
                    <th>Sno</th>
                    <th>Store Products</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1; 
                    foreach($model->agreementProductItems as $Item):
                    ?>
                    <tr>
                    <td><?= $sn++;?></td>
                    <td><?= $Item->products->name;?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>

        </div>
        

    </div>
</div>
