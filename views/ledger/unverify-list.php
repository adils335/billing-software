<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Payment */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Unverify Account');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">
   <div class="payment-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?php  echo $this->render('_unverify-search', ['model' => $searchModel]); ?>
    
    <div class="box-body">
	
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn','sort'=>SORT_DESC],
            [
                'header' => 'S. No.',
                'content' => function ($model, $key, $index, $column) {
                     $provider = $column->grid->dataProvider;
                      return $provider->totalCount-$provider->getPagination()->getOffset() - $index;
                },
                'contentOptions'=>['style'=>'width: 10%;']
            ],
			
		        [
                'header' => 'Account',
                'content' => function($model) {
                    return $model->accountName;
                },
                'contentOptions'=>['style'=>'width: 50%;']           
            ],
			
			
            ['class' => 'yii\grid\ActionColumn','template'=>'{view} {personal} {expense}',
			        'buttons'=>[
			           'personal'=>function ($url, $model, $key) {
                              return Html::a('P', $url, ['class' => 'btn btn-info']);
                          },
			           'expense'=>function ($url, $model, $key) {
                              return Html::a('E', $url, ['class' => 'btn btn-info']);
                          },
			        ],
			        'visibleButtons' => [
                        'personal' => function ($model, $key, $index) {
                            return $model['type'] === $model::TYPE_EMPLOYEE && $model->hasUnverify($model::ACCOUNT_PERSONAL);
                        },
                        'expense' => function ($model, $key, $index) {
                            return $model['type'] === $model::TYPE_EMPLOYEE && $model->hasUnverify($model::ACCOUNT_EXPENSE);
                        },
                        'view' => function ($model, $key, $index) {
                            return $model['type'] !== $model::TYPE_EMPLOYEE;
                        }
                    ],
			        'urlCreator' => function ($action, $model, $key, $index) {
			         	   if($action == "view" ){
                                 return Url::to(['ledger/unverify-ledger','Ledger[company_id]' => $model['company_id'],'Ledger[type]' => $model['type'],'Ledger[account]' => $model['account']]);
			         	   }
			         	   if($action == "personal"){
                                 return Url::to(['ledger/unverify-ledger','Ledger[company_id]' => $model['company_id'],'Ledger[type]' => $model['type'],'Ledger[account]' => $model['account'],'Ledger[account_type]'=>1]);
			         	   }
			         	   if($action == "expense"){
                                 return Url::to(['ledger/unverify-ledger','Ledger[company_id]' => $model['company_id'],'Ledger[type]' => $model['type'],'Ledger[account]' => $model['account'],'Ledger[account_type]'=>2]);
			         	   }
               },
               'contentOptions'=>['style'=>'width: 10%;']
			],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
