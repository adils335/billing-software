<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
$this->title = Yii::t('app', 'Workers Leaves');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers Leaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="employee-leave-details box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Workers Leave')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
			     <div class="row">
			         <div class="col-sm-6">
			              <?php $form = ActiveForm::begin([
			              'action'=>Url::to(['worker-leave/mark-leave'])
			              ]); ?>
			                  <?= $form->field($model, 'worker_id')->widget(Select2::classname(), [
                                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Worker::find()->select(['id','CONCAT(name,"-",code) as name'])->orderBy(['name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
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
			               <label>Worker</label>
			               <?= Select2::widget([
                                     'name' => 'worker_id',
                                     'id'=>'worker_id',
                                     'data' => \yii\helpers\ArrayHelper::map(\app\models\Worker::find()->select(['id','CONCAT(name,"-",code) as name'])->orderBy(['name'=>SORT_ASC])->asArray()->all(), 'id', 'name'),
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
                                  <input type="hidden" id="form-leave-worker">
                              </div>
                              <?php Modal::end(); ?>
                              
                              <?php Modal::begin([
			                 'id'=>'show-filter-leave',
			                 'header'=>'<h2>Leave Search Result</h2>'
                         ]);

                         Modal::end();?>

<?php
 $deaultLeaveUrl = Url::to(['worker-leave/ajax-default-leave']);
 $filterLeaveUrl = Url::to(['worker-leave/ajax-filter-leave']);
 $RemoveLeaveUrl = Url::to(['worker-leave/ajax-remove-leave']);
 $MarkLeaveUrl = Url::to(['worker-leave/ajax-mark-leave']);
 $CommentLeaveUrl = Url::to(['worker-leave/ajax-comment']);
 $script = <<< JS
         var activeDates = [];
         
         $("#leave-search-btn").click(function(){
             var id = $("#worker_id").val();
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
                data:{worker:$("#form-leave-worker").val(),date:$("#form-leave-date").val(),comment:$("#form-leave-comment").val()},
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
              var leavesArray = $("#workerleave-leave").val();
              var leaves = leavesArray.split(",");
              var date = $(ele).attr("data-date");
              leaves = leaves.filter(function(ele){ 
                    return ele != date; 
              });
              $.ajax({
                url:"$RemoveLeaveUrl",
                data:{employee:$("#workerleave-employee_id").val(),date:date},
                success:function(res){
                    if(res.status == "success"){
                        if(leaves.length != 0)
                        $("#workerleave-leave").parent().kvDatepicker("setDate",leaves);
                        else $("#workerleave-leave").parent().kvDatepicker("setDate","");
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
 