<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Employee */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Employees');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 

    $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        
        'emp_code',
        'emp_name',
        
        [
        'header' => 'Company',
        'content' => function($model) {
            return $model->empCompany->name;
        }           
        ],
        
        'mobile',
        
        [
        'header' => 'Joining Date',
        'content' => function($model) {
            return Yii::$app->formatter->asDate($model->joining_date,'php:d-m-Y');
        }           
        ],
        [
        'header' => 'Designation',
        'content' => function($model) {
            return $model->designationName->designation;
        }           
        ],
        [
        'header' => 'Status',
        'content' => function($model) {
            return $model->statusLabel;
        }           
        ],
        
        //'refference',
        //'aadhar',
        //'pancard',
        'expense_balance',
        //'expense_type',
        'personal_balance',
        //'personal_type',
        //'status',
        //'session',
        //'created_at',
        //'created_by',
        //'updated_at',
        //'updated_by',

        ['class' => 'yii\grid\ActionColumn'],
    ];
    ?>
<div class="employee-index">
   <div class="employee-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?>
	   
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Employee'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span>

	</h1>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">

        <?php echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-outline-secondary btn-default'
            ]
            ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]);
    
        ?>


</div>
</div>
</div>
</div>
<?php 
$employeeUrl = Url::to(['employee/ajax-record']);
$formatJs = <<< JS

  $(".employee").click(function(){
       
       var row = this;
       if($(row).hasClass("Active")){
           
           $(row).removeClass("Active");
           $(".employee-record").hide(1500,function(){						
			   $(".employee-record").remove();
		   });
           
       } else{
           
       $("tr").removeClass("Active");
       $(".employee-record").remove();
	   
       var id = $(row).data("key");
       		   
       $.ajax({
           url:'$employeeUrl',
           data:{id:id},
           dataType:"html",
           success:function(res){
               $(row).after("<tr class='employee-record'><td colspan='12'>"+res+"</td></tr>");
               $(".employee-record").show(1500);
			   window.scrollTo({
			   	   top: $('.employee-record').offset().top-50,
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
