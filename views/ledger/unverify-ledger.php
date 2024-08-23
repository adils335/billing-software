<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\Ledger;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Ledger */
/* @var $dataProvider yii\data\ActiveDataProvider */
$account_type = 0;
if($searchModel->account_type){
    $account_type = $searchModel->account_type;
}

$this->title = Yii::t('app', 'Unverify Ledger');
$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
$count = 0;
?>
<div class="ledger-index">
	
   <div class="ledger-index box box-primary"> 

	<?php if($data['name']){?>

    <br>

	<div class="row">
	     
	    <div class="col-md-12">
		
			  <div class="col-md-4">
			      <label>Name: <?= $data['name'];?></label>
			  </div>
			  
			  <div class="col-md-4">
			      <label>From Date: <?= $formatter->asDate($data['fromDate'],'php:d-m-Y');?></label>
			  </div>
			  
			  <div class="col-md-4">
			      <label>To Date: <?= $formatter->asDate($data['toDate'],'php:d-m-Y');?></label>
			  </div>
			  
	      </div>
		  <hr>
	</div>

	<div class="row">
	     
	    <div class="col-md-12">
		
			  <div class="col-md-3">
			      <label>Balance (Till <?= $formatter->asDate(strtotime($data['fromDate']),'php:d-m-Y')?>): <?= $data['opening_bal'];?></label>
			  </div>
			  
			  <div class="col-md-3">
			      <label>Debit: <?= $data['debit'];?></label>
			  </div>
			  
			  <div class="col-md-3">
			      <label>Credit: <?= $data['credit'];?></label>
			  </div>
			  
			  <div class="col-md-3">
			      <label>Balance: <?= $data['bal'];?></label>
			  </div>
			  
	      </div>
		  
		  <hr>
	</div>

	<?php }?>
    
	<?php $balance = $data['opening_balance']+$data['last_balance'];?>
	
    <div class="box-body">
    <?php $form = ActiveForm::begin([
        'action' => ['verify'],
        'method' => 'post',
    ]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'options'=>['class'=>($searchModel->type != $searchModel::TYPE_EMPLOYEE) || $searchModel->account_type?"":"hide"],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

		    [
            'header' => 'Date',
            'content' => function($model) {
                return \Yii::$app->formatter->asDate($model->date,"php:d-m-Y");
            },
            'contentOptions'=>['style'=>'width: 10%;']           
            ],
            
            [
            'header' => 'Contract Company',
            'content' => function($model) {
                return $model->transaction->contractCompany->name;
            },
            'contentOptions'=>['style'=>'width: 15%;']           
            ],
            
		    [
            'header' => 'Site',
            'content' => function($model) {
                return $model->getSiteName();
            },
            'contentOptions'=>['style'=>'width: 10%;']           
            ],
			
		    [
            'header' => 'Particular',
            'content' => function($model) {
                return $model->particularLabel;
            },
            'contentOptions'=>['style'=>'width: 28%;']           
            ],
			
		    [
            'header' => 'Debit',
            'content' => function($model)use (&$balance,&$account_type){

                if(Ledger::TYPE_ACCOUNT == $model->type || $account_type == Ledger::ACCOUNT_EXPENSE){
                   
				    $balance -= $model->debit;
                    return $model->debit;

                }else{

				    $balance -= $model->credit;
                    return $model->credit;
                    
                }

            },
            'contentOptions'=>['style'=>'width: 10%;text-align:right;padding-right:10px;'],
            'format'=>['decimal',2]           
            ],
			
		    [
            'header' => 'Credit',
            'content' => function($model)use (&$balance,&$account_type){

            	if(Ledger::TYPE_ACCOUNT == $model->type || $account_type == Ledger::ACCOUNT_EXPENSE){

				   $balance += $model->credit;
                   return $model->credit;

                }else{

				   $balance += $model->debit;
                   return $model->debit;
                   
                }
            },
            'contentOptions'=>['style'=>'width: 10%;text-align:right;padding-right:10px;'],   
            'format'=>['decimal',2]                   
            ],
			
		    [
            'header' => 'Balance',
            'content' => function($model) use (&$balance) {
            	$balance = sprintf('%0.2f',$balance);
				$htmlBalance = $balance<0?abs($balance)." Dr.":$balance." Cr.";
                return $htmlBalance;
            },
            'contentOptions'=>['style'=>'width: 10%;text-align:right;padding-right:10px;']
            ],
            [
            'header' => '<input type="checkbox" id="all-data"> All &nbsp;<button type="submit" href="#" id="verify"><i class="fa fa-check"></i></a>',    
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{checkbox_data}{update}{verify}',
            'buttons' => [
                'verify' => function($url, $model, $key) use (&$count) {     
                	$count++;
                    if($count == 1)
                       return Html::a("<span class='fa fa-check'></span>",['verify','id'=>$model->id],['class'=>'']);
                    else "";
                },
                'update' => function($url, $model, $key) {     
                       return Html::a("<span class='fa fa-pencil'></span>",$url,['class'=>'','target'=>'_blank']);
                },
                'checkbox_data' => function( $url, $model, $key ){
                    return "<input type='checkbox' class='data-checkbox' name='data[]' value='".$model->id."'>";
                }
            ],
			    
			'urlCreator' => function ($action, $model, $key, $index) {
				   if($action == "update")
                        return $model->paymentEditLink();
               },
            'contentOptions'=>['style'=>'width: 6%;']
			],
			
        ],
    ]); ?>
<?php ActiveForm::end(); ?>

</div>
</div>
</div>
<?php 
$verify_url = Url::to(['verify']);
$script = <<<JS
    $("#all-data").click(function(event){
        event.stopPropagation();
        if( $(this).is(":checked") ){
            $(".data-checkbox").prop("checked",true);
        }else{
            $(".data-checkbox").prop("checked",false);
        }
    }); 
JS;
$this->registerJs($script);