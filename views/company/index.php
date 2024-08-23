<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Company */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">
<div class="company-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Company'), ['create'], ['class' => 'btn btn-success']) ?>
    </span></h1>


    <div class="box-body">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
			
		    [
            'header' => 'Type',
            'content' => function($model) {
                return $model->companyType->type;
            }           
            ],
            'address:ntext',
            
		    [
            'header' => 'State',
            'content' => function($model) {
                return $model->stateName->state;
            }           
            ],
			
		    [
            'header' => 'District',
            'content' => function($model) {
                return $model->districtName->district;
            }           
            ],
            'pincode',
            'person',
            //'number',
            //'email:email',
            //'pancard_no',
            //'gst_no',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


       </div>
	</div>
</div>
</div>

