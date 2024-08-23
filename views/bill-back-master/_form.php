<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\BillBackMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bill-back-master-form">

        <?php $form = ActiveForm::begin([
       'id'=>'bill-back-master'
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
                'formId' => 'bill-back-master',
                'formFields' => [
                    'sno',
                    'type',
                    'description',
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
                            
                            <div class="col-sm-12">
                                <?= $form->field($rate, "[{$i}]type")->textInput(['class'=>'type form-control']) ?>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-1">
                            <?= $form->field($rate, "[{$i}]sno")->textInput(['class'=>'sequence form-control']); ?>
                            </div>
                            <div class="col-sm-10">
                            <?= $form->field($rate, "[{$i}]description")->textarea(); ?>
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

    <?php 
       
       $this->registerJs("
     
         sortItem();
         hideRow();
         $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
         $(e.target).find('.container-items').find('.item:last').find('input,textarea').val('');
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

           $('.type').val(type);
           $('.single').not(':first').hide();
         }

         function sortItem(){
            $('.sequence').each(function(index){
               $(this).val(index+1);
            });
         }

       ");
       
    ?>