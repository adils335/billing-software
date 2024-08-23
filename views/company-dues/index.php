<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\CompanyDues */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Company Dues');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-dues-index">
<div class="company-dues-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Company Dues'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>

    <div class="box-body">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'father_name',
            'mobile',
            
            [
                'header' => 'Company',
                'content' => function($model){
                    return $model->company->name;
                }
            ],
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
</div>
</div>

</div>
