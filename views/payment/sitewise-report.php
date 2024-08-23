<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Payment */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sitewise Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">
   <div class="payment-index box box-primary"> 
		<div class="box-header with-border"> 
            <?php  echo $this->render('_sitewise_search', ['model' => $searchModel]); ?>
        </div>
    </div>
</div>
