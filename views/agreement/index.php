<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\Agreement;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\Agreement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Agreements'); 
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
            'session',
            'file_no',
            [
                'header' => 'Date',
                'content' => function($model){
                    return Yii::$app->formatter->asDate($model->date,'php:d-m-Y');
                },
                'headerOptions' => ['style' => 'width:8%'],
            ],
            [
                'header' => 'agreement_name',
                'content' => function($model){
                    return $model->agreement_name;
                },
                'headerOptions' => ['style' => 'width:15%'],
            ],
            [
                'header' => 'agreement_no',
                'content' => function($model){
                    return $model->agreement_no;
                },
                'headerOptions' => ['style' => 'width:15%'],
            ],
            [
                'header' => 'District',
                'content' => function($model){
                    return $model->contractCompanyDistrict->district;
                }
            ],
            [
                'header' => 'Bill',
                'content' => function($model){
                    return $model->totalBill();
                },
                /*'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'text-right'];
                },*/
            ],
            [
                'header' => 'Cost',
                'content' => function($model){
                    return $model->cost;
                },
                /*'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'text-right'];
                },*/
            ],
            [
                'header' => 'Consume',
                'content' => function($model){
                    return $model->billing();
                },
                /*'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'text-right'];
                },*/
            ],
            [
                'header' => 'Balance',
                'content' => function($model){
                    return $model->balance();
                },
                /*'contentOptions' =>function ($model, $key, $index, $column){
                    return ['class' => 'text-right'];
                },
            ],
            
                },*/
            ]
    ]; 
    
?>


<div class="agreement-index">
<div class="agreement-index box box-primary">

    <div class="box-header with-border">
        <div class="pull-right">
            <?= Html::a(Yii::t('app', 'Create Agreement'), ['create-agreement'], ['class' => 'btn btn-success btn-flat btn-xs']) ?>
        </div>
    </div>
    <div class="box-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
        <?php echo ExportMenu::widget([ 
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-outline-secondary btn-default'
            ]
            ]);
        echo GridView::widget([
            'rowOptions'=>function ($model, $key, $index, $grid){
                return ['class'=>'agreement clickable'];
            },
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns
        ]);
    
        ?>
    </div>

</div>
</div>
<?php 
$indexByAjax = Url::to(['agreement/index-by-ajax']);
$formatJs = <<< JS
  $(".agreement").click(function(event){
       event.stopPropagation();
       var row = this;
       if($(row).hasClass("Active")){
           
           $(row).removeClass("Active");
           $(".agreement-record").hide(1500,function(){						
			   $(".agreement-record").remove();
		   });
           
       } else{
           
       $("tr").removeClass("Active");
       $(".agreement-record").remove();
	   
       var id = $(row).data("key");
       		   
       $.ajax({
           url:'$indexByAjax',
           data:{id:id},
           dataType:"html",
           success:function(res){
               $(row).after("<tr class='agreement-record'><td colspan='12'>"+res+"</td></tr>");
               $(".agreement-record").show(1500);
			   window.scrollTo({
			   	   top: $('.agreement-record').offset().top-50,
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
