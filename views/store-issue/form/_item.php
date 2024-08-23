<?php 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row">
	<div class="col-sm-12">
	   <h2>Items</h2>
	   <hr>
	</div>
</div>

<div class="issue-items">

<?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 100,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $items[0],
        'formId' => 'DynamicForm',
        'formFields' => [
            // 'gate_pass_no',
            'store_products_id',
            'uom_id',
            'quantity',
        ],
    ]); ?>

    
    <div class="container-items"><!-- widgetContainer -->
        <?php foreach ($items as $index => $Item): ?>

            <div class="item panel">
                    <?php
                        // necessary for update action.
                        if (!$Item->isNewRecord) {
                            echo Html::activeHiddenInput($Item, "[{$index}]id",['class'=>'item_id']);
                        }
                    ?>

                    <div class="row">
                        <!-- <div class="col-lg-2 col-md-3 col-sm-4">
                            <?php //echo $form->field($Item, "[{$index}]gate_pass_no") ?>
                        </div> -->
                        <div class="col-lg-3 col-md-3 col-sm-4">
                        <?= $form->field($Item, "[{$index}]store_products_id")->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\StoreProducts::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Select a Item ...','class'=>'case-item'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>

                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-6">
                        <?= $form->field($Item, "[{$index}]uom_id")->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Select a Item ...','class'=>'uom-item'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>
                        </div>

                        <div class="col-lg-2 col-md-3 col-sm-4">
                            <?= $form->field($Item, "[{$index}]quantity") ?>
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-3">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>

                    <!--Dynamic Fields End-->
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add Items</button>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>

</div>
