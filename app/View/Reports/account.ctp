<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default card-view">
              <?php echo $this->Session->flash(); ?>
                <div class="panel-heading">
                    <div class="pull-left">
                        <h6 class="panel-title txt-dark">Account Reports 
							<select name="accountReport" style="height:30px;width:250px;margin-left:20px;">
								<option value="">Select Report</option>
								<option value="bank_book">Bank Book</option>
								<option value="bank_reconciliation">Bank Reconciliation</option>
								<option value="cash_book">Cash Book</option>
								<option value="member_dues">Dues From Members</option>
								<option value="advanced_received">Advanced Received From Members</option>
								<option value="member_ledger">Member Ledger</option>
								<option value="general_ledger">General Ledger</option>
								<option value="income_expenditure">Income & Expenditure Statement</option>
								<option value="balance_sheet">Balance Sheet</option>
								<option value="petty_cash_register">Petty Cash Register</option>
								<option value="payment_register">Payment Register</option>
								<option value="member_monthly_contribution">Member Monthly Contribution</option>
							</select>
						</h6>						
                    </div>                    
                </div>
                <div class="panel-wrapper collapse in">
                   <div class="panel-body">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
