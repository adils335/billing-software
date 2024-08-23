<?php
use app\models\EmployeeLeave;

$formatter = Yii::$app->formatter;
$monthModel = clone $model;
$monthModel = $monthModel->groupBy(['month'])->all();
foreach($monthModel as $month):
    $endDate = date("t",strtotime($month->month)); 
    $shortMonth = date("Y-m",strtotime($month->month)); 
?>
<h3><?= $formatter->asDate($month->month,'php:M-Y');?></h3>
<table class="table">
    <thead>
        <tr>
           <th>Name</th>
           <?php for($date = 1;$date <= $endDate;$date++){?>
           <th><?= sprintf("%02d",$date)."<br>".date("D",strtotime($shortMonth."-".sprintf("%02d",$date)))[0]?></th>
           <?php }?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($employees as $employee):
        $LeaveModel = clone $model;
        $LeaveModel = $LeaveModel->andWhere(['employee_id'=>$employee->id,'month'=>$month->month])->one();
        $leaves = json_decode($LeaveModel->comments,true);
        ?>
        <tr>
            <td><?= $employee->emp_name." (".$employee->emp_code.") "?></td>
            <?php for($date = 1;$date <= $endDate;$date++){
             $currentDate = date("Y-m-d",strtotime($shortMonth."-".sprintf("%02d",$date)));
             $label = "P";$comment = "";
             if($leaves[$currentDate]){$label = "L";$comment = 'data-toggle="tooltip" title="'.$leaves[$currentDate].'"';}
             if(strtotime($currentDate) > strtotime(date("Y-m-d")) || EmployeeLeave::isLeave($currentDate)){$label = "";}
            ?>
            <td class="leave-column" <?= $comment?>><span  class="leave-status-<?= $label?>"><?= $label?></span></td>
            <?php }?>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php endforeach;

$script = <<<JS
$(document).ready(function(){
       $('[data-toggle="tooltip"]').tooltip();   
    });
JS;
$this->registerJs($script);