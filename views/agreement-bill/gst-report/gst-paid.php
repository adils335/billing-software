<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Search\AgreementBill */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Gst Paid');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-md-12">
    <div class="agreement-bill-details box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-history margin-r-5"></i>
          <?= Yii::t('app', 'Gst Paid') ?>
        </h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="box-body">
        <?php echo $this->render('../_gst_search', ['model' => $searchModel]); ?>

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>S No.</th>
              <th>Agreement No</th>
              <th>Contract Company</th>
              <th>GSTIN of Recipient</th>
              <th>Invoice Number</th>
              <th>Invoice Date</th>
              <th>Invoice Value(Total)</th>
              <th>Place of Supply</th>
              <th>Tax Rate</th>
              <th>Taxable Value</th>
              <?php foreach ($taxes as $tax) {
                $totalTax[$tax->tax_id] = 0; ?>
              <th>
                <?php echo $tax->tax->name; ?>
              </th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
                    $totalTaxableAmount = 0;
                    foreach ($model as $agreementBill): ?>

            <tr>
              <td>
                <?=++$counter; ?>
              </td>
              <td>
                <?= $agreementBill->agreement->agreement_no ?>
              </td>
              </td>
              <td>
                <?= $agreementBill->agreement->contractCompany->name->agreement_no ?>
              </td>
              </td>
              <td>
                <?= $agreementBill->billing_company_gst ?>
              </td>
              <td>
                <?= $agreementBill->invoiceNo ?>
              </td>
              <td>
                <?= $agreementBill->invoice_date ?>
              </td>
              <td>
                <?= $agreementBill->payable_amount ?>
              </td>
              <td>
                <<= $agreementBill->billingCompanyState->state ?>
              </td>
              <td>
                <<= $agreementBill->taxRate ?>
              </td>
              <td>
                <?php echo $agreementBill->taxable_amount;
                      $totalTaxableAmount += $agreementBill->taxable_amount; ?>
              </td>
              <?php foreach ($taxes as $tax) { ?>
              <td>
                <?php $taxAmount = $agreementBill->getTaxAmountById($tax->tax_id);
                        echo $taxAmount;
                        $totalTax[$tax->tax_id] += $taxAmount; ?>
              </td>
              <?php } ?>
            </tr>
            <?php endforeach; ?>

            <tr>
              <td colspan="4" style="text-align:right"><b>Total</b></td>
              <td class="r-data"><b></b></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td class="r-data"><b>
                  <?= $totalTaxableAmount ?>
                </b></td>
              <?php foreach ($taxes as $tax) { ?>
              <td class="r-data"><b>
                  <?php echo $totalTax[$tax->tax_id]; ?>
                </b></td>
              <?php } ?>
            </tr>
            <tr>
              <td colspan="10" style="text-align:right"><b>Total</b></td>
              <td class="r-data"><b>
                  <?= array_sum($totalTax); ?>
                </b></td>
            </tr>

          </tbody>
        </table>

      </div>
    </div>
  </div>
</div>