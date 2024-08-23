<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Signature Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signature-master-index">
  <div class="signature-master-index box box-primary">
      <div class="box-header with-border">
        <h1><?= Html::encode($this->title) ?>
        <span class="pull-right">
            <?= Html::a(Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
        </span>
        </h1>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label'=>'Company',
                        'attribute'=>'company_id',
                        'content'=>function($model){
                            return $model->company->name;
                        }
                    ],
                    [
                        'label'=>'Type',
                        'attribute'=>'type_id',
                        'content'=>function($model){
                            return $model->type->type;
                        }
                    ],
                    [
                        'label'=>'Signature',
                        'attribute'=>'signature',
                        'content'=>function($model){
                            return $model->signature;
                        }
                    ],
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
     </div>
  </div>
</div>
