<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Vendor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

    <div class="employee-index box box-primary"> 
		
		<div class="box-header with-border"> 
              
              <?= $this->render('view/_buttons', ['model' => $model])?>
              
              <?= $this->render('view/_details', ['model' => $model])?>

              <?= $this->render('view/_document', ['model' => $model])?>

              <?= $this->render('view/_account', ['model' => $model])?>
              
              <?= $this->render('view/_allowance', ['model' => $model])?>

              <?= $this->render('view/_date', ['model' => $model])?>

              <?= $this->render('form/_document', ['model' => $model,'document'=>$document])?>

              <?= $this->render('form/_account', ['model' => $model,'account'=>$account])?>
              
              <?= $this->render('form/_allowance', ['model' => $model, 'allowances' => $allowances])?>
              
              <?= $this->render('form/_date', ['model' => $model,'erpmeta'=>$erpmeta])?>
              
        </div>

    </div>
    
</div>