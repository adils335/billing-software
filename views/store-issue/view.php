<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\StoreIssue $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Store Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="store-issue-view box box-primary"> 
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
            <div class="col-md-4"><strong>Indent Number: </strong> <?= $model->indent_no?></div>
            <div class="col-md-4"><strong>Session: </strong> <?= $model->session?></div>
            <div class="col-md-4"><strong>Date : </strong> <?= Yii::$app->formatter->asDate($model->date,'php:d-m-Y')?></div>
            
        </div>
        <br>
        <div class="row">
            <div class="col-md-4"><strong>Company Name : </strong> <?= $model->company->name?></div>
            <div class="col-md-4"><strong>State : </strong> <?= $model->state->state?></div> 
            <div class="col-md-4"><strong>District: </strong> <?= $model->district->district?></div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4"><strong>Billing Company Name: </strong> <?= $model->billingCompany->name?></div>
            <div class="col-md-4"><strong>Agreement Number : </strong> <?= $model->agreement->agreement_no?></div>
            <div class="col-md-4"><strong>Site : </strong> <?= $model->site->name?></div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-4"><strong>Gate Pass No : </strong> <?= $model->gate_pass_no?></div>
            <div class="col-md-4"><strong>Status : </strong> <?= $model->statusLabel?></div>
        </div>

        <div class="row store-indents-items">             
            <table class="table store-indents-items-body">
                <thead>
                    <tr>
                    <th>Sno</th>
                    <!-- <th>Gate Pass No</th> -->
                    <th>Store Products</th>
                    <th>Uom</th>
                    <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1; 
                    foreach($model->storeIssueItems as $Item):
                    ?>
                    <tr>
                    <td><?= $sn++;?></td>
                    <!-- <td><?php //echo$Item->gate_pass_no;?></td> -->
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
