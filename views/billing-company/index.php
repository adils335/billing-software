<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\BillingCompany */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Billing Parties');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="billing-company-index">
<div class="billing-company-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Billing Party'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{view} {update} {delete} {gst}',
			'buttons' => [
                'gst' => function($url, $model, $key) {     // render your custom button
                    return Html::a("<span class='fa fa-plus'>GST</span>",['company-gst','id'=>$model->id],['class'=>'']);
                }
            ]
			],
        ],
    ]); ?>

  
</div>
</div>
</div>
</div>
