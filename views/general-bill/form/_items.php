<?php
use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2; 
?>
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Item Details</h2>
	       <hr>
		</div>
	</div>

	<div class="row general-bill-items">
	    
	            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 99, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $billItem[0],
                'formId' => 'agreement-bill',
                'formFields' => [
                    'invoice_id',
                    'agreement_id',
                    'sno',
                    'item',
                    'item_text',
                    'hsn_no',
                    'unit',
                    'quantity',
                    'rate',
                    'amount',
                    'session',
                    'company_id',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($billItem as $i => $rate): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $rate->isNewRecord) {
                                echo Html::activeHiddenInput($rate, "[{$i}]id");
                            }
                        ?>
                        <div class="row">
							<div class="col-sm-1 no-padding">
                                <div class="pull-left">
                                   <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            </div>
                            <div class="col-sm-1">
                             <?= $form->field($rate, "[{$i}]sno")->textInput(['class'=>'sequence sequence-input']); ?>
                            </div>
                            <div class="col-sm-3 no-padding">
                                <?php if (! $rate->isNewRecord) {?>
                                <?= $form->field($rate, "[{$i}]item")->hiddenInput(['value'=>$rate->itemName->id,'class'=>'hidden-item']); ?>
                                <?php }?>
                                <?php 
                                $itemText = "";
                                if(!empty($rate->itemName)){
                                    $itemText = $rate->itemName->item;
                                }
                                ?>
							<?= $form->field($rate, "[{$i}]item_text")->textarea(['rows'=>3,'value'=>$itemText,'class'=>'form-control item-input'])->label(false); ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]hsn_no")->textInput(['class'=>'hsno-input']) ?>
                            </div>
	                        <div class="col-sm-2">
		                          <?= 
		                    	     $form->field($rate, "[{$i}]unit")
                                        ->dropDownList(
                                          \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'), 
		                    			  ['prompt' => 'Select ...','class'=>'form-control unit-input']  // options
                                    );
		                    	  ?>
		                    </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]quantity")->textInput(['class'=>'item-quantity quantity-input','onkeyup'=>'billAmount()']) ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]rate")->textInput(['class'=>'item-rate rate-input','onkeyup'=>'billAmount()']) ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <?= $form->field($rate, "[{$i}]amount")->textInput(['class'=>'item-amount rate-amount']) ?>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-9">
                     <button type="button" class="add-item btn btn-info pull-left"><i class="glyphicon glyphicon-plus"></i>Add</button>
                </div>
                <div class="col-sm-1">
                    <label>Total</label>
               </div>
	           <div class="col-sm-2">
                 <?= $form->field($model, 'base_amount')->textInput(['maxlength' => true])->label(false); ?>
                 <?= $form->field($model, 'taxable_amount')->hiddenInput(['maxlength' => true])->label(false); ?>
               </div>
            </div>
            <?php DynamicFormWidget::end(); ?>
	    
	</div>
	