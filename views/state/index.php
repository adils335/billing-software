<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\State */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'States');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="state-index">
  <div class="state-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create State'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>
</h1>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'state',
            'state_tin',
            'state_code',

            ['class' => 'yii\grid\ActionColumn',
			 'template'=>'{update} {delete}'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
