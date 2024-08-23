<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Agreement */

$this->title = $model->agreement_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Quotation'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('files/_bills', [ 'model' => $model,'searchModel'=>$searchModelBill,'dataProvider'=>$dataProviderBill]) ?> 
<div class="agreement-view box box-primary">
              
    <div class="box-header">
        <h1>
		    <?= $this->title;?>
			<?= $this->render('view/_button', ['model' => $model])?>
		</h1>
    </div>
    <div class="box-body">
        <div class="agreement-view"> 
               <?= $this->render('view/_agreement', ['model' => $model])?>
               <?= $this->render('view/_rate_schedule', ['model' => $model])?>
               <?= $this->render('view/_tax', ['model' => $model])?>
        </div>
        
    </div>
</div>
