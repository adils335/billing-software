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
        <title><?= Html::encode($this->title) ?></title> 
		<style>
		  .tax-table,.main-table {
            border-collapse: collapse;
            overflow: wrap;
          }

		  .main-table th, .main-table td {
             height: 30px;
             border:0;
          }
		  .bg-blue{
			 background-color:#0066FF !important;
             color:#fff !important;			 
		  }
		  .text-blue{
			  color:#0066FF !important;
		  }
		  .text-red{
			  color:red !important;
		  }
		  .r-text{
			  text-align:right !important;
			  padding-right:3px !important;
		  }
		  .l-text{
			  text-align:left !important;
			  padding-left:3px !important;
		  }
		  .m-text{
			  text-align:center !important;
		  }
		  .v-text{
			  verticle-align:center !important;
			  font-size:36px !important;
		  }
		  .sf{
			  font-size:9px !important;
			  font-weight:bold !important;
		  }
		  .mf{
			  font-size:12px !important;
			  font-weight:bold !important;
		  }
		  .lf{
			  font-size:16px !important;
			  font-weight:bold !important;
		  }
		  .w5{
		      width:5% !important;
		  }
		  .w43{
		     width:43% !important; 
		  }
		  .w8{
		      width:8% !important;
		  }
		  .w12{
		      width:12% !important;
		  }
		  .salary-detail{
		      width:100%;
		      border-collapse: collapse;
              overflow: wrap;
		  }
		  .salary-detail th, .salary-detail td {
             border: 1px solid #ddd;
          }
          .ptext{
              padding-left:20px;
          }
		  
		</style>
  </head>
  <body>
        
		<table class="main-table">
		     
			 <tr>
			     <td colspan="4" class="text-red m-text v-text">
					<img src="<?= \Yii::getAlias('@webroot/upload/logo/').$model->company->logo;?>" style="height:30px;">
					<span  style="letter-spacing: 5px;"><?= $model->company->name?></span>
				 </td>
			 </tr>
		
			 <tr>
			     <td colspan="4" class="text-blue m-text"><b><?= $model->company->address.', '.$model->company->districtName->district.', '.$model->company->stateName->state.' ('.$model->company->pincode.')';?></b></td>
			 </tr>
			 
			 <tr>
			     <td colspan="4" class="m-text"><b>Salary Slip for the month of <?= $formatter->asDate($model->month,'php:M/Y')?></b></td>
			 </tr>
		
			 <tr>
			     <td class="ptext">Emp Id</td>
			     <td><?= $model->employee->emp_code?></td>
			     <td class="ptext">Employee Name</td>
			     <td><?= $model->employee->emp_name?></td>
			 </tr>
		
			 <tr>
			     <td class="ptext">EPF No</td>
			     <td><?= $model->employee->epf_no?></td>
			     <td class="ptext">ESI No</td>
			     <td><?= $model->employee->esi_no?></td>
			 </tr>
			 
			 <tr>
			     <td class="ptext">Pay days</td>
			     <td><?php if( date( "Y-m", strtotime( $model->month ) ) == date( "Y-m", strtotime( $model->employee->joining_date ) )  ){
			                   echo date("t",strtotime($model->month))-date( "d", strtotime( $model->employee->joining_date ) )-$model->leave+1;
			               }else{
			                   echo date("t",strtotime($model->month))-$model->leave;
			               }?></td>
			     <td class="ptext">Designation</td>
			     <td><?= $model->employee->designationName->designation?></td>
			 </tr>
			 
			 <tr>
			     <td class="ptext">PAN</td>
			     <td><?= $model->employee->pancard?></td>
			     <td class="ptext">Aadhar</td>
			     <td><?= $model->employee->aadhar?></td>
			 </tr>
			 <?php
			      $salary[]['salary'] = ['title'=>'Base Salary','rate'=>$model->base_salary,'amount'=>$model->salary];
			      $allowances = $model->employeeSalaryAllowances;
			      foreach($allowances as $allowance){
			          $salary[]['salary'] = ['title'=>$allowance->allowance->name,'rate'=>$allowance->actual_amount,'amount'=>$allowance->amount];
			      }
			      $i = 0;
			      $deductions = $model->employeeDeduction;
			      //echo "<pre>";print_r($deductions);die();
			      if( !empty($deductions) ){
			        foreach($deductions as $deduction){
			            $salary[$i]['deduction'] = ['title'=>$deduction->deduction->name,'rate'=>$deduction->rate,'amount'=>$deduction->amount];
			            $i++;
			        }
			      }
			 ?>
			 <tr>
			     <td colspan="4">
			         <table  class="salary-detail">
			             <tr>
			                 <th class="l-text">Earnings</th>
			                 <th class="r-text">Salary Rate</th>
			                 <th class="r-text">Amount</th>
			                 <th class="l-text">Deductions</th>
			                 <th class="r-text">Amount</th>
			             </tr>
			             <?php $total_rate = 0;
			             foreach($salary as $value):
			             ?>
			             <tr>
			                 <td class="l-text"><?= !empty($value['salary']['title'])?$value['salary']['title']:""; ?></td>
			                 <td class="r-text"><?= $value['salary']['rate']??"0";  $total_rate += $value['salary']['rate']??0;?></td>
			                 <td class="r-text"><?= !empty($value['salary']['amount'])?$value['salary']['amount']:""; ?></td>
			                 <td class="l-text"><?= !empty($value['deduction']['title'])?$value['deduction']['title']:""; ?></td>
			                 <td class="r-text"><?= !empty($value['deduction']['rate'])?$value['deduction']['amount']:""; ?></td>
			             </tr>
			             <?php endforeach;?>
			             
			             <tr>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			             </tr>
			             
			             <tr>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			                 <td>&nbsp;</td>
			             </tr>
			             
			             <tr>
			                 <th class="l-text">Total</th>
			                 <th class="r-text"><?= $total_rate?></th>
			                 <th class="r-text"><?= $model->salary_with_allowance?></th>
			                 <th class="l-text">Total</th>
			                 <th class="r-text"><?= $model->employee_deduction?></th>
			             </tr>
			             <tr>
			                 <td colspan="4" rowspan="4" style="border-right:none">
			                     Net Pay:- <?= $model->payable_salary?>
			                     <br><br>
			                     In Words:- <?= $converter->toWords(round($model->payable_salary));?>
			                 </td>
			             </tr>
			             <tr>
			                 <td colspan="1"  style="border:none;border-right: 1px solid #ddd"></td>
			             </tr>
			             <tr>
			                 <td colspan="1"  style="border:none;border-right: 1px solid #ddd"></td>
			             </tr>
			             <tr>
			                 <td colspan="1" style="border-left:none;border-top:none;">
			                     Signature
			                 </td>
			             </tr>
			         </table>
			     </td>
			 
			 </tr>
			 
			 <tr>
			     <td colspan="4" style="border-bottom: 1px solid black;border-style: dashed;">
			     </td>
			 </tr>
			 
			 <tr>
			     <td colspan="4"><br>
			         ********** THIS IS A COMPUTER GENERATED PAYSLIP, NOT REQUIRING SIGNATURE **********
			     </td>
			 </tr>
			 
		</table>

  </body>
</html>

<?php 
$this->endPage() ; ?>
