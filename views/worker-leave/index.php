<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\WorkerLeave */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Worker Leaves');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-leave-index">
    <div class="worker-leave-index box box-primary">
            <div class="box-header with-border">
                
			 <div class="box-body">  

    <h1><?= Html::encode($this->title) ?>

    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'new'), ['leave'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>    
    </h1>


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Name',
                'content'=>function($model){
                    return $model->worker->code." ".$model->worker->name;
                }
            ],
            [
                'label'=>'Month',
                'content'=>function($model){
                    return Yii::$app->formatter->asDate($model->month,'php:M-Y');
                }
            ],
            [
                'label'=>'Leave',
                'content'=>function($model){
                    $html = "";
                    $leaves = json_decode($model->comments,true);
                    if($leaves)
                    foreach($leaves as $key => $leave){
                        $remove = "";
                        //if(!\app\models\EmployeeSalary::find()->where(['employee_id'=>$model->employee_id,'month'=>$model->month])->exists()){
                          $remove = '<a href="javascript:void(0)" title="Delete" aria-label="Delete" data-date="'.$key.'" data-id="'.$model->worker_id.'" class="remove-leave">
                                     <span class="glyphicon glyphicon-trash"></span>
                                   </a>';  
                        //}
                        $date = Yii::$app->formatter->asDate($key,'php:d-m-Y'); 
                        
                        $html .= "<div class='row leave-row'><div class='col-sm-4'>$date</div><div class='col-sm-6'>$leave</div><div class='col-sm-2'>$remove</div></div>";
                    }
                    return $html;
                }
            ],
        ],
    ]); ?>


</div>
</div>
</div>
</div>

<?php
$RemoveLeaveUrl = Url::to(['worker-leave/ajax-remove-leave']);
$script = <<< JS
   $(".remove-leave").click(function(){
       var index = $(".remove-leave").index($(this));
       var parentDiv = $(".leave-row").eq(index);
       var what = confirm("Are you sure! you want to delete it.");
              if(!what){
                  return false;
              }
              var date = $(this).attr("data-date");
              var worker_id = $(this).attr("data-id");
              $.ajax({
                url:"$RemoveLeaveUrl",
                data:{worker:worker_id,date:date},
                success:function(res){
                    if(res.status == "success"){
                        $("#success-modal .message").html("Leave deleted successfully");
                        $("#success-modal").modal("show"); 
                        parentDiv.remove();
                    }else{
                        $("#error-modal .message").html(res.error);
                        $("#error-modal").modal("show");
                    }
                }
              });
   });
   
JS;
$this->registerJS($script);

