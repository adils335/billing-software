<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementBill */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mode-of-transport-view">
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="box-body">

                <h1><?= Html::encode($this->title) ?></h1>
            
                <p>
                    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'agreement_id',
                        'invoice_no',
                        'invoice_date',
                        'order_no',
                        'work_name',
                        'estimate_no',
                        'section_name',
                        'start_date',
                        'complete_date',
                        'circle_name',
                        'base_amount',
                        'schedule',
                        'schedule_rate',
                        'schedule_amount',
                        'taxable_amount',
                        'tax_amount',
                        'payable_amount',
                        'deduction_amount',
                        'pay_amount',
                        'company_id',
                        'session',
                        'created_at',
                        'created_by',
                        'updated_at',
                        'updated_by',
                        /*[
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{sync}',
                            'buttons' => [
                                'sync' => function ($url, $model, $key) {
                                  return  Html::a('<i class="fas fa-sync"></i>',['sync','id'=>$model->id],
                                  ['title'=>'Sync','aria-label'=>'Sync','data-pjax'=>'0','data-confirm'=>'Are you sure you want to sync?','data-method'=>'post']);
                                  
                                },
                             ]
                        ],*/
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>

