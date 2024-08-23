<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use kartik\nav\NavX;
use yii\helpers\Url;
//use \yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $wizard app\components\Wizard */
 
$first_step = $wizard->steps['information']['title'];
$second_step = $wizard->steps['gauranty']['title'];
$third_step = $wizard->steps['sites']['title'];
$forth_step = $wizard->steps['tax']['title'];
$fifth_step = $wizard->steps['vendor']['title'];

if(!empty($_GET['id'])) 
  $agreement=\app\models\Agreement::findOne($_GET['id']);
else $agreement= new \app\models\Agreement;
$flag=false;

$url = Url::to(['agreement/create-agreement']);
if(!empty($_GET['step']))
	 $url = Url::to(['agreement/create-agreement','step'=>$_GET['step'],'id'=>$agreement->id]);
            $stepData = $wizard->_getSession('stepData');
?>
<style>
.assessment-nav-pills .nav-pills li button {
    background: #222d32;
    color: #ffffff;
    border: none !important;
}
.nav-pills>li>button {
    border-radius: 0;
    border-top: 3px solid transparent;
    color: #444;
}
.nav > li > button {
    position: relative;
    display: block;
    padding: 10px 15px;
}
.agreement-nav-pills .nav-pills li.active button {
    color: #f3f3f3;
    background: #3c8dbc;
	font-weight: 700;
}

</style>
<div class="agreement-create">
    <div class="agreement-form">
        
        <?php $form = ActiveForm::begin([
		            'id' => $wizard->currentFormId,
					'action' => $url,
			]); ?>
		<div class="agreement-nav-pills">
			<ul id="navigation-pills" class="nav nav-pills">
				<li class="<?=($wizard->currentStep=='information')?'active':''?>">
					<?= Html::submitButton($first_step, ['class' => 'btn  btn-flat ', 'name' => 'step', 'value' => 'information']);
					?>
					
				</li>
				<li class="<?=($wizard->currentStep=='gauranty')?'active':''?>">
					<?= Html::submitButton($second_step, ['class' => 'btn  btn-flat ', 'name' => 'step', 'value' => 'gauranty']);
					?>
				</li>
				<li class="<?=($wizard->currentStep=='sites')?'active':''?>">
					<?= Html::submitButton($third_step, ['class' => 'btn  btn-flat ', 'name' => 'step', 'value' => 'sites']);
					?>
				</li>
				<li class="<?=($wizard->currentStep=='tax')?'active':''?>">
				<?= Html::submitButton($forth_step, ['class' => 'btn  btn-flat', 'name' => 'step', 'value' => 'tax']);
				?>

				<li class="<?=($wizard->currentStep=='vendor')?'active':''?>">
				<?= Html::submitButton($fifth_step, ['class' => 'btn btn-success btn-flat', 'name' => 'step', 'value' => 'vendor']);
				?>
					
				</li>
			</ul>        
		</div>

        <div class="#">
            <div class="row">
			
                <div class="col-md-12">
                    <?= $this->render('/'.$wizard->getView().'//'.$wizard->steps[$wizard->currentStep]['view'], array_merge($wizard->data, ['form'=>$form, 'current_step'=>$wizard->currentStep, 'stepData' => $stepData])) ?>
                </div>
				
            </div>
        </div>
        <div class="box-footer"> 
            <?php
                echo Html::beginTag('div', ['class' => 'form-row buttons']); 
                echo Html::a('<button type="button" class="btn btn-danger btn-flat"><i class="fa fa-times"></i>&nbsp;'.Yii::t('app', 'Cancel').'</button>', ['agreement/view','id'=>$wizard->data['agreement']->id]);

                if($wizard->currentStep != 'information'){
                    
                    echo Html::submitButton('<i class="fa fa-arrow-circle-left"></i>&nbsp;'.Yii::t('app', 'Previous'), ['class' => 'btn btn-info btn-flat', 'name' => 'prev', 'value' => 'Previous']);
                }  
                //echo Html::submitButton(Yii::t('app', 'Prev'), ['class' => 'btn btn-success btn-flat', 'name' => 'prev', 'value' => 'prev']);
                if($wizard->currentStep == 'vendor'){
                    echo Html::beginTag('div', ['class' => 'form-row submit-buttons pull-right']); 
					if($flag){
				?>
					<button type="button" class="btn btn-success btn-flat " data-toggle="modal" data-target="#myModal"><i class="fa fa-floppy-o"></i>&nbsp;<?=Yii::t('app', 'Save Agreement')?></button>
				<?php 
					}else
                    echo Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;'.Yii::t('app', 'Save Agreement'), ['class' => 'btn btn-success btn-flat price-submit', 'name' => 'save', 'value' => 'save']);
                }else {

                    echo "&nbsp;";
                    echo Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;'.Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat submit', 'name' => 'save-step', 'value' => 'save']);

                    echo Html::submitButton('<i class="fa fa-arrow-circle-right"></i>&nbsp;'.Yii::t('app', 'Next'), ['class' => 'btn btn-success btn-flat pull-right', 'name' => 'next', 'value' => 'next']);
                }  
                
                echo Html::endTag('div');
            ?> 
        </div>
	  <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
$('#location-address_form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
}); 
JS;
$this->registerJs($script); 