<?php

use yii\bootstrap\Html; 
use yii\Helpers\Url; 
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleRateMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-rate-master-form">

    <?php $form = ActiveForm::begin([
       'id'=>'shedule_rate'
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
                'formId' => 'shedule_rate',
                'formFields' => [
                    'agreement_id',
                    'sno',
                    'item',
                    'hsn_no',
                    'unit',
                    'amount',
                    'quantity',
                    'rate',
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
                                echo Html::activeHiddenInput($rate, "[{$i}]id");
                            }
                        ?>
                        <?= $form->field($rate, "[{$i}]srmid")->hiddenInput()->label(false); ?>
                        <div class="row single">
                            
                            <div class="col-sm-9">
                                <?= $form->field($rate, "[{$i}]type")->textInput(['class'=>'type form-control']) ?>
                            </div>

                            <div class="col-sm-3">
                            <?= $form->field($rate, "[{$i}]company_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'form-control company'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-1">
                            <?= $form->field($rate, "[{$i}]sno")->textInput(['class'=>'sequence form-control']); ?>
                            </div>
                            <div class="col-sm-6">
                            <?= $form->field($rate, "[{$i}]item")->textarea(); ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($rate, "[{$i}]hsn_no")->textInput() ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($rate, "[{$i}]unit")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>

                            <div class="col-sm-1">
                                <div class="pull-right">
                                   <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            </div>
                            
                        </div><!-- .row -->
                        
                    </div>

                </div>
            <?php endforeach; ?>

            </div>

                        <div class="row">

                             <div class="col-sm-12">
                                 
                                <div class="pull-right">
                                   <button type="button" class="add-item btn btn-success"><i class="glyphicon glyphicon-plus">Add</i></button>
                                </div>

                             </div>
                            
                        </div>
            <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>


    <?php 
       
       $this->registerJs("
     
         sortItem();
         hideRow();
         $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
            $(e.target).find('.container-items').find('.item:last').find('input,textarea').val('');
           $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
            hideRow();
            sortItem();
         });

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
         
         function hideRow(){
           var type = $('.type').eq(0).val();
           var company = $('.company').eq(0).val();

           $('.type').val(type);
           $('.company').val(company).trigger('change');
           $('.single').not(':first').hide();
         }

         function sortItem(){
            $('.sequence').each(function(index){
               $(this).val(index+1);
            });
         }

       ");
       
    ?>