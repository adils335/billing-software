<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\EmployeeLeave */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employee Leaves');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
    $gridColumns =[
    ['class' => 'yii\grid\SerialColumn'],
    [
        'label'=>'Name',
        'content'=>function($model){
            return $model->employee->emp_code." ".$model->employee->emp_name;
        }
    ],
    [
        'label'=>'Month',
        'content'=>function($model){
            return Yii::$app->formatter->asDate($model->month,'php:M-Y');
        }
    ],
    [
        'label'=>'Total Leave',
        'content'=>function($model){
            $leaves = json_decode($model->comments,true);
            return "<b class='text-danger'>".count($leaves)."</b>";
        }
    ],
    
    [
        'label'=>'Leave',
        'content'=>function($model){
            $html = "";
            $leaves = json_decode($model->comments,true);
            $i = 1;
            if($leaves)
            foreach($leaves as $key => $leave){
                $remove = "";
                //if(!\app\models\EmployeeSalary::find()->where(['employee_id'=>$model->employee_id,'month'=>$model->month])->exists()){
                  $remove = '<a href="javascript:void(0)" title="Delete" aria-label="Delete" data-date="'.$key.'" data-id="'.$model->employee_id.'" class="remove-leave">
                             <span class="glyphicon glyphicon-trash"></span>
                           </a>';  
                //}
                $date = Yii::$app->formatter->asDate($key,'php:d-m-Y'); 
                $html .= "<div class='row leave-row'><div class='col-sm-4'>$date</div><div class='col-sm-6'>$leave</div><div class='col-sm-2'>$remove</div></div>";
                $i++;
            }
            return $html;
        }
    ]
    ]
?>
<div class="employee-leave-index">
    <div class="employee-leave-index box box-primary">
            <div class="box-header with-border">
                
			 <div class="box-body">  

    <h1><?= Html::encode($this->title) ?>

    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'new'), ['leave'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>    
    </h1>


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">

        <? echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-outline-secondary btn-default'
            ]
        ]) . "<hr>\n".
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]);
    
    ?>




</div>
</div>
</div>
</div>

<?php
$RemoveLeaveUrl = Url::to(['employee-leave/ajax-remove-leave']);
$script = <<< JS
   $(".remove-leave").click(function(){
       var index = $(".remove-leave").index($(this));
       var parentDiv = $(".leave-row").eq(index);
       var what = confirm("Are you sure! you want to delete it.");
              if(!what){
                  return false;
              }
              var date = $(this).attr("data-date");
              var employee_id = $(this).attr("data-id");
              $.ajax({
                url:"$RemoveLeaveUrl",
                data:{employee:employee_id,date:date},
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

