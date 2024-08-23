<?php

use yii\helpers\Html;
use NumberToWords\NumberToWords;
/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */

// create the number to words "manager" class
$numberToWords = new NumberToWords();

// build a new number transformer using the RFC 3066 language identifier
$numberTransformer = $numberToWords->getNumberTransformer('en');

$formatter = Yii::$app->formatter;
$converter = Yii::$app->currency_formator;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <style>
        .tax-table,
        .main-table {
            border-collapse: collapse;
            overflow: wrap;
        }

        .tax-table td,
        .main-table td {
            border: 1px solid black;
        }

        .tax-table {
            font-size: 14px;
            width: 100%;
        }

        .bg-blue {
            background-color: #0066FF !important;
            color: #fff !important;
        }

        .text-blue {
            color: darkblue !important;
        }

        .text-red {
            color: darkred !important;
        }

        .r-text {
            text-align: right !important;
            padding-right: 3px !important;
        }

        .l-text {
            text-align: left !important;
            padding-left: 3px !important;
        }

        .m-text {
            text-align: center !important;
        }

        /*.v-text{
                			  verticle-align:center !important;
                			  font-size:36px !important;
                		  }*/
        .sf {
            font-size: 11px !important;
            font-weight: bold !important;
        }

        .mf {
            font-size: 12px !important;
            font-weight: bold !important;
        }

        .lf {
            font-size: 15px !important;
            font-weight: bold !important;
        }

        .w5 {
            width: 5% !important;
        }

        .w6 {
            width: 6% !important;
        }

        .w7 {
            width: 7% !important;
        }

        .w8 {
            width: 8% !important;
        }

        .w9 {
            width: 9% !important;
        }

        .w10 {
            width: 10% !important;
        }

        .w11 {
            width: 11% !important;
        }

        .w12 {
            width: 12% !important;
        }

        .w25 {
            width: 25% !important;
        }

        .w40 {
            width: 40% !important;
        }

        .w54 {
            width: 54% !important;
        }

        .w75 {
            width: 75% !important;
        }

        .w30 {
            width: 30% !important;
        }

        .w20 {
            width: 20% !important;
        }

        .logo-table {
            border-collapse: collapse;
            overflow: wrap;
            width: 100%;
        }

        .logo-table td {
            border: 1px solid black;
        }

        /* Start Here*/
        .w35 {
            font-size: 14px;
            text-align: center;
            border-bottom: none;
        }

        .v-text {
            text-align: center !important;
            font-size: 18px !important;
            border-bottom: none;
        }

        .text-left {
            text-align: left !important;
        }

        .center-td {
            text-align: left !important;
        }

        .gst-table {
            border-collapse: collapse;
            overflow: wrap;
            width: 100% !important;
        }

        .gst-table td {
            border: none;
        }

        .top-border-remove {
            border-top: none;
        }

        .s-text {
            font-size: 10px;
            text-align: center
        }

        .text-center {
            text-align: center;
        }

        .header-text-size {
            font-size: 12px;
        }

        .items-header {
            background-color: #bababa;
            font-weight: 700;
        }

        .parapragh-font-size {
            font-size: 11px;
            padding: 5px !important;
        }

        .main-table tr td {
            padding: 5px !important;
        }

        .gst-table tr td {
            padding: 0px !important;
        }

        .main-table tr td{
            padding:0;
        }
        .item-table {
            border-collapse: collapse;
            overflow: wrap;
            width: 100%;
            margin: 0!important;
            padding: 0!important;
        }
        .item-table tr td {
            border: none;
            border-right: 1px solid black;
        }

        .item-table tr td:last {
            border-right: none !important;
            color: red;
        }

        .b-show {
            border: 1px solid black;
        }
        .pad-none{
            padding:0!important;
            margin:0!important;
        }
    </style>
</head>

<body>
    <table class="logo-table">
        <tr>
            <td class="w35 v-text header-text-size"><b>Tax Invoice</b></td>
        </tr>
    </table>
    <table class="logo-table">
        <tr>
            <td class="v-text">
                <h4 class="text-center"><b><?= $model->company->name ?></b></h4>
                <p class="header-text-size text-center">Address:
                    <?= $model->company->address; ?>
                </p>
                <p class="header-text-size text-center">
                    <?= $model->company->districtName->district  . ', ' . $model->company->stateName->state . ' (' . $model->company->pincode . ')'; ?>
                </p>
                <p class="header-text-size text-center">Email:
                    <?= $model->company->email; ?>
                </p>
                <p class="header-text-size text-center">Tele. No :
                    <?= $model->company->number; ?>
                </p>
            </td>
        </tr>

    </table>
    <table class="main-table">

        <tr>
            <td colspan="12">
                <table class="gst-table">
                    <tr>
                        <td class="text-left right-border-remove header-text-size">GST IN :
                            <?= $model->company->gst_no ?? ""; ?>
                        </td>
                        <td class="border-remove header-text-size">PAN :
                            <?= $model->company->pancard_no ?? ""; ?>
                        </td>
                        <td class="text-left header-text-size">CIN :
                            <?= $model->company->cin_no ?? ""; ?>
                        </td>
                    </tr>

                </table>
            </td>

        </tr>

        <tr>
            <td colspan="6" rowspan="3">
                <H5 class="parapragh-font-size"><b>To,</b></H5>
                <p class="parapragh-font-size">
                    <?= $model->agreement->contractCompany->name ?? ""; ?>
                </p>
                <p class="parapragh-font-size">
                    <?= $model->agreement->contractCompanyState->state ?? ""; ?>
                </p>
                <p class="parapragh-font-size">Meerut, Uttar Pradesh (250110)</p>
                <br>
                <p class="parapragh-font-size">Email :
                    <?= $model->company->email ?? ""; ?>
                </p>
                <p class="parapragh-font-size">Contact : Hindustan Engineering Services</p>
                <p class="parapragh-font-size">State :
                    <?= $model->company->stateName->state ?? ""; ?>
                </p>
                <p class="parapragh-font-size">GST IN :
                    <?= $model->company->gst_no ?? ""; ?>
                </p>
                <p class="parapragh-font-size">PAN NO :
                    <?= $model->company->pancard_no ?? ""; ?>
                </p>
            </td>
            <td colspan="3">
                <p class="parapragh-font-size">Order No :</p>
                <p class="parapragh-font-size">
                    <?= $model->invoice_no; ?>
                </p>
            </td>
            <td colspan="3">
                <p class="parapragh-font-size">Dated :</p>
                <p class="parapragh-font-size">
                    <?= $formatter->asDate($model->invoice_date, 'php:d/m/Y'); ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="parapragh-font-size">Mode Of Transport:</p>
                <p class="parapragh-font-size">
                    <?= $model->modeOfTransport->name ?? ""; ?>
                </p>
            </td>
            <td colspan="3">
                <p class="parapragh-font-size">Transporter : </p>
                <p class="parapragh-font-size">
                    <?= $model->transporter ?? ""; ?>
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="parapragh-font-size">GR/LR NO :</p>
                <p class="parapragh-font-size">
                    <?= $model->gr_no ?? ""; ?>
                </p>
            </td>
            <td colspan="3">
                <p class="parapragh-font-size">Vehicle NO : </p>
                <p class="parapragh-font-size">
                    <?= $model->vehicle_no ?? ""; ?>
                </p>
            </td>
        </tr>

        <tr>
            <td colspan="6" class="header-text-size">
                <H5><b>Ship To,</b></H5>
                <p class="parapragh-font-size">
                    <?= $model->billingCompany->name ?? ""; ?>
                </p>
                <p class="parapragh-font-size">
                    <?= $model->billingCompanyDistrict->district ?? ""; ?>
                </p>
                <p class="parapragh-font-size">
                    <?= $model->billingCompanyState->state ?? ""; ?>
                </p>
            </td>
            <td colspan="6" class="m-text">
            </td>
        </tr>

        <tr>
            <td colspan="12" class="parapragh-font-size">
                <p>We are pleased to place with you the order for supply/Service of the following items subject to the terms and conditions as given.</p>
            </td>
        </tr>

        <tr class="pad-none">
            <td colspan="12" class="pad-none">
                <table class="item-table">
                    <tr class="items-header">
                        <td colspan="1" class="m-text sf w5">S.No</td>
                        <td colspan="2" class="m-text sf w8">Particulars</td>
                        <td colspan="1" class="m-text sf w8">HSN/SAC</td>
                        <td colspan="2" class="m-text sf w8">UOM</td>
                        <td colspan="2" class="r-text sf w7">Qty</td>
                        <td colspan="2" class="r-text sf w8">Rate</td>
                        <td colspan="2" class="r-text sf w10">Amount</td>
                    </tr>

                    <?php
                    $sn = 1;
                    $billItems = $model->billItems;
                    foreach ($billItems as $item) { ?>

                        <tr class="item">
                            <td colspan="1" class="m-text sf">
                                <?= $sn++; ?>
                            </td>
                            <td colspan="2" class="m-text sf">
                                <?= $item->item; ?>
                            </td>
                            <td colspan="1" class="m-text sf">
                                <?= $item->hsn_no; ?>
                            </td>
                            <td colspan="2" class="m-text sf">
                                <?= $item->unitName->name; ?>
                            </td>
                            <td colspan="2" class="r-text sf">
                                <?= $item->quantity; ?>
                            </td>
                            <td colspan="2" class="r-text sf">
                                <?= $item->rate; ?>
                            </td>
                            <td colspan="2" class="r-text sf">
                                <?= $item->amount; ?>
                            </td>
                        </tr>

                    <?php
                    } ?>

                    <?php
                    while ($sn < 13) { ?>

                        <tr class="item">
                            <td colspan="1" class="m-text sf">&nbsp;</td>
                            <td colspan="2" class="m-text sf">&nbsp;</td>
                            <td colspan="1" class="m-text sf">&nbsp;</td>
                            <td colspan="2" class="m-text sf">&nbsp;</td>
                            <td colspan="2" class="r-text sf">&nbsp;</td>
                            <td colspan="2" class="r-text sf">&nbsp;</td>
                            <td colspan="2" class="r-text sf">&nbsp;</td>
                        </tr>

                    <?php $sn++;
                    } ?>
                    <tr class="b-show">
                        <td colspan="10" class="r-text sf">Total</td>
                        <td colspan="2" class="r-text sf">
                            <?= $model->base_amount; ?>
                        </td>
                    </tr>

                    <?php if ($model->billTaxes): ?>

                        <?php
                        $billTaxes = $model->billTaxes;
                        foreach ($billTaxes as $tax): ?>
                            <tr class="b-show">
                                <td colspan="8" class="r-text sf">&nbsp;</td>
                                <td colspan="2" class="r-text sf">
                                    <?= $tax->tax->name; ?> @
                                    <?= $tax->rate; ?>
                                </td>
                                <td colspan="2" class="r-text sf">
                                    <?= $tax->amount; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <tr class="b-show">
                            <td colspan="8" class="r-text sf">&nbsp;</td>
                            <td colspan="2" class="r-text sf">Total Tax</td>
                            <td colspan="2" class="r-text sf">
                                <?= $model->tax_amount; ?>
                            </td>
                        </tr>

                    <?php endif; ?>

                    <tr class="b-show">
                        <td colspan="8" class="l-text sf text-red">&nbsp;</td>
                        <td colspan="2" class="r-text sf">Total Amount</td>
                        <td colspan="2" class="r-text sf">
                            <?= $model->payable_amount; ?>
                        </td>
                    </tr>

                    <tr class="b-show">
                        <td colspan="8" class="l-text sf text-blue">&nbsp;</td>
                        <td colspan="2" class="r-text sf"><b style="font-size:10px">TOTAL INVOICE VALUE</b></td>
                        <td colspan="2" class="r-text sf">
                            <?= sprintf("%0.2f", round($model->payable_amount)); ?>
                        </td>
                    </tr>

                    <tr class="b-show">
                        <td colspan="12" class="l-text sf"><b>Amount in Words:-</b>
                            <?= $converter->toWords(round($model->payable_amount)); ?>
                        </td>
                    </tr>



                </table>
            </td>
        </tr>
        <tr>
            <td colspan="12" class="r-text" style="border:none !important">
                <?= $model->signature->signature ?>
            </td>
        </tr>


    </table>
    <br>
    <div class="r-text">

    </div>

</body>

</html>

<?php
$this->endPage(); ?>