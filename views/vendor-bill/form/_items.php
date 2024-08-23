<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;

?>
	<div class="row">
	    <div class="col-sm-12">
		   <h2>Item Details</h2>
	       <hr>
		</div>
	</div>
	
		 <div class="panel panel-default">
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 50, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $billItem[0],
                'formId' => 'vendor-bill',
                'formFields' => [
                    'bill_id',
                    'district_id',
                    'site_id',
                    'particular',
                    'work_type',
                    'work_name',
                    'uom_id',
                    'hsn_no',
                    'quantity',
                    'rate',
                    'amount',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($billItem as $i => $item): ?>
			
                <div class="item panel"><!-- widgetBody -->
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $item->isNewRecord) {
                                echo Html::activeHiddenInput($item, "[{$i}]id",['class'=>'item-id']);
                            }
                        ?>
                        <div class="row">
                            <div class="col-sm-2">
							<?= $form->field($item, "[{$i}]district_id")->widget(Select2::classname(), [
                               'data' => \app\models\District::buildDistrict(),
                               'options' => ['placeholder' => 'Select ...','class'=>'district'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($item, "[{$i}]site_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Sites::find()->where(['district_id'=>$item->district_id])->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'sites'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
							
                            <div class="col-sm-2">
                                <?= $form->field($item, "[{$i}]work_type")->widget(Select2::classname(), [
                               'data' => $item->isNewRecord?[]:$item->itemWorkType,
                               'options' => ['placeholder' => 'Select ...','class'=>'work-type'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $form->field($item, "[{$i}]work_name")->widget(Select2::classname(), [
                               'data' => $item->isNewRecord?[]:$item->itemWorks,
                               'options' => ['placeholder' => 'Select ...','class'=>'work-name'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            
                            <div class="col-sm-2">
                                <?= $form->field($item, "[{$i}]hsn_no")->textInput(['class'=>'form-control hsn-no']); ?>
                            </div>
                        </div>
						  
                        <div class="row">
                            <div class="col-sm-3">
                                <?= $form->field($item, "[{$i}]particular")->textarea(['class'=>'form-control particular']); ?>
                            </div>

                            <div class="col-sm-2">
                                <?= $form->field($item, "[{$i}]uom_id")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                               'options' => ['placeholder' => 'Select ...','class'=>'uom'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($item, "[{$i}]quantity")->textInput(['class'=>'form-control item-quantity','onkeyup'=>'billAmount()']); ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($item, "[{$i}]rate")->textInput(['class'=>'form-control item-rate','onkeyup'=>'billAmount()']); ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($item, "[{$i}]amount")->textInput(['class'=>'form-control item-amount','onkeyup'=>'billAmount()']); ?>
                            </div>
							
                            <div class="col-sm-1"><br>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
							
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
			
	<div class="row">
	
	   <div class="col-sm-12">
              
			  
			<div class="form-group">
             <button type="button" class="add-item btn btn-i pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
             </div>
                 
	   </div>
	
	</div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
	
	<div class="row">
	    <div class="col-sm-9"></div>
	    <div class="col-sm-1">
          <label>Total</label>
        </div>
	    <div class="col-sm-2">
          <?= $form->field($model, 'base_amount')->textInput(['maxlength' => true])->label(false); ?>
        </div>
	</div>

<?php
     $select2Options = json_encode([
    'data'=>'',         
    'multiple' => false,
    //'theme' => 'krajee',
    'placeholder' => 'Select',
    'language' => 'en-US',
    'width' => '100%',
     ]);
     
     $sitesUrl = Url::to(['sites/ajax-sites']);
     $workTypeUrl = Url::to(['common/ajax-work-type']);
     $workNameUrl = Url::to(['common/ajax-work-name']);
     $workRateUrl = Url::to(['common/ajax-work-rate']);
?>
<?php 
$formatJs = <<< JS
      
      $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
          
            $(e.target).find(".container-items").find(".item:last").find(".item-id").val('');
            $(e.target).find(".container-items").find(".item:last").find(".district").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".sites").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".work-type").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".work-name").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".hsn-no").val('');
            $(e.target).find(".container-items").find(".item:last").find(".particular").val('');
            $(e.target).find(".container-items").find(".item:last").find(".uom").val('').trigger("change");
            $(e.target).find(".container-items").find(".item:last").find(".item-quantity").val('');
            $(e.target).find(".container-items").find(".item:last").find(".item-rate").val('');
            $(e.target).find(".container-items").find(".item:last").find(".item-amount").val('');
            
            
            
            var currentItem = $(".item").last();
            set_work_type(currentItem.index());
      });

      $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
            if (! confirm("Are you sure you want to delete this item?")) {
                   return false;
            }
            return true;
      });
      
      $(document).on("change",".district",function(){
        var site = $(this).closest(".row").find(".sites");
        $.ajax({
          url:'$sitesUrl',
          data:{id:$(this).val()},
          success:function(data){
              select2Options = $select2Options;
            site.find("option").remove();
            select2Options.data = data.data;
            site.select2(select2Options);
          }
        });
      });
      
      $(document).on("change","#vendorbill-vendor_id",function(){
         set_work_type(null);
      });
      
      $(document).on("change",".work-type",function(){
        var vendor_id = $("#vendorbill-vendor_id").val();  
        var work_name = $(this).closest(".row").find(".work-name");
        $.ajax({
          url:'$workNameUrl',
          data:{vendor_id:vendor_id,work_type:$(this).val()},
          success:function(data){
            select2Options = $select2Options;
            work_name.find("option").remove();
            select2Options.data = data.data;
            work_name.select2(select2Options);
          }
        });
      });
      
      $(document).on("change",".work-name",function(){
        var vendor_id = $("#vendorbill-vendor_id").val();  
        var work_type = $(this).closest(".row").find(".work-type").val();
        var index = $(".work-name").index(this);
        $.ajax({
          url:'$workRateUrl',
          data:{vendor_id:vendor_id,work_type:work_type,work_name:$(this).val()},
          success:function(data){
            $(".item-rate").eq(index).attr('value',data.rate);
            $(".item-rate").eq(index).trigger("keyup");
          }
        });
      });
      
      function set_work_type(index){
        var vendor_id = $("#vendorbill-vendor_id").val();    
        var work_type = $(".work-type");
        $.ajax({
          url:'$workTypeUrl',
          data:{vendor_id:vendor_id},
          success:function(data){
            set_options("work-type",data,index);
          }
        });
      }
      
      function set_options(className,data,index){
          if(index != null){
              select2Options = $select2Options;
              $("."+className).eq(index).find("option").remove();
              select2Options.data = data.data;
              $("."+className).eq(index).select2(select2Options);
          }else{
            $("."+className).each(function(key){
                select2Options = $select2Options;
                $(this).find("option").remove();
                select2Options.data = data.data;
                $(this).select2(select2Options);
            });  
          }
      }
      
JS;
$this->registerJs($formatJs);