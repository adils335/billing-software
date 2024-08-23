<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\Employee */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salary Report');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">
   <div class="employee-index box box-primary"> 
		
		<div class="box-header with-border"> 
		
    <div class="box-body">
        <?= $this->render('_balance_report_search',['model'=>$searchModel]);?>
        <table class="table">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <!--<th class="text-right">Last Balance</th>-->
                    <th class="text-right">Debit</th>
                    <th class="text-right">Credit</th>
                    <th class="text-right">Current Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php $counter = 1;$total = 0;
                if(!empty($model))
                foreach($model as $data){
                  $total += $data['ledger']['balance'];
                ?>
                  <tr>
                    <td><?= $counter++;?></td>
                    <td><?= $data['ledger']['name']?></td>
                    <!--<td class="text-right"><?= $data['ledger']['opening_bal']?></td>-->
                    <td class="text-right"><?= $data['ledger']['debit']?></td>
                    <td class="text-right"><?= $data['ledger']['opening_balance']+$data['ledger']['credit']?></td>
                    <td class="text-right"><?= $data['ledger']['bal']?></td>
                  </tr>
                <?php }?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"><label class="pull-right">Total</label></td>
                    <td><label class="pull-right"><?= $total>0?abs($total) . " Cr.": abs($total) . "Dr."?></label></td>
                </tr>
            </tfoot>
        </table>
   </div>
</div>
</div>
</div>