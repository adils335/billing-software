<?php

namespace app\models;

use Yii;
use \app\models\base\EmployeeSalary as BaseEmployeeSalary;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee_salary".
 */
class EmployeeSalary extends BaseEmployeeSalary
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
    
	public function createPdf(){
		
		$tmp_path = Yii::getAlias('@webroot/employee-salary/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/employee/salary-slip", [
                                            'model' => $this,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/employee/pdf-footer',[
            'model' => $this,
        ]);
		
		if (!file_exists($tmp_path.$this->employee->emp_code)) {
          mkdir($tmp_path.$this->employee->emp_code, 0777, true);
        }
		$tmp_path .= $this->employee->emp_code."/";
		$filename = $this->month.".pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader(Yii::t('app', 'Salary slip')); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'F'); 
		
	}
	
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDeduction()
    {
        return \app\models\EmployeeSalaryDeduction::find()->where(['salary_id' => $this->id,'type'=>1])->all();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployerDeduction()
    {
        return \app\models\EmployeeSalaryDeduction::find()->where(['salary_id' => $this->id,'type'=>2])->all();
    }

	
}
