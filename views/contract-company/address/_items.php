<?php 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
	<div class="col-md-12">
	   <h2>Addresses</h2>
	   <hr>
	</div>
</div>

<?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 999,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsAddresses[0],
        'formId' => 'company-address',
        'formFields' => [
            'type',
            'type_id',
            'pos',
            'legal_name',
            'trade_name',
            'address_1',
            'address_2',
            'location',
            'pincode',
            'phone',
            'email',
        ],
    ]); ?>

    
            <div class="container-items"><!-- widgetContainer -->
                <?php foreach ($modelsAddresses as $index => $modelAddress): ?>
                    <div class="container-items">
                        <div class="item"><!-- widgetBody -->
                            
                            <div class="row">
                                <?= $form->field($modelAddress, "[{$index}]type")->hiddenInput(['value'=>$modelAddress->type,'class'=>'type'])->label(false); ?>
                                <?php if( !empty( $modelAddress->id ) ):?>
                                <?= $form->field($modelAddress, "[{$index}]id")->hiddenInput(['class'=>'id'])->label(false); ?>
                                <?php endif;?>

                                <div class="col-md-3">
                                <?= $form->field($modelAddress, "[{$index}]district_id")->widget(Select2::classname(), [
                                        'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$companyGst->state_id])->orderBy('id')->asArray()->all(), 'id', 'district'),
                                        'options' => ['placeholder' => 'Select ...','class'=>'district-id'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                ])->label("District"); ?>
                                </div>

                                <div class="col-md-3">
                                    <?= $form->field($modelAddress, "[{$index}]legal_name")?>
                                </div>
                        
                                <div class="col-md-3">
                                    <?= $form->field($modelAddress, "[{$index}]trade_name") ?>
                                </div>
                        
                                <div class="col-md-3">
                                    <?= $form->field($modelAddress, "[{$index}]address_1") ?>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <?= $form->field($modelAddress, "[{$index}]address_2")?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($modelAddress, "[{$index}]location") ?>
                                </div>

                                <div class="col-md-1">
                                    <?= $form->field($modelAddress, "[{$index}]pincode") ?>
                                </div>

                                <div class="col-md-2">
                                    <?= $form->field($modelAddress, "[{$index}]phone")?>
                                </div>
                                <div class="col-md-2">
                                    <?= $form->field($modelAddress, "[{$index}]email") ?>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="remove-item btn btn-danger btn-xs pull-right"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add Items</button>
                </div>
            </div>
    <?php DynamicFormWidget::end(); ?>


<?php $script = <<<JS
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        $(".type:not(:first)").val($(".type:first").val());
        $(".type-id:not(:first)").val($(".type-id:first").val());
        $(".id:last").val("");
    });
JS;

$this->registerJs($script);