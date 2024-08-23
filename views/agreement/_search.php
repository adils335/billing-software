<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Common;
$commonModel = new Common; 

//$agreement_no  = $commonModel->agreementNoByStatus($model->contract_company_id,$model->status);


/* @var $this yii\web\View */
/* @var $model app\models\search\Assessment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assessment-search">
    
    
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        
        <div class="col-md-3">
            <?php 
            $sessonArray = \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy(['session'=>SORT_DESC])->asArray()->all(), 'session', 'session');
            $sessonArray['all'] = "All";
            ?>
              <?= $form->field($model, 'session')->widget(Select2::classname(), [
                        'data' => $sessonArray,
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ]);?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ]); ?>
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'contract_company_state')->widget(Select2::classname(), [
                        'data' => \app\models\Common::agreementState(),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ])->label("State"); ?>
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'contract_company_district')->widget(Select2::classname(), [
                        'data' => \app\models\Common::agreementDistrict($model->contract_company_state),
                        'options' => ['placeholder' => 'Select ...'],
                        'pluginOptions' => [
                                        'allowClear' => true
                            ],
              ])->label("District"); ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'contract_company_id')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?>     
        </div>  

        <div class="col-md-3">
            <?php if( empty( $model->status ) ) $model->status = 0;?>
              <?= $form->field($model, 'status')->widget(Select2::classname(), [
                    'data' =>\app\models\Agreement::buildStatus(),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                                'allowClear' => true,
                        ],
               ]); ?>
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'id')->widget(Select2::classname(), [
                    'data' =>$commonModel->agreementNoByStatus($model->contract_company_id,$model->status),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ])->label('Agreement No'); ?> 
        </div>

        <!-- <div class="col-md-3">
            <?=  $form->field($model, 'site_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Sites::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Site'); ?> 
        </div> -->

        
    
    </div>

    <div class="row">

        <div class="col-sm-3">
                
            <?=  $form->field($model, "from_date")->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter date ...'],
                'pluginOptions' => [
                            'autoclose'=>true,
                            'format'=>'dd-mm-yyyy'
                        ]
            ]); ?>
                
            </div>

        <div class="col-sm-3">
                
            <?=  $form->field($model, "to_date")->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter date ...'],
                'pluginOptions' => [
                            'autoclose'=>true,
                            'format'=>'dd-mm-yyyy'
                        ]
            ]); ?>
            
        </div>
    </div>
    
    
    <div class="row">
        
       <div class="col-md-12">    
        
          <div class="form-group">
              <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
              <?= Html::a('Reset',['index'], ['class' => 'btn btn-default']) ?>
          </div>
          
       </div>
       
    </div>
    
    <?php ActiveForm::end(); ?>

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
     
     
$toStateUrl = Url::to(['common/contract-company-district']);
$toDistrictUrl = Url::to(['common/contract-company-name']);
// $toContractCompanyUrl = Url::to(['common/ajax-agreement-no']);
$toStatusUrl = Url::to(['common/ajax-by-status']);
$toAgreementNoUrl = Url::to(['common/ajax-site-name']);
$script = <<< JS
  
  $(document).on("change","#agreement-contract_company_state",function(){
           
        var state = $(this).val();
        var district = $("#agreement-contract_company_district");
        $.ajax({
          url:'$toStateUrl',
          data:{state:state},
          success:function(data){
              select2Options = $select2Options;
              district.find("option").remove();
           select2Options.data = data.data;console.log(select2Options);
           district.select2(select2Options);
          }
        });
        
  });

    $(document).on("change","#agreement-contract_company_district",function(){
           
           var district = $(this).val();
           //console.log(district);
           var contract_company = $("#agreement-contract_company_id");
           $.ajax({
            url:'$toDistrictUrl',
            type:'get',
            data:{district:district},
            dataType:'JSON',
            success:function(res){
                contract_company.find("option").remove();
                contract_company.append("<option value=''>Contract Company</option>");
                for(var key in res){
                    contract_company.append("<option value='"+key+"'>"+res[key]+"</option>");
                }
            }
           });
           
     });

    $(document).on("change","#agreement-contract_company_id,#agreement-status",function(){
        var contract_company_id = $("#agreement-contract_company_id").val(); 
        var status = $("#agreement-status").val(); 
        var agreement_no = $("#agreement-id"); //return element
        $.ajax({
            url:"$toStatusUrl",
            type:'get',
            data:{contract_company_id,status},
            dataType:'JSON',
            success:function(res){
                agreement_no.find("option").remove();
                agreement_no.append("<option value=''>Select Agreement</option>");
                for(var key in res){
                    agreement_no.append("<option value='"+key+"'>"+res[key]+"</option>");
                }
            }
        });

    });

    // $(document).on("change","#agreement-contract_company_id",function(){
    //     var contract_company_id = $(this).val();
    //     var agreement_no = $("#agreement-agreement_no");
    //     $.ajax({
    //         url:"$toContractCompanyUrl",
    //         type:'get',
    //         data:{contract_company_id},
    //         dataType:'JSON',
    //         success:function(res){
    //             agreement_no.find("option").remove();
    //             agreement_no.append("<option value=''>Select Agreement</option>");
    //             for(var key in res){
    //                 agreement_no.append("<option value='"+key+"'>"+res[key]+"</option>");
    //             }
    //         }
    //     });

    // });

    $(document).on("change","#agreement-agreement_no",function(){
        var agreement_no = $(this).val();
        var site = $("#agreement-site_id");
        $.ajax({
            url:"$toAgreementNoUrl",
            type:'get',
            data:{agreement_no},
            dataType:'JSON',
            success:function(res){
                site.find("option").remove();
                site.append("<option value=''>Select Site</option>");
                for(var key in res){
                    site.append("<option value='"+key+"'>"+res[key]+"</option>");
                }
            }
        });

    });
  JS;
  $this->registerJs($script);