<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Work */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Works');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-index">
   <div class="work-index box box-primary"> 
		
		<div class="box-header with-border">

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Work'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>
</h1>

    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            
		    [
            'header' => 'Unit',
            'content' => function($model) {
                return $model->unitName->name;
            }           
            ],
			
		    [
            'header' => 'Work Type',
            'content' => function($model) {
                return $model->workType->name;
            }           
            ],
			

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
