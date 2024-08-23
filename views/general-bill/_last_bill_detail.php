<?php $formatter = Yii::$app->formatter;?>
<div class="col-sm-12">
    <b>Last Invoice No:<?= $lastBill->session."/".sprintf('%02d',$lastBill->invoice_no)?></b>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php $startDate = $formatter->asDate($lastBill->invoice_date,'php:d-m-Y');?>
    <b>Last Invoice Date:<?= $startDate?></b>
</div>