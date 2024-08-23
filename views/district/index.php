<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\District */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Districts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="district-index">
  <div class="state-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <!--<span class="pull-right">
        <?//= Html::a(Yii::t('app', 'Create District'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>-->
</h1>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'district',
            
		    [
            'header' => 'State',
            'content' => function($model) {
                return $model->state->state;
            }           
            ],
			

            ['class' => 'yii\grid\ActionColumn',
			'template'=>'{update} {delete}'],
        ],
    ]); ?>


</div>
</div>
</div>
