<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseBill */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Purchase Bills'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-bill-view box box-primary">

	<div class="box-header with-border"> 
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
            'name',
            [
                'attribute'=>'company_id',
                'value' => function($model){
                    return $model->company->name;
                }
            ],
            [
                'attribute'=>'state_id',
                'value' => function($model){
                    return $model->state->state;
                }
            ],
            'company_gstin',
            'gstin',
            'invoice_no',
            [
                'attribute'=>'date',
                'value' => function($model){
                    return \Yii::$app->formatter->asDate($model->date,'php:d-m-Y');
                }
            ],
            'amount',
            'tax',
            'total',
            [
                'attribute'=>'file',
                'label'=>'Attachment',
                'format'=>'html',
                'value' => function($model){
                    $url = \Yii::getAlias('@web/upload/purchase-bill/'). $model->file;
                    return Html::a('View',[$url]);
                    //return '<a href="'. Yii::getAlias('@webroot')."/upload/purchase-bill/". $model->file .'">View</a>';
                }
            ],
        ],
    ]) ?>
    
    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'particular',
        'rate',
        'quantity',
        'amount',
        [
         'header'=>'Tax',
         'content'=>function($model){
             $html = '<table class="table">
                         <thead>
                            <tr>
                               <th>Tax</th>
                               <th>Rate</th>
                               <th>Tax Amount</th>
                            </tr>
                         </thead>
                         <tbody>';
                         foreach($model->purchaseBillItemsTaxes as $tax){
                            $html .= '
                            <tr>
                               <td>'. $tax->tax->name .'</td>
                               <td>'. $tax->rate .'</td>
                               <td>'. $tax->tax_amount .'</td>
                            </tr>';  
                         }
                            
                        $html .='</tbody>
                      </table>';
             return  $html;
         }
        ],
        'total'
    ],
    ]); ?>
  </div>
</div>
