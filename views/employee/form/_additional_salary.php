<div class="row as">
    	<div class="col-md-12">
    	   <h4>Allowance</h4><hr>
    	</div>
</div>

<div class="row as allowance">
    <?php 
    $totalAllowance = 0;
    $i = 0;
    foreach($employeeAllowance as $allowance):?>
         
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $allowance->allowance->name?></label>
                 <input type="text" class="form-control allowance-amount" name="allowance[<?=$i?>][EmployeeSalaryAllowance][amount]" aria-invalid="false" value="<?= round($allowance->amount,2)?>">
                 <input type="hidden" class="form-control actual-allowance-amount" aria-invalid="false" value="<?= round($allowance->amount,2)?>">
                 <?php $totalAllowance += $allowance->amount;?>
                 <div class="help-block"></div>
                 <?php if(!empty($allowance->id)){?>
                 <input type="hidden" class="form-control" name="allowance[<?= $i?>][EmployeeSalaryAllowance][id]" aria-invalid="false" value="<?=$allowance->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control allowance-actual-amount" name="allowance[<?= $i?>][EmployeeSalaryAllowance][actual_amount]" aria-invalid="false" value="<?= round($allowance->actual_amount,2)?>">
                 <input type="hidden" class="form-control allowance-per-day" name="allowance[<?= $i?>][EmployeeSalaryAllowance][per_day]" aria-invalid="false" value="<?= round($allowance->per_day,2)?>">
                 <input type="hidden" class="form-control allowance-id" name="allowance[<?=$i?>][EmployeeSalaryAllowance][allowance_id]" aria-invalid="false" value="<?= $allowance->allowance_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++; 
         endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Total Allowance</label>
                 <input type="text" class="form-control allowance" name="EmployeeSalary[allowance]" aria-invalid="false" value="<?= round($totalAllowance,2)?>">
                 <div class="help-block"></div>
             </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Salary With Allowance</label>
                 <input type="text" class="form-control salary_with_allowance" name="EmployeeSalary[salary_with_allowance]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>

<div class="row as">
    	<div class="col-md-12">
    	   <h4>Employee Deduction</h4><hr>
    	</div>
</div>

<div class="row employee-deduction as">
    	<?php 
        $i = 0;
    foreach($employeeDeduction as $deduction):?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $deduction->deduction->name?></label>
                 <input type="text" class="form-control employee-deduction-amount" name="deduction[<?= $i?>][EmployeeSalaryDeduction][amount]" aria-invalid="false">
                 <div class="help-block"></div>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][EmployeeSalaryDeduction][type]" aria-invalid="false" value="1">
                 <?php if(!empty($deduction->id)){?>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][EmployeeSalaryDeduction][id]" aria-invalid="false" value="<?=$deduction->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control employee-deduction-rate" name="deduction[<?= $i?>][EmployeeSalaryDeduction][rate]" aria-invalid="false" value="<?= $deduction->rate?>">
                 <input type="hidden" class="form-control employee-deduction-id" name="deduction[<?= $i?>][EmployeeSalaryDeduction][deduction_id]" aria-invalid="false" value="<?= $deduction->deduction_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++;endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Employee Deduction</label>
                 <input type="text" class="form-control employee-deduction" name="EmployeeSalary[employee_deduction]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Payable Salary</label>
                 <input type="text" class="form-control payable-salary" name="EmployeeSalary[payable_salary]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>

<div class="row as">
    	<div class="col-md-12">
    	   <h4>Employer Deduction</h4><hr>
    	</div>
</div>

<div class="row employer-deduction as">
    <?php 
    foreach($employerDeduction as $deduction):?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $deduction->deduction->name?></label>
                 <input type="text" class="form-control employer-deduction-amount" name="deduction[<?= $i?>][EmployeeSalaryDeduction][amount]" aria-invalid="false">
                 <div class="help-block"></div>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][EmployeeSalaryDeduction][type]" aria-invalid="false" value="2">
                 <?php if(!empty($deduction->id)){?>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][EmployeeSalaryDeduction][id]" aria-invalid="false" value="<?=$deduction->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control employer-deduction-rate" name="deduction[<?= $i?>][EmployeeSalaryDeduction][rate]" aria-invalid="false" value="<?= $deduction->rate?>">
                 <input type="hidden" class="form-control employer-deduction-id" name="deduction[<?= $i?>][EmployeeSalaryDeduction][deduction_id]" aria-invalid="false" value="<?= $deduction->deduction_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++;endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Employer Deduction</label>
                 <input type="text" class="form-control employer-deduction" name="EmployeeSalary[employer_deduction]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>	
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Net Salary</label>
                 <input type="text" class="form-control net-salary" name="EmployeeSalary[net_salary]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>