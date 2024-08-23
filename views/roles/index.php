<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Roles */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="roles-index">

   <div class="roles-index box box-primary"> 
        
        <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Roles'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


       </div>
    </div>
</div>
