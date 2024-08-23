<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sessions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="session-index">
   <div class="session-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
       <span class="pull-right">
          <?= Html::a(Yii::t('app', 'Create Session'), ['create'], ['class' => 'btn btn-success']) ?>
       </span>
    </h1>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
		
		    [
            'header' => '',
            'content' => function($model) {
				$class = $model->status == $model::STATUS_DEACTIVE?"text-danger":"text-success";
                return Html::a("<span class='fa fa-circle $class'></span>",['change-status','id'=>$model->id]);
            }           
            ],
            ['class' => 'yii\grid\SerialColumn'],
           
            'session',
             
		    [
            'header' => 'Status',
            'content' => function($model) {
                return $model->statusLabel;
            }           
            ],
			
            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{update} {delete}'],
        ],
    ]); ?>

       </div>
	</div>
</div>
</div>
