<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseBillItems */
/* @var $form yii\widgets\ActiveForm */
$formatter = Yii::$app->formatter;
?>

<?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 100, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $items[0],
                'formId' => 'purchaseForm',
                'formFields' => [
                    'particular',
                    'quantity',
                    'rate',
                    'amount',
                    'total',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($items as $i => $item): ?>
                <div class="item panel"><!-- widgetBody -->
                    
                    <div class="">
                        <?php
                            // necessary for update action.
                            if (! $item->isNewRecord) {
                                echo Html::activeHiddenInput($item, "[{$i}]id",['class'=>'item-id']);
                            }
						
                        ?>
                        
						<div class="row item-<?= $i?>">
                            <div class="col-sm-3">
							<?=  $form->field($item, "[{$i}]particular")->textarea(['class'=>'form-control particular']); ?>
                            </div>
							<div class="col-sm-2">
							  <?= $form->field($item, "[{$i}]hsn_no")->textInput(['class'=>'form-control hsn_no']); ?>
                            </div>
							<div class="col-sm-1">
							  <?= $form->field($item, "[{$i}]rate")->textInput(['class'=>'form-control item_rate']); ?>
                            </div>
							<div class="col-sm-1">
							  <?= $form->field($item, "[{$i}]quantity")->textInput(['class'=>'form-control quantity']); ?>
                            </div>
							<div class="col-sm-2">
							  <?= $form->field($item, "[{$i}]amount")->textInput(['class'=>'form-control amount']); ?>
                            </div>
							<div class="col-sm-2">
							  <?= $form->field($item, "[{$i}]total")->textInput(['class'=>'form-control total']); ?>
                            </div>
							<div class="col-sm-1">
							    <br>
							    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            
                        </div><!-- .row -->
                            <?= $this->render('_items_tax', [
                              'form' => $form,
                              'index' => $i,
                              'itemsTax' => $itemsTax[$i],
                            ]) ?>
                        
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
						<div class="row">
						     <div class="col-md-12">
							      <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
							 </div>
						</div>
            <?php DynamicFormWidget::end(); ?>