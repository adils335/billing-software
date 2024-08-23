<?php
use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2; 

?>
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Payable Tax</h2>
	       <hr>
		</div>
	</div>
	<div class="row general-bill-items">
	    
	            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper_1', 
                'widgetBody' => '.container-items-1', 
                'widgetItem' => '.item-1', 
                'limit' => 99, 
                'min' => 1, 
                'insertButton' => '.add-item-1', 
                'deleteButton' => '.remove-item-1', 
                'model' => $billTax[0],
                'formId' => 'agreement-bill',
                'formFields' => [
                    'invoice_id',
                    'agreement_id',
                    'tax_id',
                    'rate',
                    'amount',
                    'session',
                    'company_id',
                ],
            ]); ?>

            <div class="container-items-1"><!-- widgetContainer -->
            <?php foreach ($billTax as $i => $rate): ?>
                <div class="item-1 panel panel-default"><!-- widgetBody -->
                    
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $rate->isNewRecord) {
                                echo Html::activeHiddenInput($rate, "[{$i}]id");
                            }
                        ?>
                        <div class="row">
							<div class="col-sm-2">
                                <div class="pull-left">
                                   <button type="button" class="remove-item-1 btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <?php 
                                $activeField = $form->field($rate, 'tax_id');
                                $activeField->enableClientValidation=false;
                                $activeField ->enableAjaxValidation=false;
                                ?>
                             <?= 
			                   $form->field($rate, "[{$i}]tax_id")
                                  ->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\app\models\Tax::find()->where(['tax_type'=>1])->orderBy('id')->asArray()->all(), 'id', 'name') 
				              	 /* ['options' => [$tax->tax_id => ['Selected'=>true]],
				              	  'prompt'=>'Select']   */ 
                               )->label("Tax");
			                   ?>
                            </div>
							<div class="col-sm-2">
                                <?= $form->field($rate, "[{$i}]rate")->textInput(['class'=>'form-control tax-rate','onkeyup'=>'billAmount()']) ?>
                            </div>
							<div class="col-sm-4">
                                <?= $form->field($rate, "[{$i}]amount")->textInput(['class'=>'form-control tax-amount','onkeyup'=>'billAmount()']) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-9">
                     <button type="button" class="add-item-1 btn btn-info pull-left"><i class="glyphicon glyphicon-plus"></i>Add</button>
                </div>
                <div class="col-sm-1">
                    <label>Tax Total</label>
               </div>
	           <div class="col-sm-2">
                 <?= $form->field($model, 'tax_amount')->textInput(['maxlength' => true])->label(false); ?>
               </div>
            </div>
            <?php DynamicFormWidget::end(); ?>
	    
	</div>