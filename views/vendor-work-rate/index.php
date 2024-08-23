<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\VendorWorkRate */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Work Rates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-work-rate-index">
   <div class="vendor-work-rate-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Work Rate'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>

    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
             'header' => 'Vendor',
             'content' => function($model){
                   return $model->vendor->name;
             }
            ],

            [
             'header' => 'Work Description',
             'content' => function($model){

                 $html = "<table class='table'>
                          <thead>
                            <tr>
                               <th>Work Type</th>
                               <th>Work Name</th>
                               <th>Rate</th>
                            </tr>
                          </thead>
                          <tbody>";
                 $workRate = $model->workDescription();

                     $count = 1;
                     foreach ($workRate as $key => $work) {
                         $html .= "<tr>";
                         if($count == 1)
                             $html .= "<td rowspan='".count($workRate)."'>".$work->workType->name."</td>";
                         $html .= "<td>".$work->workName->name."</td>";
                         $html .= "<td>".$work->rate."</td>";
                         $html .= "</tr>";
                         $count++;
                     }

                 $html .= "</tbody></table>";
                 return $html;
             }
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}',
                
            'urlCreator' => function ($action, $model, $key, $index) {
                   if($action == "update")
                        return Url::to(['vendor-work-rate/update','vendor_id' => $model['vendor_id'],'work_type'=>$model['work_type']]);
                   if($action == "delete")
                        return Url::to(['vendor-work-rate/delete','vendor_id' => $model['vendor_id'],'work_type'=>$model['work_type']]);    
                   if($action == "view")
                        return Url::to(['vendor-work-rate/view','vendor_id' => $model['vendor_id'],'work_type'=>$model['work_type']]);  
               }
            ],
        ],
    ]); ?>


</div>
</div>
</div>
</div>
