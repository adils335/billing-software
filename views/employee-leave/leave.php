<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
$this->title = Yii::t('app', 'Employee Leaves');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employee Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="employee-leave-details box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Employee Leave')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
			     <div class="row">
			         <div class="col-sm-6">
			              <?php $form = ActiveForm::begin([
			              'action'=>Url::to(['employee-leave/mark-leave'])
			              ]); ?>
			                  <?= $form->field($model, 'employee_id')->widget(Select2::classname(), [
                                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->select(['id','CONCAT(emp_name,"-",emp_code) as name'])->orderBy(['emp_name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
                                     'options' => ['placeholder' => 'Select ...'],
                                     'pluginOptions' => [
                                             'allowClear' => true,
                                         ],
                              ]);?>
                              <?= $form->field($model, 'leave')->widget(DatePicker::classname(), [
                                     'type' => DatePicker::TYPE_INLINE,
                                     'pluginOptions' => [
                                         'autoclose'=>true,
                                         'format' => 'yyyy-mm-dd',
                                         //'updateViewDate'=>false,
                                         'todayHighlight'=>true,
                                         'multidate' => true
                                     ],
                                     'options' => [
                                          'style' => 'display:none'
                                     ]
                              ])->label(false);?>
                              <?= $form->field($model,'comments')->textarea(); ?>
                              <?= Html::submitButton('Save',['class'=>'btn btn-success']);?>
			              <?php ActiveForm::end(); ?>
			         </div>
			         <div class="col-sm-6">
			             <h3>Search Leave</h3>
                         <div class="form-group">
                             <label>Start Month</label>
                             <?= DatePicker::widget([
	                              'name' => 'start_month', 
	                              'id' => 'start_month', 
	                              'options' => ['placeholder' => 'Select month ...'],
	                              'pluginOptions' => [
	                              	'format' => 'M-yyyy',
	                              	'todayHighlight' => true,
	                              	'viewMode'=> 'months', 
                                    'minViewMode' => 'months',
                                    'orientation' => 'bottom',
                                    'autoclose' => true
	                              ]
                             ]);?>
                         </div>
                         <div class="form-group">
                             <label>End Month</label>
                             <?= DatePicker::widget([
	                              'name' => 'end_month', 
	                              'id' => 'end_month', 
	                              'options' => ['placeholder' => 'Select month ...'],
	                              'pluginOptions' => [
	                              	'format' => 'M-yyyy',
	                              	'todayHighlight' => true,
	                              	'viewMode'=> 'months', 
                                    'minViewMode' => 'months',
                                    'orientation' => 'bottom',
                                    'autoclose' => true
	                              ]
                             ]);?>
                         </div>
			             <div class="form-group">
			               <label>Employee</label>
			               <?= Select2::widget([
                                     'name' => 'employee_id',
                                     'id'=>'employee_id',
                                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->select(['id','CONCAT(emp_name,"-",emp_code) as name'])->orderBy(['emp_name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
                                     'options' => [
                                         'placeholder' => 'Select ...',
                                     ],
                           ]);?>
                         </div>
                         <div class="form-group">
                             <?= Html::button('Search',['class'=>'btn btn-info','id'=>'leave-search-btn']);?>
                         </div>
			         </div>
			     </div>
			</div>   
		</div>   
	</div>   
</div>  
                              <?php
                               Modal::begin([
                                 'header' => '<h2>Leave Description</h2>',
                                 'footer' => '<button type="button" id="save-leave" class="btn btn-primary">Save</button>
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>',
                                 'id' => 'leave-comment-modal'
                              ]);?> 
                              <div class="form-group">
                                  <label id="form-date-label"></label>
                                  <textarea id="form-leave-comment" class="form-control"></textarea>
                                  <input type="hidden" id="form-leave-date">
                                  <input type="hidden" id="form-leave-employee">
                              </div>
                              <?php Modal::end(); ?>
                              
                              <?php Modal::begin([
			                 'id'=>'show-filter-leave',
			                 'header'=>'<h2>Leave Search Result</h2>'
                         ]);

                         Modal::end();?>

<?php
 $deaultLeaveUrl = Url::to(['employee-leave/ajax-default-leave']);
 $filterLeaveUrl = Url::to(['employee-leave/ajax-filter-leave']);
 $RemoveLeaveUrl = Url::to(['employee-leave/ajax-remove-leave']);
 $MarkLeaveUrl = Url::to(['employee-leave/ajax-mark-leave']);
 $CommentLeaveUrl = Url::to(['employee-leave/ajax-comment']);
 $script = <<< JS
         var activeDates = [];
         /*
         $('#employeeleave-employee_id').change(function(){
             $.ajax({
                 url:'$deaultLeaveUrl',
                 data:{id:$(this).val()},
                 success:function(res){
                     var ele = $('#employeeleave-leave').parent();
                     ele.kvDatepicker("setDate",res);
                 }
             });
         });
         
         $('#employeeleave-leave').parent().kvDatepicker().on("changeDate",function(e){
               if($("#employeeleave-employee_id").val() == ""){
                  $(this).kvDatepicker("update",""); 
                  $("#error-modal .message").html("Please select Employee");
                  $("#error-modal").modal("show");
                  return false;
               }
               var datepicker = this;
               var dates = e.dates;
               $.each(dates,function(index,item){
                 var date = new Date(item);
                 var mydate = convertDate(date);
                 var timestamp = date.getTime()-date.getTimezoneOffset()*60*1000;
                 $(datepicker).find("td.active.day[data-date='"+timestamp+"']").append("<div class='leave-comment-left' data-date='"+mydate+"'><i class='fa fa-envelope add-leave-comment'></i></div>"+
                                                     "<div class='leave-comment-right' data-date='"+mydate+"'><i class='fa fa-times remove-leave-comment'></i></div>");
               })
               $(".leave-comment-left").bind("click",function(){
                   event.stopPropagation();
                   var formdate = $(this).attr("data-date");
                   $("#form-date-label").text(formdate);
                   $("#form-leave-date").val(formdate);
                   $("#form-leave-employee").val($("#employeeleave-employee_id").val());
                   $.ajax({
                       url:"$CommentLeaveUrl",
                       data:{employee:$("#employeeleave-employee_id").val(),date:formdate},
                       success:function(res){
                          if(res.status == "success"){
                              $("#form-leave-comment").val(res.comment);
                          }else{
                              $("#error-modal .message").html(res.error);
                              $("#error-modal").modal("show");
                          }
                       }
                   });
                   $("#leave-comment-modal").modal("show");
               });
               $(".leave-comment-right").bind("click",function(){
                   event.stopPropagation();
                   removeLeave(this);
               });  
         });
         */
         $("#leave-search-btn").click(function(){
             var id = $("#employee_id").val();
             var start = $("#start_month").val();
             var end = $("#end_month").val();
             $.ajax({
                 url:'$filterLeaveUrl',
                 data:{id:$(this).val(),start_month:start,end_month:end},
                 success:function(res){
                     $('#show-filter-leave').find(".modal-body").html(res);
                     $('#show-filter-leave').modal("show");
                 }
             });
         });
         $("#save-leave").click(function(){
             $.ajax({
                url:"$MarkLeaveUrl",
                data:{employee:$("#form-leave-employee").val(),date:$("#form-leave-date").val(),comment:$("#form-leave-comment").val()},
                success:function(res){
                   if(res.status == "success"){
                       $("#leave-comment-modal").modal("hide");
                   }else{
                       $("#error-modal .message").html(res.error);
                       $("#error-modal").modal("show");
                   }
                }
            }); 
         });
         function convertDate(val){
             var yr = val.getFullYear();
             var month = val.getMonth() < 10 ? '0' + (val.getMonth()+1) : (val.getMonth()+1);
             var day = val.getDate()  < 10 ? '0' + val.getDate()  : val.getDate();
             return yr + '-' + month + '-' + day;
         }
         function removeLeave(ele){
              var leavesArray = $("#employeeleave-leave").val();
              var leaves = leavesArray.split(",");
              var date = $(ele).attr("data-date");
              leaves = leaves.filter(function(ele){ 
                    return ele != date; 
              });
              $.ajax({
                url:"$RemoveLeaveUrl",
                data:{employee:$("#employeeleave-employee_id").val(),date:date},
                success:function(res){
                    if(res.status == "success"){
                        if(leaves.length != 0)
                        $("#employeeleave-leave").parent().kvDatepicker("setDate",leaves);
                        else $("#employeeleave-leave").parent().kvDatepicker("setDate","");
                        $(ele).parent().removeClass("active");
                        $(ele).parent().find(".leave-comment-left,.leave-comment-right").remove();  
                    }else{
                        $("#error-modal .message").html(res.error);
                        $("#error-modal").modal("show");
                    }
                }
              });
         }
 JS;
 $this->registerJs($script);
 