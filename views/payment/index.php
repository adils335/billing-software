<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Payment */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payments');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php 

    $gridColumns = [

        ['class' => 'yii\grid\SerialColumn'],

		    [
            'header' => 'Date',
            'content' => function($model) {
                return \Yii::$app->formatter->asDate($model->date,"php:d-m-Y");
            },
            'contentOptions'=>['style'=>'width: 8%;']
            ],
			
		    [
            'header' => 'From Head',
            'content' => function($model) {
                return $model->fromHeadLabel;
            },
            'contentOptions'=>['style'=>'width: 8%;']           
            ],
			
		    [
            'header' => 'From Account',
            'content' => function($model) {
                return $model->fromAccount;
            },
            'contentOptions'=>['style'=>'width: 14%;']           
            ],
            
		    [
            'header' => 'Payment Description',
            'content' => function($model) {
                $description = $model->getPaymentByRefNo($model->ref_no);
                
                $html = "<table class='table'>
                         <thead>
                            <tr>
                                <th>Company</th>
                                <th>Site</th>
                                <th>Head</th>
                                <th>Name</th>
                                <th>Particular</th>
                                <th>Amount</th>
                            </tr>
                          </thead>
                          <tbody>";
                          
                foreach($description as $payment){
                    $history = Html::a('<span class="glyphicon glyphicon-repeat"></span>', ['#'], ['data-toggle'=>'modal','data-target'=>'#payment-history','payment_id'=>$payment->id]);
                    $recentHistory = $payment->recentHistory;
                    $class = $recentHistory?"bg-danger":"";
                    $history = $recentHistory?$history:"";
                    $html .= "<tr class='".$class."'>
                                <td>".$payment->contractCompany->name."</td>
                                <td>".$payment->site->name."</td>
                                <td>".$payment->paymentHeadLabel."</td>
                                <td>".$payment->toAccount."</td>
                                <td>".$payment->particular."</td>
                                <td>".$payment->net_amount. $history ."</td>
                             </tr>";
                }   
                
                $html .= "</tbody>
                         </table>";          
                
                return $html;
            },
            'contentOptions'=>['style'=>'width: 62%;']           
            ],
			
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}{history}',
            'buttons' => [
                'history' => function( $url, $model ){
                    if( $model->hasHistory() ){
                        return Html::a('<span class="glyphicon glyphicon-repeat"></span>', [$url], []);
                    }
                    return '';
                }
            ],
			    
			'urlCreator' => function ($action, $model, $key, $index) {
				if($action == "update")
                     return Url::to(['payment/update','ref_no' => $model['ref_no']]);
				if($action == "delete")
                     return Url::to(['payment/delete','ref_no' => $model['ref_no']]);	
				if($action == "view")
                     return Url::to(['payment/view','ref_no' => $model['ref_no']]);
				if($action == "history")
                     return Url::to(['payment/history','ref_no' => $model['ref_no']]);

               },
            'contentOptions'=>['style'=>'width: 6%;']
			]

    ]

?>
<div class="payment-index">
   <div class="payment-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Payment'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span></h1>


    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
	
    <?//= GridView::widget([
        //'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        // 'columns' => [
        //     ['class' => 'yii\grid\SerialColumn'],

		//     [
        //     'header' => 'Date',
        //     'content' => function($model) {
        //         return \Yii::$app->formatter->asDate($model->date,"php:d-m-Y");
        //     },
        //     'contentOptions'=>['style'=>'width: 8%;']
        //     ],
			
		//     [
        //     'header' => 'From Head',
        //     'content' => function($model) {
        //         return $model->fromHeadLabel;
        //     },
        //     'contentOptions'=>['style'=>'width: 8%;']           
        //     ],
			
		//     [
        //     'header' => 'From Account',
        //     'content' => function($model) {
        //         return $model->fromAccount;
        //     },
        //     'contentOptions'=>['style'=>'width: 14%;']           
        //     ],
            
		//     [
        //     'header' => 'Payment Description',
        //     'content' => function($model) {
        //         $description = $model->getPaymentByRefNo($model->ref_no);
                
        //         $html = "<table class='table'>
        //                  <thead>
        //                     <tr>
        //                         <th>Company</th>
        //                         <th>Site</th>
        //                         <th>Head</th>
        //                         <th>Name</th>
        //                         <th>Particular</th>
        //                         <th>Amount</th>
        //                     </tr>
        //                   </thead>
        //                   <tbody>";
                          
        //         foreach($description as $payment){
        //             $history = Html::a('<span class="glyphicon glyphicon-repeat"></span>', ['#'], ['data-toggle'=>'modal','data-target'=>'#payment-history','payment_id'=>$payment->id]);
        //             $recentHistory = $payment->recentHistory;
        //             $class = $recentHistory?"bg-danger":"";
        //             $history = $recentHistory?$history:"";
        //             $html .= "<tr class='".$class."'>
        //                         <td>".$payment->contractCompany->name."</td>
        //                         <td>".$payment->site->name."</td>
        //                         <td>".$payment->paymentHeadLabel."</td>
        //                         <td>".$payment->toAccount."</td>
        //                         <td>".$payment->particular."</td>
        //                         <td>".$payment->net_amount. $history ."</td>
        //                      </tr>";
        //         }   
                
        //         $html .= "</tbody>
        //                  </table>";          
                
        //         return $html;
        //     },
        //     'contentOptions'=>['style'=>'width: 62%;']           
        //     ],
			
        //     ['class' => 'yii\grid\ActionColumn','template'=>'{view}{update}{delete}{history}',
        //     'buttons' => [
        //         'history' => function( $url, $model ){
        //             if( $model->hasHistory() ){
        //                 return Html::a('<span class="glyphicon glyphicon-repeat"></span>', [$url], []);
        //             }
        //             return '';
        //         }
        //     ],
			    
		// 	'urlCreator' => function ($action, $model, $key, $index) {
		// 		if($action == "update")
        //              return Url::to(['payment/update','ref_no' => $model['ref_no']]);
		// 		if($action == "delete")
        //              return Url::to(['payment/delete','ref_no' => $model['ref_no']]);	
		// 		if($action == "view")
        //              return Url::to(['payment/view','ref_no' => $model['ref_no']]);
		// 		if($action == "history")
        //              return Url::to(['payment/history','ref_no' => $model['ref_no']]);

        //        },
        //     'contentOptions'=>['style'=>'width: 6%;']
		// 	],
        // ],
    //]); ?>

    <?php echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => 'Export',
                'class' => 'btn btn-outline-secondary btn-default'
            ]
            ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]);
    
        ?>


</div>
</div>
</div>
</div>

<?php 
Modal::begin([
    'id'=>'payment-history',
    'header' => '<h2>History</h2>'
]);

Modal::end();
?>

<?php 
$paymentHistoryUrl = Url::to(['ajax-payment-history']);
$script = <<<JS
   $(".payment-history-btn").click(function(){
       var id = $(this).attr("payment_id");
       $.ajax({
           url:"$paymentHistoryUrl",
           data:{id},
           success:function(res){
               
           }
       })
   });
JS;
$this->registerJs($script);
?>
