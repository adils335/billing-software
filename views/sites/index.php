<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sites');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sites-index">
<div class="sites-index box box-primary"> 
		
		<div class="box-header with-border">
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Sites'), ['create'], ['class' => 'btn btn-success']) ?>
    </span></h1>



    <div class="box-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            
		    [
            'header' => 'State',
            'content' => function($model) {
                return $model->state->state;
            }           
            ],
			
		    [
            'header' => 'district',
            'content' => function($model) {
                return $model->district->district;
            }           
            ],

            
            ['class' => 'yii\grid\ActionColumn',
			 'template'=> '{update}{archive}{delete}',
			 'buttons' => [
                'archive' => function ($url, $model, $key) {
					   if($model->status == $model::ACTIVE_STATUS){
					      return Html::a('<i class="fa fa-archive"></i>', ['sites/archive', 'id' => $model->id], [
                                   'class' => 'btn-primary',
                                   'data-confirm' => 'Are you sure?',
                                   'data-method' => 'post',
                                 ]);
					   }elseif($model->status == $model::ARCHIVE_STATUS){
					       return Html::a('<i class="fa fa-undo"></i>', ['sites/un-archive', 'id' => $model->id], [
                                   'class' => 'btn-primary',
                                   'data-confirm' => 'Are you sure?',
                                   'data-method' => 'post',
                                 ]);
					   }
                    },	
                ],	
			],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
