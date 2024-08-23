<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\WorkType */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Work Types');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="work-type-index">
   <div class="work-type-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Work Type'), ['create'], ['class' => 'btn btn-success']) ?>
    </span></h1>


    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
