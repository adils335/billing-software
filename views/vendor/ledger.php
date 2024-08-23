<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Ledger */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vendor Ledger');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ledger-index">
   <div class="ledger-index box box-primary"> 
		
    <?php  echo $this->render('_search_ledger', ['model' => $searchModel]); ?>
	
	<div class="row">
	     
	    <div class="col-md-12">
		
			  <div class="col-md-4">
			      <label>Name: <?= $data['name'];?></label>
			  </div>
			  
			  <div class="col-md-4">
			      <label>From Date: <?= $data['fromDate'];?></label>
			  </div>
			  
			  <div class="col-md-4">
			      <label>To Date: <?= $data['toDate'];?></label>
			  </div>
			  
	      </div>
		  <hr>
	</div>

	<div class="row">
	     
	    <div class="col-md-12">
		
			  <div class="col-md-3">
			      <label>Opening Balance: <?= $data['opening_bal'];?></label>
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
    
	<?php $balance = $data['opening_balance'];?>
	
    <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

		    [
            'header' => 'Date',
            'content' => function($model) {
                return \Yii::$app->formatter->asDate($model->date,"php:d-m-Y");
            }           
            ],
			
		    [
            'header' => 'Particular',
            'content' => function($model) {
                return $model->particularLabel;
            }           
            ],
			
		    [
            'header' => 'Debit',
            'content' => function($model)use (&$balance){
				$balance -= $model->debit;
                return $model->debit;
            }           
            ],
			
		    [
            'header' => 'Credit',
            'content' => function($model)use (&$balance){
				$balance += $model->credit;
                return $model->credit;
            }           
            ],
			
		    [
            'header' => 'Balance',
            'content' => function($model) use (&$balance) {
				$htmlBalance = $balance<0?abs($balance)." Dr.":$balance." Cr.";
                return $htmlBalance;
            }           
            ],
			
        ],
    ]); ?>


</div>
</div>
</div>
