<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AgreementBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Agreement Bills');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agreement-bill-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Agreement Bill'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'agreement_id',
            'invoice_no',
            'invoice_date',
            'order_no',
            ['class' => 'yii\grid\ActionColumn',
            'template'=> '{view}&nbsp;&nbsp;{pdf}&nbsp;&nbsp;{update}&nbsp;&nbsp;{sync_irn}&nbsp;&nbsp;{cancel_irn}&nbsp;&nbsp;{delete}',
			 
			 'buttons' => [
                'view' => function ($url, $model, $key) { 
					   return Html::a('<i class="fa fa-eye" aria-hidden="true"></i>',
     					   ['agreement-bill/view','id'=>$model->id]);
                },
                'pdf' => function ($url, $model, $key) {
				   
                    return Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>',
                         ['agreement bill/'.$model->company_id.'-'.$model->session.'-'.$model->invoice_no.'.pdf'],
                        ['target'=>'_blank']);
                },
                'sync_irn' => function ($url, $model, $key) { 
                    return Html::a('<i class="fa fa-sync" aria-hidden="true"></i>',
                    ['#'],['class'=>'show-sidebar-popup','data-url'=>Url::to(['//einvoice/verify-irn','id'=>$model->id]),"swidth"=>"70",'title'=>'Sync to einvoice portal']);
                },
                'cancel_irn' => function ($url, $model, $key) { 
                    if( !empty( $model->irn_no ) && empty( $model->cancel_date ) ){
                        return Html::a('<i class="fa fa-times" aria-hidden="true"></i>',
                        ['#'],['bill_id'=>$model->id ,'class'=>'show-sidebar-popup','data-url'=>Url::to(['//einvoice/cancel-reason-irn','id'=>$model->id]),'title'=>'cancel irn no']);
                    }
                },
                'delete' => function ($url, $model, $key) {
                        $actionUrl = Url::to('../agreement-bill/change-status');
                        $html = "<select class='bill-action' id='" . $model->id . "' url='$actionUrl' refresh='0'>";
                        $statuses = $model::buildStatus();
                            foreach( $statuses as $key => $status ){
                                $selected = $key == $model->status ? "selected":"";
                                $html .= "<option value='$key' $selected>$status</option>";
                            }
                        $html .= "</select>";
                      return  $html;
                 }
                
                ],
                'visibleButtons' => [
                    'sync' => function ($model, $key, $index) {
                        return $model->has_sync == 1;
                    },
                ],
    			'urlCreator' => function ($action, $model, $key, $index) {
    				   if($action == "update")
                            return Url::to(['agreement-bill/update','id' => $model['id']]);
    				  // if($action == "delete")
                        //    return Url::to(['agreement-bill/delete','id' => $model['id']]);	
                   }	
    			],
        ],
    ]); ?>


</div>
