<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Vendor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vendor-view">
   
    <div class="vendor-index box box-primary"> 
        
        <div class="box-header with-border"> 
              
              <?= $this->render('view/_buttons', ['model' => $model])?>
              
              <?= $this->render('view/_details', ['model' => $model])?>
              
              <?= $this->render('view/_document', ['model' => $model])?>

              <?= $this->render('view/_account', ['model' => $model])?>

              <?= $this->render('form/_document', ['model' => $model,'document'=>$document])?>

              <?= $this->render('form/_account', ['model' => $model,'account'=>$account])?>
              
        </div>

    </div>
 
</div>
