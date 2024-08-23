<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Bill Back Masters');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bill-back-master-index">
   <div class="bill-back-master-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'New'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>
    </h1>


    <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions'=>function ($model, $key, $index, $grid){
             return ['class'=>'bill-back-master','srmid'=>$model->srmid];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'type',

            ['class' => 'yii\grid\ActionColumn',
             'template'=> '{update} {delete}',
             'urlCreator' => function ($action, $model, $key, $index) {
                   if($action == "update")
                        return Url::to(['bill-back-master/update','srmid' => $model['srmid']]);
                   if($action == "delete")
                        return Url::to(['bill-back-master/delete','srmid' => $model['srmid']]);   
               } 
            ],
        ],
    ]); ?>


</div>
</div>
</div>
</div>

<?php 
$backMasterUrl = Url::to(['ajax-record-by-srmid']);
$formatJs = <<< JS

  $(".bill-back-master").click(function(){
       
       var row = this;
       if($(row).hasClass("Active")){
           
           $(row).removeClass("Active");
           $(".back-master-record").hide(1500,function(){                      
               $(".back-master-record").remove();
           });
           
       } else{
           
       $("tr").removeClass("Active");
       $(".back-master-record").remove();
       
       var id = $(row).attr("srmid");
               
       $.ajax({
           url:'$backMasterUrl',
           data:{srmid:id},
           dataType:"html",
           success:function(res){
               $(row).after("<tr class='back-master-record'><td colspan='12'>"+res+"</td></tr>");
               $(".back-master-record").show(1500);
               window.scrollTo({
                   top: $('.back-master-record').offset().top-50,
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
