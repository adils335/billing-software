<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\ScheduleRateMaster */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Schedule Rate');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="schedule-rate-master-index">
<div class="schedule-rate-master-index box box-primary"> 
        
    <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Schedule Rate'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
</h1>


    <div class="box-body">
    <?=$this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'rowOptions'=>function ($model, $key, $index, $grid){
             return ['class'=>'schedule-rate-master','srmid'=>$model->srmid];
          },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'type',
            //'item:ntext',
            //'hsn_no',
            //'unit',
            //'company_id',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',

            ['class' => 'yii\grid\ActionColumn',
             'template'=> '{update} {delete}',
             'urlCreator' => function ($action, $model, $key, $index) {
                   if($action == "update")
                        return Url::to(['schedule-rate-master/update','srmid' => $model['srmid']]);
                   if($action == "delete")
                        return Url::to(['schedule-rate-master/delete','srmid' => $model['srmid']]);   
               } 
            ],
        ],
    ]); ?>


</div>

</div>

</div>

</div>
<?php 
$rateMasterUrl = Url::to(['schedule-rate-master/ajax-record-by-srmid']);
$formatJs = <<< JS

  $(".schedule-rate-master").click(function(){
       
       var row = this;
       if($(row).hasClass("Active")){
           
           $(row).removeClass("Active");
           $(".rate-master-record").hide(1500,function(){                      
               $(".rate-master-record").remove();
           });
           
       } else{
           
       $("tr").removeClass("Active");
       $(".rate-master-record").remove();
       
       var id = $(row).attr("srmid");
               
       $.ajax({
           url:'$rateMasterUrl',
           data:{srmid:id},
           dataType:"html",
           success:function(res){
               $(row).after("<tr class='rate-master-record'><td colspan='12'>"+res+"</td></tr>");
               $(".rate-master-record").show(1500);
               window.scrollTo({
                   top: $('.rate-master-record').offset().top-50,
                   left: 0,
                   behavior: 'smooth'
               })
               $(row).addClass("Active");
           }
       });
       
       }
   });

JS;
 
// Register the formatting script
$this->registerJs($formatJs);
