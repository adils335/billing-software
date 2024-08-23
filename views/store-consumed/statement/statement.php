<?php

use app\models\StoreConsumed;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\StoreConsumedItems;
use app\models\StoreIssue;

/** @var yii\web\View $this */
/** @var app\models\Search\StoreConsumed $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Store Statement';
$this->params['breadcrumbs'][] = $this->title;
//echo "<pre>";print_r($issue);die();

?>
<div class="store-consumed-index box box-primary"> 
    <div class="box-header with-border">
        <?=$this->render('_search', ['model' => $searchModel]); ?>

        <table class="table store-indents-items-body" id="consumed_statement" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Sno</th>
                    <th>Date</th>
                    <th>Gate Pass No</th>
                    <?php foreach($products as  $product_name):?>
                        <th><?= $product_name?></th>
                    <?php endforeach;?>
                </tr>
            </thead>

            <tbody>
                
            <?php
            $i=1;
            $issue_total_qty = [];
            foreach($issue as $data):?>
                <tr>
                    <td><?=$i++?></td>
                    <td><?= Yii::$app->formatter->asDate($data->date,'php:d-m-Y')?></td>
                    <td><?= $data->gate_pass_no?></td>
                    <?php foreach($products as $product_id => $product_name):
                        $issue_qty = $data->getIssueProductQuantity($product_id);
                        $issue_total_qty[$product_id] = empty( $issue_total_qty[$product_id] )?$issue_qty:$issue_total_qty[$product_id]+$issue_qty;
                    ?>
                    <td><?= $issue_qty?></td>
                    <?php endforeach;?>  
                </tr>
                <?php endforeach;?>
                
                <tr>
                    <td></td>
                    <td><span><strong>Total Store Issue</strong></span></td>
                    <td></td>
                    <?php foreach($products as $product_id => $product_name):?>
                    <td><strong><?= $issue_total_qty[$product_id]?></strong></td>
                    <?php endforeach;?>
                </tr>
            

            <?php 
            $j=1;
            $consumed_total_qty = [];
            foreach($consumed as $data):?>
                <tr>
                    <td><?=$j++?></td>
                    <td>Store Consumed Bill No:<?= $data->bill_no?></td>
                    <td></td>
                    <?php foreach($products as $product_id => $product_name):
                        $consumed_qty = $data->getConsumedProductQuantity($product_id);
                        $consumed_total_qty[$product_id] = empty( $consumed_total_qty[$product_id] )?$consumed_qty:$consumed_total_qty[$product_id]+$consumed_qty;
                    ?>
                        <td><?= $consumed_qty;?></td>
                    <?php endforeach;?>  
                </tr>
                <?php endforeach;?>
                
                <tr>
                    <td></td>
                    <td><span><strong>Total Store Consumed</strong></span></td>
                    <td></td>
                    <?php foreach($products as $product_id => $product_name):?>
                    <td><strong><?= $consumed_total_qty[$product_id]?></strong></td>
                    <?php endforeach;?>
                </tr>
                <?php 
                ?>
                

                <tr>

                    <td></td>
                    <td><span><strong>Total Store Balance</strong></span></td>
                    <td></td>
                    <?php 
                        $total_qty = [];

                        foreach($products as $product_id => $product_name):

                    ?>
                    <td><strong>
                       <?= $issue_total_qty[$product_id]- $consumed_total_qty[$product_id]?>
                    </strong></td>

                    <?php endforeach;?>
                </tr>
            </tbody>
        </table>

    </div>
</div>

<?php 
$script = <<<JS

$(document).ready(function() {
    $('#consumed_statement').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        info: false,
        ordering: false,
        paging: false,
        bFilter: false,
        
    } );
} );

JS;
$this->registerJs($script);
?>

