<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Vendor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Vendors');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
    $gridColumns = [

        ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            [
                'header'=>'Address',
                'content'=>function($model){
                    $address = $model->address;
                    if($model->district_id){
                        $address .= " ".$model->district->district;
                    }
                    if($model->state_id){
                        $address .= " ".$model->state->state;
                    }
                  return $model->address;
                },
                'contentOptions'=>['style'=>'width: 20%;']
            ],
            'mobile',
            'email:email',
            [
                'header'=>'Balance',
                'content'=>function($model){
                    $bal = $model->last_balance;
                    if($model->balance_type){
                      $bal .= " ".$model->balanceTypeLabel;
                    }
                  return $bal;
                }
            ],
            [
                'header'=>'Company',
                'content'=>function($model){
                  return $model->company->name;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],

    ]
?>
<div class="vendor-index">
   <div class="vendor-index box box-primary"> 
		
		<div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?>
    <span class="pull-right">
        <?= Html::a(Yii::t('app', 'Create Vendor'), ['create'], ['class' => 'btn btn-success btn-sm']) ?>
    </span></h1>


    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="box-body">
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
