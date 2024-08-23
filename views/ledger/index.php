<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use app\models\Ledger;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Ledger */
/* @var $dataProvider yii\data\ActiveDataProvider */
$account_type = 0;
if($searchModel->account_type){
    $account_type = $searchModel->account_type;
}

$this->title = Yii::t('app', 'Ledger');
$this->params['breadcrumbs'][] = $this->title;
$formatter = \Yii::$app->formatter;
?>

<?php 
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],

		    [
            'header' => 'Date',
            'content' => function($model) {
                return \Yii::$app->formatter->asDate($model->date,"php:d-m-Y");
            },
            'contentOptions'=>['style'=>'width: 10%;']           
            ],
			
		    [
            'header' => 'Particular',
            'content' => function($model) {
                return $model->particularLabel;
            },
            'contentOptions'=>['style'=>'width: 49%;']           
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
            'contentOptions'=>['style'=>'width: 12%;text-align:right;padding-right:10px;'],
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
            'contentOptions'=>['style'=>'width: 12%;text-align:right;padding-right:10px;'],   
            'format'=>['decimal',2]                   
            ],
			
		    [
            'header' => 'Balance',
            'content' => function($model) use (&$balance) {
            	$balance = sprintf('%0.2f',$balance);
				$htmlBalance = $balance<0?abs($balance)." Dr.":$balance." Cr.";
                return $htmlBalance;
            },
            'contentOptions'=>['style'=>'width: 12%;text-align:right;padding-right:10px;']
            ]

    ]

?>
<div class="ledger-index">
   <div class="ledger-index box box-primary"> 
		
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php if($data['name']){?>

    <br>
    <div class="user-summary hide">
        <table>
    <tr><td colspan='6'>
        
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
			      <label>Balance (Till <?= $formatter->asDate(strtotime($data['fromDate']."-1 DAY"),'php:d-m-Y')?>): <?= $data['opening_bal'];?></label>
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
	</td></tr></table>
	</div>

	<?php }?>
    
	<?php $balance = $data['opening_balance']+$data['last_balance'];?>
	
    <div class="box-body table-responsive">
        <div id="toolbar"></div>
    <?php 
        $html = '<tr class="header-table"><td colspan="6"><div class="row">
	     
	    <div class="col-md-12">
		
			  <div class="col-md-4">
			      <label>Name: '.$data['name'].'</label>
			  </div>
			  
			  <div class="col-md-4">
			      <label>From Date: '.$formatter->asDate($data['fromDate'],'php:d-m-Y').'</label>
			  </div>
			  
			  <div class="col-md-4">
			      <label>To Date: '.$formatter->asDate($data['toDate'],'php:d-m-Y').'</label>
			  </div>
			  
	      </div>
		  <hr>
	</div>

	<div class="row">
	     
	    <div class="col-md-12">
		
			  <div class="col-md-3">
			      <label>Balance (Till '.$formatter->asDate(strtotime($data['fromDate']."-1 DAY"),'php:d-m-Y').'):'. $data['opening_bal'].'</label>
			  </div>
			  
			  <div class="col-md-3">
			      <label>Debit: '.$data['debit'].'</label>
			  </div>
			  
			  <div class="col-md-3">
			      <label>Credit: '.$data['credit'].'</label>
			  </div>
			  
			  <div class="col-md-3">
			      <label>Balance: '.$data['bal'].'</label>
			  </div>
			  
	      </div>
		  
		  <hr>
	</div></td></tr>';
        
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'tableOptions'=>[
                'id'=>"table",
                'data-toolbar'=>"#toolbar",
                'data-toggle'=>"table",
                'data-show-columns'=>"true",
                'data-show-columns'=>"true",
                'data-show-export'=>"true",
                'data-show-print'=>"false"
                ],
            'columns' => $gridColumns,
        ]);
    
        ?>


</div>
</div>
</div>

<?php 

$script = <<<JS
    var html = $(".user-summary").clone().find("tr").prependTo("tbody");
    //html.removeClass('box-header hide');
    //$("tbody").prepend("<tr><td colspan='6'>"+html+"</td></tr>");
JS;
$this->registerJs($script);
?>
