<?php

use app\models\StoreIssue;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Search\StoreIssue $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Store Issues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-issue-index box box-primary"> 
    <div class="box-header with-border">

    <span class="pull-right">
        <?= Html::a('Create Store Issue', ['create'], ['class' => 'btn btn-success']) ?>
    </span>
    <br>
    <br>
    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'indent_no',
            [
                'header'=>'Date',
                'content' => function($model) {
                    return Yii::$app->formatter->asDate($model->date,'php:d-m-Y');
                }
            ],
            [
                'header'=>'Compnay Name',
                'content'=>function($model){
                    return $model->company->name;
                }
            ],
            [
                'header'=>'Billing Company Name',
                'content'=>function($model){
                    return $model->billingCompany->name;
                }
            ],

            'gate_pass_no',

            [
                'header' => 'Status',
                'content' => function($model) {
                    return $model->StatusLabel;
                }           
            ],
            [
                'class' => ActionColumn::className(),
                'template'=> '{view}&nbsp;&nbsp{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{pdf}',
                'buttons' => [
                    'pdf' => function ($url, $model, $key) {  
                        return Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
                               ['store-issue-file','id'=>$model->id],
                            ['target'=>'_blank']);
                        },	
                    ],
                'urlCreator' => function ($action, StoreIssue $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    </div>
</div>
