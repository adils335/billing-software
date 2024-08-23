<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AllowanceMaster */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Allowance Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allowance-master-index">
   <div class="allowance-master-index box box-primary"> 
        
        <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Allowance Master'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>


    <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
