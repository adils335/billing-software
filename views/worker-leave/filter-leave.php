<?php
use app\models\WorkerLeave;

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
        <?php foreach($workers as $worker):
        $LeaveModel = clone $model;
        $LeaveModel = $LeaveModel->andWhere(['worker_id'=>$worker->id,'month'=>$month->month])->one();
        $leaves = json_decode($LeaveModel->comments,true);
        ?>
        <tr>
            <td><?= $worker->name." (".$worker->code.") "?></td>
            <?php for($date = 1;$date <= $endDate;$date++){
             $currentDate = date("Y-m-d",strtotime($shortMonth."-".sprintf("%02d",$date)));
             $label = "P";$comment = "";
             if($leaves[$currentDate]){$label = "L";$comment = 'data-toggle="tooltip" title="'.$leaves[$currentDate].'"';}
             if(strtotime($currentDate) > strtotime(date("Y-m-d")) || WorkerLeave::isLeave($currentDate)){$label = "";}
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