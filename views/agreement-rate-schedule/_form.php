<?php
use yii\bootstrap\Html; 
use yii\Helpers\Url; 
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementRateSchedule */
/* @var $form yii\widgets\ActiveForm */
$isNew = empty($agreement->agreementBills)?1:0;
//echo "<pre>";print_r($model);die();
?>

<div class="agreement-rate-schedule-form">
<div class="agreement-rate box box-primary">
    <div class="box-header">
        <?php Pjax::begin([
    'id' => 'pjax-rate-schedule',
    'timeout' => false
]);?>
    <?php $form = ActiveForm::begin([
	  'id' => 'rate_schedule',
      'options' => ['data-pjax' => true ]
	]); ?>
        <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 99, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $model[0],
                'formId' => 'rate_schedule',
                'formFields' => [
                    'agreement_id',
                    'sno',
                    'type',
                    'item',
                    'hsn_no',
                    'unit',
                    'amount',
                    'quantity',
                    'rate',
                    'is_active',
                    'session',
                    'company_id',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model as $i => $rate): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $rate->isNewRecord) {
                                echo Html::activeHiddenInput($rate, "[{$i}]id",['class'=>'rate-and-schedule-id']);
                            }
                        ?>
                        <?= $form->field($rate, "[{$i}]agreement_id")->hiddenInput(['value'=>$agreement->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-9 type-div" style="display:<?= $isNew??"none"?>">
                                <?= $form->field($rate, "[{$i}]type")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\ScheduleRateMaster::find()->select(['srmid','type'])->distinct()->orderBy('type')->all(), 'srmid', 'type'),
                               'options' => ['placeholder' => 'Select ...','class'=>'type'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-3 new-rate-div">
                                <?php $rate->is_active = 0;?>
                                <?= $form->field($rate, "[{$i}]is_active")->checkbox(['class'=>'new-rate']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">
                             <?= $form->field($rate, "[{$i}]sno")->textInput(['class'=>'sequence sequence-input']); ?>
                            </div>
                            <div class="col-sm-6 no-padding">
							<?= $form->field($rate, "[{$i}]item")->textarea(['rows'=>3,'class'=>'form-control item']); ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]hsn_no")->textInput(['class'=>'hsno-input']) ?>
                            </div>
	                        <div class="col-sm-1 no-padding">
		                          <?= 
		                    	     $form->field($rate, "[{$i}]unit")
                                        ->dropDownList(
                                          \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'), 
		                    			  ['prompt' => 'Select ...','class'=>'unit-input']  // options
                                    );
		                    	  ?>
		                    </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]quantity")->textInput(['class'=>'item-quantity quantity-input']) ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]rate")->textInput(['class'=>'item-rate rate-input']) ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <div class="pull-right">
                                   <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            </div>
                            
                        </div><!-- .row -->
						<div class="row" style="display:none">
                            <div class="col-sm-6">
							<?= $form->field($rate, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','value' => $agreement->company_id],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-6">
							  <?= $form->field($rate, "[{$i}]session")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
                               'options' => ['placeholder' => 'Select ...','value' => $agreement->session],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                     <button type="button" class="add-item btn btn-info pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
                </div>
            </div>
            <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php Pjax::end();?>
</div>
</div>
</div>


	<?php 
	   $url = Url::to(['create','agreement_id'=>$agreement->id]);
       $srcipt = <<< JS

       hideType();
         $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
            $(".rate-and-schedule-id:last").val("");
            $(".item:last").val("");
            $(".hsno-input:last").val("");
            $(".unit-input:last").val("");
            $(".item-quantity:last").val("");
            $(".item-rate:last").val("");
            $('.agreement-id').val($('#agreementrateschedule-0-agreement_id').val());
            hideType();
             sortItem();
         });
       
        if($isNew){
           $(document).on('change','.type:first', function() {
               var type = encodeURIComponent($(this).val());
               var url = '$url'+'&type='+type;
               $.pjax.reload({container: '#pjax-rate-schedule',url:url});  //Reload
           });
        }

        $(document).on("pjax:success", function() {
           hideType();
           });

         function hideType(){

            $('.type-div').not(':first').hide();
            $('.new-rate-div').not(':first').hide();
            var type = $('.type').eq(0).val();
            var new_rate = $('.new-rate').eq(0).prop("checked");
            $('.type').not(':first').val(type).trigger('change');
            $('.new-rate').not(':first').prop("checked",new_rate).trigger('change');

         }
        // sortItem();
        
         $('.container-items').sortable({
              items: '.item:not(.item:first-child)',
              cursor: 'pointer',
              axis: 'y',
              dropOnEmpty: false,
              start: function (e, ui) {
                  ui.item.addClass('selected');
              },
              stop: function (e, ui) {
                  ui.item.removeClass('selected');
                  $(this).find('.item').each(function (index) {
                      if (index > 0) {
                          $(this).find('.sequence').val(index+1);
                      }
                  });
              }
         });
         
         $(document).on("click",".new-rate:first",function(){
            var new_rate = $('.new-rate').eq(0).prop("checked");
            $('.new-rate').not(':first').prop("checked",new_rate).trigger('change');
         });
         
         function sortItem(){
            $('.sequence').each(function(index){
               $(this).val(index+1);
            });
         }


       JS;

	   $this->registerJs($srcipt);
	   