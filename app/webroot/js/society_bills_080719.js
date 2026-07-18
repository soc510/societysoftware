/*
 * Society billing related functions generate member bills.
 * Author - Vedh Technology.
 */

var memberBillType = '';
function loadSocietyBillsModel(societyModelId){
    $(societyModelId).modal('show');
}

function loadSocietyBillsModelFromFlatShop(societyModelId,billId,month){
    $(societyModelId).modal('show');
    
    var postString = 'data[MemberBillSummary][id]='+billId+'&'+'data[MemberBillSummary][month]='+month;
    getAllMembersBillSummaryDetails(postString);    
}

function societyMemberBillGenerated(){
    var validate = $('#Society_Member_Generate_Bill').parsley().validate();
    if (validate) {
        var postStr = $('#Society_Member_Generate_Bill').serialize();
        showLoader();
        var postUrl = webrootUrl+'society_bills/memberBillGenerate';
        $.ajax({
            type: "POST",
            url: postUrl,
            data: postStr,
            dataType: 'json',
            success: function (resp) {
                hideLoader();
                showNotifyAlert(resp,'show_bill_notify_error');
                setTimeout(function () { $('#show_bill_notify_error').hide(); },7000);
            }
        });
    }
}

function societyMemberSupplementaryBillGenerated(){
    var validate = $('#Society_Member_Generate_Bill').parsley().validate();
    if (validate) {
        var postStr = $('#Society_Member_Generate_Bill').serialize();
        showLoader();
        var postUrl = webrootUrl+'society_bills/generateSupplementaryMemberBills';
        $.ajax({
            type: "POST",
            url: postUrl,
            data: postStr,
            dataType: 'json',
            success: function (resp) {
                hideLoader();
                showNotifyAlert(resp,'show_bill_notify_error');
                setTimeout(function () { $('#show_bill_notify_error').hide(); },7000);
            }
        });
    }
}

$(function() {
$('#supplementary_bill').on('click',function (){
    if(this.checked) {
            $('#regular_bill_generate').hide();
            $('#supplementary_bill_generate').show();
            var postStr = $('#Society_Member_Generate_Bill').serialize();
            $.ajax({
            type: "POST",
            url: webrootUrl+'society_bills/loadLedgerDetailsSupplementaryBills',
            data: postStr,
            dataType: 'html',
            success: function (resp) {
                hideLoader();
                $('#member_supplementary_bill').empty();
                $('#member_supplementary_bill').html(resp);
            }
        });
    }else{
        $('#member_supplementary_bill').empty();
        $('#regular_bill_generate').show();
        $('#supplementary_bill_generate').hide();
    }
});
});

function checkMemberBillGeneratedExistsByMonth(billType){
    memberBillType = billType;
    var validate = $('#Society_Member_Generate_Bill').parsley().validate();
    if (validate) {
        var postStr = $('#Society_Member_Generate_Bill').serialize();
        $.ajax({
            type: "POST",
            url: webrootUrl+'society_bills/checkMemberBillGeneratedExistsByMonth',
            data: postStr,
            dataType: 'json',
            success: function (resp) {
                hideLoader();
                if (resp.error === 0) {
                    $('#bill_exist_warning').modal('hide');
                    $('#Member_Generate_Bill').modal('show');
                    societyMemberBillGenerated();
                } else if (resp.error === 1) {
                    showNotifyAlert(resp,'show_bill_notify_error');
                }
            }
        });
    }
    return false;
}

function checkMemberSupplementaryBillByMonth(billType){
    memberBillType = billType;
    var validate = $('#Society_Member_Generate_Bill').parsley().validate();
    if (validate) {
        var postStr = $('#Society_Member_Generate_Bill').serialize();
        $.ajax({
            type: "POST",
            url: webrootUrl+'society_bills/checkMemberSupplementaryBillByMonth',
            data: postStr,
            dataType: 'json',
            success: function (resp) {
                hideLoader();
                if (resp.error === 0) {
                    $('#bill_exist_warning').modal('hide');
                    $('#Member_Generate_Bill').modal('show');
                    societyMemberSupplementaryBillGenerated();
                } else if (resp.error === 1) {
                    showNotifyAlert(resp, 'show_bill_notify_error');
                }
            }
        });
    }
    return false;
}

function getSocietyLedgerHeadDetails(societyLedgerheadId) {
    if (societyLedgerheadId) {
        showLoader();
        var postData = 'societyLedgerheadId=' + societyLedgerheadId;
        $.ajax({
            type: 'POST',
            url: webrootUrl+'societys_ajax/society_ledger_head_details',
            data: postData,
            dataType: 'json',
            async: false,
            success: function (resp) {
                hideLoader();
                if (resp) {
                    $('#ledger_head_previous').val(resp.SocietyLedgerHeads.previous_id);
                    $('#ledger_head_next').val(resp.SocietyLedgerHeads.next_id);
                    if (resp.SocietyLedgerHeads.previous === 0) {
                        //alert();
                       $('#ledger_head_previous_button').show();
                        //$('#ledger_head_previous_button').attr('disabled', false);
                    } else if (resp.SocietyLedgerHeads.previous === 1) {
                        //$('#ledger_head_previous_button').attr('disabled', true);
                        $('#ledger_head_previous_button').hide();
                    }
                    if (resp.SocietyLedgerHeads.next === 0) {
                        //$('#ledger_head_next_button').attr('disabled', false);
                        $('#ledger_head_next_button').show();
                    } else if (resp.SocietyLedgerHeads.next === 1) {
                        //$('#ledger_head_next_button').attr('disabled', true);
                        $('#ledger_head_next_button').hide();
                    }
                    $('#LedgerHeadsTitle').val(resp.SocietyLedgerHeads.title);
                    $('#short_code').val(resp.SocietyLedgerHeads.short_code);
                    $('#serial_no').val(resp.SocietyLedgerHeads.id);                    
                    $('#op_balance').val(resp.SocietyLedgerHeads.opening_amount);
                    $('#print_header_section_bulding_name').text(resp.SocietyLedgerHeads.title);
                   
			$.each($("#LedgerHeadsId > option"), function() {		
			if ((this).value ==  resp.SocietyLedgerHeads.id)           // this is it		
				$(this).prop('selected', true);		
			else		
				$(this).prop('selected', false)		
			}); 
                    
                    $('#society_head_sub_category').val(resp.SocietyLedgerHeads.society_head_sub_category_id);
                    $('#society_head_sub_category').empty();
                    $('#society_head_sub_category').append("<option value="+resp.SocietyHeadSubCategory.id+">"+resp.SocietyHeadSubCategory.title+"</option>");
                    
                    $('#AccountCategoryID').val(resp.SocietyLedgerHeads.account_category_id);
                    $('#AccountHeadsCategoryID').empty();
                    $('#AccountHeadsCategoryID').append("<option value="+resp.SocietyLedgerHeads.account_head_id+">"+resp.AccountHead.title+"</option>");
                    //alert(resp.SocietyLedgerHeads.is_in_bill_charges);
                    $('#ledger_heads_is_in_bill_charges').val(resp.SocietyLedgerHeads.is_in_bill_charges).prop('checked',resp.SocietyLedgerHeads.is_in_bill_charges === "1"?true:false);
                    $('#ledger_heads_is_tax_applicable').val(resp.SocietyLedgerHeads.is_tax_applicable).prop('checked',resp.SocietyLedgerHeads.is_tax_applicable === "1"?true:false);;
                    $('#ledger_heads_is_rebate_applicable').val(resp.SocietyLedgerHeads.is_rebate_applicable).prop('checked',resp.SocietyLedgerHeads.is_rebate_applicable === "1"?true:false);;
                    $('#ledger_heads_is_interest_free').val(resp.SocietyLedgerHeads.is_interest_free).prop('checked',resp.SocietyLedgerHeads.is_interest_free === "1"?true:false);;
                    $('#ledger_details').empty();
                    
                    var txnType = resp.txn_type;
                    var count = 1;
                    
                    if(resp.SocietyLedgerHeads.opening_amount == null) {
                        resp.SocietyLedgerHeads.opening_amount = 0;
                    }
                    var stepBal = 0;
                    if(txnType == 'debit') {
                        $('#ledger_details').append('<tr id=ledger_' + count + '><td class="opening-bal">01/04/2017</td><td class="opening-bal"> Opening Balance </td><td class="text-right ledger_total_debit opening-bal">' + resp.SocietyLedgerHeads.opening_amount + '</td><td class="text-right ledger_total_credit opening-bal"> 0 </td><td class="text-right total_balance opening-bal">' + resp.SocietyLedgerHeads.opening_amount + ' Dr.</td></tr>');
                        stepBal = stepBal - parseFloat(resp.SocietyLedgerHeads.opening_amount);
                    } else if(txnType == 'credit') {
                        $('#ledger_details').append('<tr id=ledger_' + count + '><td class="opening-bal">'+resp.SocietyLedgerHeads.openingdate+'</td><td class="opening-bal"> Opening Balance </td><td class="text-right ledger_total_debit opening-bal"> 0 </td><td class="text-right ledger_total_credit opening-bal">'+resp.SocietyLedgerHeads.opening_amount +'</td><td class="text-right total_balance opening-bal">' + resp.SocietyLedgerHeads.opening_amount + ' Cr.</td></tr>');
                        stepBal = stepBal + parseFloat(resp.SocietyLedgerHeads.opening_amount);
                    }
                    
                    var particularNote = 'To Payment';
                    if(resp.SocietyLedgerHeads.is_in_bill_charges == 1) {
                       particularNote = 'By Bill'; 
                    }
                    if (resp.ledgerPaymentData && resp.ledgerPaymentData.length > 0) {
                        count++;
                        if(resp.SocietyLedgerHeads.opening_amount !== 'null'){
                            var indBalance = parseInt(resp.SocietyLedgerHeads.opening_amount);
                        }else{
                            var indBalance = 0;
                        }
                        
                        $.each(resp.ledgerPaymentData, function (index, paymentData) {
                            var crAmount = 0.00;
                            var drAmount = 0.00;
                            
                            if(paymentData.txn_type == 'credit') {
                               crAmount = paymentData.amount;
                               stepBal = stepBal + parseFloat(paymentData.amount);
                               var posNeg = 'Cr';
                            } else if(paymentData.txn_type == 'debit') {
                               drAmount = paymentData.amount; 
                               stepBal = stepBal - parseFloat(paymentData.amount);
                               var posNeg = 'Dr';
                            }
                            
                            if(paymentData.particulars){
                                particularNote = paymentData.particulars;

                                if(paymentData.txn_type == 'debit') {
                                    if (typeof paymentData.payment_id !== 'undefined') {
                                        particularNote = '<a target="_blank" href="/societys/add_society_payments/'+paymentData.payment_id+'">'+paymentData.particulars+'</a>';
                                    }
                                }
                                
                            }else{
                                particularNote = 'By Bill'; 
                            }
                            
                            if(paymentData.txn_type == 'credit') {
                               indBalance = indBalance + parseInt(paymentData.amount); 
                            } else if(paymentData.txn_type == 'debit') {
                               indBalance = indBalance - parseInt(paymentData.amount);
                            }
                            
                            if (stepBal >= 0){
                                posNeg = 'Cr';
                            } else {
                                posNeg = 'Dr';
                            }
                            
                            $('#ledger_details').append('<tr id=ledger_' + count + '><td>' + paymentData.payment_date + '</td><td>'+particularNote+'</td><td class="text-right ledger_total_debit">' + drAmount + '</td><td class="text-right ledger_total_credit">' + crAmount + '</td><td class="text-right total_balance">' + Math.abs(stepBal.toFixed(2)) + ' ' + posNeg + '</td></tr>');
                            
                            count++;
                        });
                    }
                    tableSum('ledger_total_debit');
                    tableSum('ledger_total_credit');
                    var totalDebit = $('#ledger_total_debit_sum').html();
                    var totalCredit = $('#ledger_total_credit_sum').html();
                    
                    var totalBalance = ((totalDebit * 1) - (totalCredit * 1));
                    var checkNegativePositive = checkNegativePositiveValue(totalBalance);
                    totalBalance = Math.abs(totalBalance);
                    
                    $('#ledger_total_balance_sum').html(totalBalance.toFixed(2) + ' '+checkNegativePositive);
                }
            }
        });
    }
    return false;
}


function getSocietyMemberDetails(societyMemberId) {
    if (societyMemberId) {
        //showLoader();
        $('#modal_member_notify_error').hide();
        var postData = 'societyMemberId=' + societyMemberId;
        $.ajax({
            type: 'POST',
            url: webrootUrl + 'societys_ajax/getSocietyMemberDetails',
            data: postData,
            dataType: 'json',
            async: false,
            success: function (resp) {
                hideLoader();
                if (resp) {
                    $('#society_member_previous').val(resp.Member.previous_id);
                    $('#society_member_next').val(resp.Member.next_id);
                    if (resp.Member.previous === 0) {
                        $('#society_member_previous_button').show();
                    } else if (resp.Member.previous === 1) {
                        $('#society_member_previous_button').hide();
                    }
                    if (resp.Member.next === 0) {
                        $('#society_member_next_button').show();
                    } else if (resp.Member.next === 1) {
                        $('#society_member_next_button').hide();
                    }
                    $('#society_member_id').val(resp.Member.id);
                    $('#member_serial_no').val(resp.Member.id);
                    $('#member_unit_no').val(resp.Member.flat_no);
                    $('#society_member_name').val(resp.Member.member_name);
                    $('#member_building_id').val(resp.Member.building_id);
                    $('#member_floor_no').val(resp.Member.floor_no);

                    if (resp.Member.unit_type == 'C') {
                        resp.Member.unit_type = 'Commercial';
                    } else if (resp.Member.unit_type == 'R') {
                        resp.Member.unit_type = 'Residential';
                    } else if (resp.Member.unit_type == 'B') {
                        resp.Member.unit_type = 'Both';
                    }
                    $('#member_unit_type').val(resp.Member.unit_type);
                    $('#member_wing_id').empty();
                    $('#member_wing_id').append("<option value=" + resp.Member.wing_id + ">" + resp.Wing.wing_name + "</option>");
                    $('#member_building_id').val(resp.Member.building_id);
                    $('#member_flat_area').val(resp.Member.area);
                    $('#member_carpet').val(resp.Member.carpet);
                    $('#member_commercial').val(resp.Member.commercial);
                    $('#member_residential').val(resp.Member.residential);
                    $('#member_terrace').val(resp.Member.terrace);

                    $('#member_principal').val(resp.Member.op_principal);
                    $('#member_interest').val(resp.Member.op_interest);
                    $('#member_tax').val(resp.Member.op_tax);
                    $('#member_servicetax').val('');
                    $('#flat_details').empty();//empty bill table rows
                    //society print flat/shop details individual bill section 
                    $('#print_header_section_bulding_name').html(resp.Building.building_name);
                    $('#print_header_section_bulding_wing').html(resp.Wing.wing_name);
                    $('#print_header_section_member_name').html(resp.Member.member_name);
                    $('#print_header_section_unit_no').html(resp.Member.flat_no);
                    
                    
                    var op_balance = parseInt(resp.Member.op_principal) + parseInt(resp.Member.op_interest)+ parseInt(resp.Member.op_tax);
                    var checkNegativePositive = checkNegativePositiveValue(op_balance);
                    
                    var openingCr = 0;
                    var openingDr = 0;
                    
                    if(op_balance < 0) {
                        openingDr = Math.abs(op_balance);
                    } else {
                        openingCr = Math.abs(op_balance);
                    }
                    
                    var indBalance = op_balance;
                    
                    var tempOpBalance = Math.abs(op_balance);
                    //console.log(resp);
                    $('#flat_details').append('<tr id=member_' + count + '><td> '+resp.openingdate+' </td><td class="opening-bal"> Opening Balance </td><td class="text-right total_debit_flat_shop">' + openingCr.toFixed(2) + '</td><td class="text-right total_credit_flat_shop">' + openingDr.toFixed(2) +'</td><td class="text-right total_balance">' + tempOpBalance.toFixed(2) + ' ' + checkNegativePositive + '</td></tr>');
                    if (resp.memberBillPayment && resp.memberBillPayment.length > 0) {
                        var count = 0;
                        count++;
                        $.each(resp.memberBillPayment, function (index, MemberBillData) {
                            var billParticularString = MemberBillData.particular;
                            
                            //indBalance = parseInt(indBalance)+ parseInt(MemberBillData.credit)-parseInt(MemberBillData.debit);
                            indBalance = parseInt(indBalance) + parseInt(MemberBillData.debit)-parseInt(MemberBillData.credit);
                            var paymentBal = Math.abs(indBalance);
                            var checkNegativePositive = checkNegativePositiveValue(indBalance);
                            
                            if(MemberBillData.flag == 'bill') {
                               $('#flat_details').append('<tr id=member_' + count + '><td>' + MemberBillData.formattedDate + '</td><td><a href="javascript:void(0);" onclick="loadSocietyBillsModelFromFlatShop(\'#bill_modal\','+MemberBillData.bill_id+','+MemberBillData.month+');"> '+billParticularString+'</a></td><td class="text-right total_debit_flat_shop">' + MemberBillData.debit + '</td><td class="text-right total_credit_flat_shop">' + MemberBillData.credit + '</td><td class="text-right total_balance">' + paymentBal.toFixed(2) + ' ' + checkNegativePositive + '</td></tr>');
                            } else {
                               $('#flat_details').append('<tr id=member_' + count + '><td>' + MemberBillData.formattedDate + '</td><td>'+billParticularString+'</td><td class="text-right total_debit_flat_shop">' + MemberBillData.debit + '</td><td class="text-right total_credit_flat_shop">' + MemberBillData.credit + '</td><td class="text-right total_balance">' + paymentBal.toFixed(2) + ' ' + checkNegativePositive + '</td></tr>');
                            }
                            count++;
                        });
                    }
                    tableSum('total_debit_flat_shop');
                    tableSum('total_credit_flat_shop');
                    var totalDebit = $('#total_debit_flat_shop_sum').html();
                    var totalCredit = $('#total_credit_flat_shop_sum').html();
                    
                    var totalBalance = ((totalDebit * 1) - (totalCredit * 1));
                    var checkNegativePositive = checkNegativePositiveValue(totalBalance);
                    totalBalance = Math.abs(totalBalance);
                    
                    $('#total_balance_flat_shop_sum').html(totalBalance.toFixed(2) + ' '+checkNegativePositive);
                }
            }
        });
    }
    return false;
}

function getSocietyMemberDetailsBack(societyMemberId) {
    if (societyMemberId) {
        //showLoader();
        $('#modal_member_notify_error').hide();
        var postData = 'societyMemberId=' + societyMemberId;
        $.ajax({
            type: 'POST',
            url: webrootUrl + 'societys_ajax/getSocietyMemberDetails',
            data: postData,
            dataType: 'json',
            async: false,
            success: function (resp) {
                hideLoader();
                if (resp) {
                    $('#society_member_previous').val(resp.Member.previous_id);
                    $('#society_member_next').val(resp.Member.next_id);
                    if (resp.Member.previous === 0) {
                        $('#society_member_previous_button').show();
                    } else if (resp.Member.previous === 1) {
                        $('#society_member_previous_button').hide();
                    }
                    if (resp.Member.next === 0) {
                        $('#society_member_next_button').show();
                    } else if (resp.Member.next === 1) {
                        $('#society_member_next_button').hide();
                    }
                    $('#society_member_id').val(resp.Member.id);
                    $('#member_serial_no').val(resp.Member.id);
                    $('#member_unit_no').val(resp.Member.flat_no);
                    $('#society_member_name').val(resp.Member.member_name);
                    $('#member_building_id').val(resp.Member.building_id);
                    $('#member_floor_no').val(resp.Member.floor_no);

                    if (resp.Member.unit_type == 'C') {
                        resp.Member.unit_type = 'Commercial';
                    } else if (resp.Member.unit_type == 'R') {
                        resp.Member.unit_type = 'Residential';
                    } else if (resp.Member.unit_type == 'B') {
                        resp.Member.unit_type = 'Both';
                    }
                    $('#member_unit_type').val(resp.Member.unit_type);
                    $('#member_wing_id').empty();
                    $('#member_wing_id').append("<option value=" + resp.Member.wing_id + ">" + resp.Wing.wing_name + "</option>");
                    $('#member_building_id').val(resp.Member.building_id);
                    $('#member_flat_area').val(resp.Member.area);
                    $('#member_carpet').val(resp.Member.carpet);
                    $('#member_commercial').val(resp.Member.commercial);
                    $('#member_residential').val(resp.Member.residential);
                    $('#member_terrace').val(resp.Member.terrace);

                    $('#member_principal').val(resp.Member.op_principal);
                    $('#member_interest').val(resp.Member.op_interest);
                    $('#member_tax').val(resp.Member.op_tax);
                    $('#member_servicetax').val('');
                    $('#flat_details').empty();//empty bill table rows
                    //society print flat/shop details individual bill section 
                    $('#print_header_section_bulding_name').html(resp.Building.building_name);
                    $('#print_header_section_bulding_wing').html(resp.Wing.wing_name);
                    $('#print_header_section_member_name').html(resp.Member.member_name);
                    $('#print_header_section_unit_no').html(resp.Member.flat_no);
                    
                    
                    var op_balance = parseInt(resp.Member.op_principal) + parseInt(resp.Member.op_interest)+ parseInt(resp.Member.op_tax);
                    var checkNegativePositive = checkNegativePositiveValue(op_balance);
                    
                    var openingCr = 0;
                    var openingDr = 0;
                    
                    if(op_balance < 0) {
                        openingDr = Math.abs(op_balance);
                    } else {
                        openingCr = Math.abs(op_balance);
                    }
                    
                    var indBalance = op_balance;
                    
                    var tempOpBalance = Math.abs(op_balance);
                    //console.log(resp);
                    $('#flat_details').append('<tr id=member_' + count + '><td> 01/04/2017 </td><td class="opening-bal"> Opening Balance </td><td class="text-right total_debit_flat_shop">' + openingCr.toFixed(2) + '</td><td class="text-right total_credit_flat_shop">' + openingDr.toFixed(2) +'</td><td class="text-right total_balance">' + tempOpBalance.toFixed(2) + ' ' + checkNegativePositive + '</td></tr>');
                    if (resp.memberBillPayment && resp.memberBillPayment.length > 0) {
                        var count = 0;
                        count++;
                        $.each(resp.memberBillPayment, function (index, MemberBillData) {
                            //console.log(MemberBillData);
                            //alert(MemberBillData.MemberBillSummary.id);
                            //var billGeneratedDate = $.datepicker.parseDate('dd/mm/yyyy', MemberBillData.MemberBillSummary.bill_generated_date);
							var billParticularString = "Receipt";
							if(MemberBillData.MemberBillSummary.flag && MemberBillData.MemberBillSummary.flag === "jv"){
										var billParticularString = MemberBillData.MemberBillSummary.monthName;
									}else{
										var billParticularString = 'To Bill No '+ MemberBillData.MemberBillSummary.bill_no + ' For ' + MemberBillData.MemberBillSummary.monthName;
									}
                            indBalance = parseInt(indBalance)+ parseInt(MemberBillData.MemberBillSummary.monthly_bill_amount);
                            
                            var paymentBal = Math.abs(indBalance);
                            var checkNegativePositive = checkNegativePositiveValue(indBalance);
                            
                            $('#flat_details').append('<tr id=member_' + count + '><td>' + MemberBillData.MemberBillSummary.bill_generated_date + '</td><td><a href="javascript:void(0);" onclick="loadSocietyBillsModelFromFlatShop(\'#bill_modal\','+MemberBillData.MemberBillSummary.id+','+MemberBillData.MemberBillSummary.month+');"> '+billParticularString+'</a></td><td class="text-right total_debit_flat_shop">' + MemberBillData.MemberBillSummary.monthly_bill_amount + '</td><td class="text-right total_debit_flat_shop"> 0.00 </td><td class="text-right total_balance">' + paymentBal.toFixed(2) + ' ' + checkNegativePositive + '</td></tr>');
                            
                            if (MemberBillData.MemberPayment && MemberBillData.MemberPayment.length > 0) {
                                $.each(MemberBillData.MemberPayment, function (index, MemberPaymentData) {
                                    indBalance = indBalance - MemberPaymentData.amount_paid;
                                    MemberPaymentData.amount_paid = Math.abs(MemberPaymentData.amount_paid);                                    
                                    var receiptBal = Math.abs(indBalance);
                                    var checkNegativePositive = checkNegativePositiveValue(indBalance);
									var particularString = "";
									if(MemberPaymentData.flag && MemberPaymentData.flag === "jv"){
										var particularString = MemberPaymentData.cheque_reference_number;
									}else{
										var particularString = 'By Receipt V.No.'+MemberPaymentData.receipt_id;
									}
                                                                        alert(particularString);
                                    $('#flat_details').append('<tr id=member_' + count + '><td>' + MemberPaymentData.payment_date + '</td><td>'+particularString+'</td><td class="text-right total_debit_flat_shop">0.00</td><td class="text-right total_credit_flat_shop">' + MemberPaymentData.amount_paid.toFixed(2) + '</td><td class="text-right total_balance">' + receiptBal.toFixed(2) + ' ' + checkNegativePositive + '</td></tr>');
                                });
                            }
                            count++;
                        });
                    }
                    tableSum('total_debit_flat_shop');
                    tableSum('total_credit_flat_shop');
                    var totalDebit = $('#total_debit_flat_shop_sum').html();
                    var totalCredit = $('#total_credit_flat_shop_sum').html();
                    
                    var totalBalance = ((totalDebit * 1) - (totalCredit * 1));
                    var checkNegativePositive = checkNegativePositiveValue(totalBalance);
                    totalBalance = Math.abs(totalBalance);
                    
                    $('#total_balance_flat_shop_sum').html(totalBalance.toFixed(2) + ' '+checkNegativePositive);
                }
            }
        });
    }
    return false;
}

function societyLedgerHeadPrevious(){
    var societyLedgerheadId = $('#ledger_head_previous').val();
    getSocietyLedgerHeadDetails(societyLedgerheadId);
}

function societyLedgerHeadNext(){
    var societyLedgerheadId = $('#ledger_head_next').val();
    getSocietyLedgerHeadDetails(societyLedgerheadId);
}

function societyMemberNext(){
     var societyMemberId = $('#society_member_next').val();
    getSocietyMemberDetails(societyMemberId);
}

function societyMemberPrevious(){
     var societyMemberId = $('#society_member_previous').val();
     
    getSocietyMemberDetails(societyMemberId);
}

function societyMemberBillPrint(){
    var validate = $('#Society_Member_Generate_Print').parsley().validate();
    if (validate) {
        showLoader();
        var postStr = $('#Society_Member_Generate_Print').serialize();
        redirectToActionController(webrootUrl+'society_bills/print_member_bills?'+postStr);
    }
}

//print all reports function
function printAllReports(el){    
    var restorepage = document.body.innerHTML;
    var printAllReports =  document.getElementById(el).innerHTML;
    document.body.innerHTML = '<div id="content">'+'<div id="pageHeader">Page </div>'+printAllReports+'</div>';
    var styles = "@page { margin: 5mm 0 5mm 0;}#content {display: table; width:100%;}#pageHeader {display: table-header-group;}#pageHeader:after {counter-increment: page;content: counter(page);}";

    var styleSheet = document.createElement("style");
    styleSheet.type = "text/css";
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);
    window.print();
    document.body.innerHTML = restorepage;
}
function printFlatShopReports(el,el1){  
    $('#'+el1).removeClass('societyinfo-section');
    $('#'+el1).addClass('societyinfo-section-visible');
    var restorepage = document.body.innerHTML;
    var printAllReports = document.getElementById(el).innerHTML;
    var content = $('<div>' + printAllReports + '</div>');
    content.find('a').replaceWith(function() { return this.childNodes; });
    var newHtml = content.html();
    document.body.innerHTML = newHtml;
    window.print();
    document.body.innerHTML = restorepage;  
    $('#'+el1).removeClass('societyinfo-section-visible');
    $('#'+el1).addClass('societyinfo-section');
}
function printFlatShopReportsBk(el,el1){  
    $('#'+el1).removeClass('societyinfo-section');
    $('#'+el1).addClass('societyinfo-section-visible');
    var restorepage = document.body.innerHTML;
    var printAllReports = document.getElementById(el).innerHTML;
    document.body.innerHTML = printAllReports;
    window.print();
    document.body.innerHTML = restorepage;  
    $('#'+el1).removeClass('societyinfo-section-visible');
    $('#'+el1).addClass('societyinfo-section');
}
function loadSocietyMemberPaymentReciepts() {
    var result = confirm('Click OK to proceed!!!');
    if(!result) {
        return false;
    }
    showLoader();
    var postStr = $('#member_receipt_payments').serialize();
    $.ajax({
        type: "POST",
        url: webrootUrl+'societys_members/loadSocietyMemberPaymentReciepts',
        data: postStr,
        dataType: 'html',
        success: function (resp) {
            hideLoader();
            $('#society_member_payment_reciepts').empty();
            $('#society_member_payment_reciepts').html(resp);
            $('#datable_4').DataTable();
        }
    });
}

function saveSocietyMemberPaymentReciepts(){
    var validate = $('#member_receipt_payments').parsley().validate();
    if (validate) {
        showLoader();
        var postStr = $('#member_receipt_payments').serialize();
        $.ajax({
            type: "POST",
            url: webrootUrl+'societys_members/saveSocietyMemberPaymentReciepts',
            data: postStr,
            dataType: 'json',
            success: function (jsonData) {
                hideLoader();
                //$('#society_member_payment_reciepts').html(resp);
                if(jsonData.error == 0){
                    showNotificationToastr('success',jsonData.error_message);
                    redirectToActionController(webrootUrl+'societys_members/member_payments');
                }else{
                    showNotificationToastr('error',jsonData.error_message);
                }
            }
        });
    }
}


function saveSocietyPaymentReciepts(){
    var validate = $('#society_payments_entry').parsley().validate();
    if (validate) {
        //showLoader();
        var postStr = $('#society_payments_entry').serialize();
        $.ajax({
            type: "POST",
            url: webrootUrl+'societys/saveSocietyPaymentEntryReciepts',
            data: postStr,
            dataType: 'json',
            success: function (jsonData) {
                hideLoader();
                if(jsonData.error == 0){
                    showNotificationToastr('success',jsonData.error_message);
                    setTimeout(function () {
                    redirectToActionController(webrootUrl+'societys/society_payments');}, 2000);
                }else{
                    showNotificationToastr('error',jsonData.error_message);
                }
            }
        });
    }
}
function saveRemark() {
    var remarks = $('#bill_remarks').val();
    var billId = $('#bill_id').val();
    showLoader();
    var postString = 'billId=' + billId + '&remarks=' +remarks;
    $.ajax({
        type: "POST",
        url: webrootUrl + 'society_bills/update_bill_remarks',
        data: postString,
        dataType: 'text',
        success: function (responseText) {
            hideLoader();
        }
    });
}
function getAllMembersBillSummaryDetails(postStr) {
    showLoader();
    $.ajax({
        type: "POST",
        url: webrootUrl+'society_bills/getAllMembersBillSummaryDetails',
        data: postStr,
        dataType: 'json',
        success: function (resp) {
            hideLoader();
            if (resp.error === 0) {
                var totalAmt = monthlyBillAmount = totalarrears = 0;
                //bill pre-next value
                $('#society_member_summary_id').val(resp.MemberBillSummary.id);
                $('#bill_id').val(resp.MemberBillSummary.id);
                $('#society_member_bill_previous').val(resp.MemberBillSummary.previous_id);
                $('#society_member_bill_next').val(resp.MemberBillSummary.next_id);
                $('#society_member_bill_previous_month').val(resp.MemberBillSummary.previous_month);
                $('#society_member_bill_next_month').val(resp.MemberBillSummary.next_month);
                if (resp.MemberBillSummary.previous == 0) {
                    $('#society_member_bill_previous_button').hide();
                } else if (resp.MemberBillSummary.previous == 1) {
                    $('#society_member_bill_previous_button').show();
                }
                if (resp.MemberBillSummary.next == 0) {
                    $('#society_member_bill_next_button').hide();
                } else if (resp.MemberBillSummary.next == 1) {
                    $('#society_member_bill_next_button').show();
                }
                
                //hide delete if not last bill
                $('#delMemSum').attr("ifpayment",resp.ifSettlement);
                if (resp.ifDelete == 1){
                    if ($('#delMemSum').hasClass('hide')) {
                        $('#delMemSum').removeClass('hide');
                    }
                } else {
                    if (!$('#delMemSum').hasClass('hide')) {
                        $('#delMemSum').addClass('hide');
                    }
                }
                
                $('#bill_remarks').val(resp.MemberBillSummary.remarks);    
                $('#member_bill_summary_month').val(resp.MemberBillSummary.month);
                $('#member_bill_summary_bill_no').val(resp.MemberBillSummary.bill_no);
                $('#member_bill_summary_bill_generated_date').val(resp.MemberBillSummary.bill_generated_date);
                $('#member_bill_summary_bill_due_date').val(resp.MemberBillSummary.bill_due_date);
                $('#member_bill_summary_member_id').val(resp.MemberBillSummary.member_id);
                $('#member_bill_summary_area').val(resp.Member.area);
                $('#member_bill_summary_bill_flat_no').val(resp.Member.flat_no);
                $('#member_bill_summary_building_id').val(resp.Member.building_id);
                $('#member_bill_summary_bill_wing_id').val(resp.Member.wing_id);
                
                //member tariff details
                $('#bill_member_tariff_details').empty();
                if (resp.MemberTariff && resp.MemberTariff.length > 0) {
                    $.each(resp.MemberTariff, function (index, itemData) {
                        if (itemData.amount) {
                            totalAmt = totalAmt + parseInt(itemData.amount);
                            $('#bill_member_tariff_details').append('<tr><td>'+itemData.tariff_serial+'</td><td>'+itemData.title+'</td><td><input type=\"text\" class=\"form-control text-right\" name=\"data[MemberTariff]['+itemData.ledger_head_id+'][amount]\" id=\"member_bill_summary_bill_discount_'+itemData.tariff_serial+'\"  value=\"'+itemData.amount+'\"></td></tr>');
                        }
                    });
                    $('#bill_member_tariff_details').append("<span style=\"color:#0f4fa8;font-size:13px;cursor: pointer;\" onclick=\"addTarrifMoreRows();\">Add More Tarrif Details</span></th>");
                    $('#bill_member_tariff_details').append('<tr style="font-weight:bold;"><td></td><td>Total</td><td class=\"form-control text-right\">' + totalAmt + '</td></tr>');
                }
                
                //memberBill Summary
                $('#member_bill_summary_bill_monthly_principal_amount').val(resp.MemberBillSummary.monthly_principal_amount);
                $('#member_bill_summary_bill_interest_on_due_amount').val(resp.MemberBillSummary.interest_on_due_amount);

                $('#member_bill_summary_monthly_principal_amount').val(resp.MemberBillSummary.monthly_amount - resp.MemberBillSummary.tax_total);
                $('#member_bill_summary_interest_on_due_amount').val(resp.MemberBillSummary.interest_on_due_amount);
                
                $('#member_bill_summary_bill_discount').val(resp.MemberBillSummary.discount);
                $('#member_bill_summary_bill_principal_adjusted').val(resp.MemberBillSummary.principal_adjusted);
                $('#member_bill_summary_bill_interest_adjusted').val(resp.MemberBillSummary.interest_adjusted);
                monthlyBillAmount = resp.MemberBillSummary.monthly_bill_amount * 1 + resp.MemberBillSummary.tax_total * 1;
                totalarrears = resp.MemberBillSummary.op_principal_arrears * 1 + resp.MemberBillSummary.op_tax_arrears * 1 + resp.MemberBillSummary.op_interest_arrears * 1;
                
                $('#member_bill_summary_bill_principal_arrears').val(resp.MemberBillSummary.op_principal_arrears*1);
                $('#member_bill_summary_bill_monthly_bill_amount').val(resp.MemberBillSummary.monthly_bill_amount*1);
                $('#member_bill_summary_bill_balance_amount').val(resp.MemberBillSummary.balance_amount);
                $('#member_bill_summary_bill_interest_balance').val(resp.MemberBillSummary.op_interest_arrears);
                $('#member_bill_summary_bill_op_total_errears').val(totalarrears);
                
                if(resp.MemberBillSummary.amount_payable < 0) {
                    $('#amtPayableExcess').html('Excess Received');
                    $('#member_bill_summary_bill_amount_payable').val(resp.MemberBillSummary.amount_payable);
                } else {
                    $('#amtPayableExcess').html('Amount Payable');
                    $('#member_bill_summary_bill_amount_payable').val(resp.MemberBillSummary.amount_payable);
                }
                
                $('#member_bill_summary_bill_principal_adjusted').val(resp.MemberBillSummary.principal_adjusted);
                $('#member_bill_summary_bill_interest_adjusted').val(resp.MemberBillSummary.interest_adjusted);
                //reciept table data
                $('#bill_principle_paid').html(resp.MemberBillSummary.principal_paid);
                $('#bill_principal_adjusted').html(resp.MemberBillSummary.principal_adjusted);
                $('#bill_principal_bal').html(resp.MemberBillSummary.principal_balance);
                
                $('#bill_interest_paid').html(resp.MemberBillSummary.interest_paid);
                $('#bill_interest_adjusted').html(resp.MemberBillSummary.interest_adjusted);
                $('#bill_interest_balance').html(resp.MemberBillSummary.interest_balance);
                
                $('#bill_tax_paid').html(resp.MemberBillSummary.tax_paid);
                $('#bill_tax_adjusted').html(resp.MemberBillSummary.tax_adjusted);
                $('#bill_tax_balance').html(resp.MemberBillSummary.tax_balance);
                
                
                $('#bill_balance_amount').html(resp.MemberBillSummary.balance_amount);
                
                $('#bill_monthly_bill_amount').html(resp.MemberBillSummary.monthly_bill_amount);
                $('#bill_op_interest_arrears').html(resp.MemberBillSummary.op_interest_arrears);
                $('#bill_interest_adjusted').html(resp.MemberBillSummary.interest_adjusted);
                $('#bill_interest_balance').html(resp.MemberBillSummary.interest_balance);
                $('#bill_op_tax_arrears_balance').html(resp.MemberBillSummary.op_tax_arrears);
                $('#bill_balance_amount').html(resp.MemberBillSummary.balance_amount);
                
                //Service tax details
                $('#member_bill_op_tax_arrears').val(resp.MemberBillSummary.op_tax_arrears);
                $('#bill_igst_total').html(resp.MemberBillSummary.igst_total);
                $('#bill_cgst_total').html(resp.MemberBillSummary.cgst_total);
                $('#bill_sgst_total').html(resp.MemberBillSummary.sgst_total);
                $('#bill_tax_total').html(resp.MemberBillSummary.tax_total);
            } else {
                $("#society_member_bill_summary")[0].reset();
                $('#bill_member_tariff_details').empty();
                showNotificationToastr('warning','Bill records not found.');
            }
        }
    });
}

function tableSum(name) {
   var sum = 0;
   
   $('.'+name).each(function(index, element) {
       var value = $(element).html();
       sum += 1 * value;
   });
   
   if(name == 'total_credit_flat_shop') {
      sum = Math.abs(sum);
      $('#' + name + '_sum').html(sum.toFixed(2)); 
   } else {
      $('#' + name + '_sum').html(sum.toFixed(2));
   }
}

function societyMemberBillPrevious(){
    var memberBillSummaryId = $('#society_member_bill_previous').val();
    var memberBillSummaryMonth = $('#society_member_bill_previous_month').val();
    if(memberBillSummaryId != ''){
        var postString = 'data[MemberBillSummary][id]='+memberBillSummaryId+'&data[MemberBillSummary][month]='+memberBillSummaryMonth;
        getAllMembersBillSummaryDetails(postString);
    }else{
        showNotificationToastr('warning','Select month');
    }
}

function societyMemberBillNext(){
    var memberBillSummaryId = $('#society_member_bill_next').val();
    var memberBillSummaryMonth = $('#society_member_bill_next_month').val();
    if(memberBillSummaryId != ''){
        var postString = 'data[MemberBillSummary][id]='+memberBillSummaryId+'&'+'data[MemberBillSummary][month]='+memberBillSummaryMonth;
        getAllMembersBillSummaryDetails(postString);
    }else{
        showNotificationToastr('warning','Select month');
    }
}

function updateMemberBillSummaryById(){
    var postMemberTarrifStr = $('#society_member_bill_summary').serialize();
    var memberBillSummaryId = $('#society_member_summary_id').val();
    var billDiscount = $('#member_bill_summary_bill_discount').val();
    var principalAdjusted = $('#member_bill_summary_bill_principal_adjusted').val();
    var interestAdjusted = $('#member_bill_summary_bill_interest_adjusted').val();
    var member_id = $('#member_bill_summary_member_id').val();
    var interestOnDueAmount = $('#member_bill_summary_interest_on_due_amount').val();
    var postString = 'data[MemberBillSummary][id]=' + memberBillSummaryId + '&' + 'data[MemberBillSummary][discount]=' + billDiscount + '&' + 'data[MemberBillSummary][principal_adjusted]=' + principalAdjusted + '&' + 'data[MemberBillSummary][interest_adjusted]=' + interestAdjusted + '&' + 'data[MemberBillSummary][interest_on_due_amount]=' + interestOnDueAmount + '&' + 'data[MemberBillSummary][member_id]=' + member_id + '&' + postMemberTarrifStr;
    showLoader();
    $.ajax({
        type: "POST",
        url: webrootUrl + 'society_bills/updateMemberBillSummaryById',
        data: postString,
        dataType: 'json',
        success: function(jsonData) {
            hideLoader();
            if (jsonData.error === 0) {
                showNotificationToastr('success', jsonData.error_message);
                var postString = 'data[MemberBillSummary][id]=' + jsonData.id + '&' + 'data[MemberBillSummary][month]=' + jsonData.month;
                getAllMembersBillSummaryDetails(postString);
                //setTimeout(function () { redirectToActionController(webrootUrl+'societys/society_payments');}, 2000);
            } else {
                showNotificationToastr('error', jsonData.error_message);
            }
        }
    });
}

function deleteMemberBillSummaryById() {    
    var ifPayment = $('#delMemSum').attr('ifpayment');
    var permit = 0;
    if (ifPayment == 1){
        var cf = window.confirm('This bill has payment settlements. Receipt will be deleted along with bill. Do you really want to delete this Bill?');
        if (cf){
            permit = 1;
        }
    } else {
        permit = 1;
    }   
    if (permit == 1){
        var memberBillSummaryId = $('#society_member_summary_id').val();
        var postString = 'data[MemberBillSummary][id]=' + memberBillSummaryId;
        showLoader();
        $.ajax({
            type: "POST",
            url: webrootUrl + 'society_bills/deleteMemberBillSummaryById',
            data: postString,
            dataType: 'json',
            success: function(jsonData) {
                hideLoader();
                if (jsonData.error === 0) {
                    showNotificationToastr('success', jsonData.error_message);
                    //location.reload(true);
                } else {
                    showNotificationToastr('error', jsonData.error_message);
                }
            }
        });
    } else {
        return false;
    }
}

function updateMemberBills(memberId,memberBillSummaryId) {
    //alert(memberId+'->'+op_principal+'->'+op_interest);
    var postString = 'memberId=' + memberId + '&memberBillSummaryId=' +memberBillSummaryId;
        $.ajax({
            type: "POST",
            url: webrootUrl + 'society_bills/update_member_bills',
            data: postString,
            dataType: 'text',
            success: function (responseText) {
                //hideLoader();
                //location.reload(true);
            }
        });
}

var rowMemberBillTarrifCount = 1;
function addTarrifMoreRows(){
    rowMemberBillTarrifCount ++;
    $.ajax({
        type: "POST",
        url: webrootUrl+'societys_ajax/getSocietyLedgerHeadsList',
        dataType: 'json',
        success: function (jsonData) {
            var AppendData = "";
            var option = '<option>Select ledger head</option>';
            $.each(jsonData,function(index,itemData) {
                if (itemData.SocietyLedgerHeads.title) {
                   option += "<option value="+itemData.SocietyLedgerHeads.id+">"+itemData.SocietyLedgerHeads.title+"</option>";
                }
            });
            AppendData = "<tr id='rowCount"+rowMemberBillTarrifCount+"'><td></td><td><select onchange=\"changeMemberTarrifNameFeilds(this.value);\">"+option+"</select></td><td><input type=\"text\" class=\"form-control text-right\" name=\"\" id=\"member_bill_summary_"+rowMemberBillTarrifCount+"\"  value=\"0.00\"></td>";
           $('#bill_member_tariff_details tr:last').before(AppendData);
        }
    });
    //$('#bill_member_tariff_details').append('<tr><td>'+itemData.tariff_serial+'</td><td>'+itemData.title+'</td><td><input type=\"text\" class=\"form-control text-right\" name=\"data[MemberTariff]['+itemData.ledger_head_id+'][amount]\" id=\"member_bill_summary_bill_discount\"  value=\"'+itemData.amount+'\"></td></tr>');
}

function changeMemberTarrifNameFeilds(ledgerHeadID){
    $('#member_bill_summary_'+rowMemberBillTarrifCount).attr('name',"data[MemberTariff]["+ledgerHeadID+"][amount]"); 
}

function getBillDateBillDueDate(financialMonth){
    var financialBillYear = $('#financialBillYear').val();
    var postString = 'data[financialMonth]='+financialMonth+'&'+'data[financialBillYear]='+financialBillYear;
    $.ajax({
        type: "POST",
        url: webrootUrl+'societys_ajax/getBillDateBilldueDateFromFinancialYearDate',
        dataType: 'json',
        data: postString,
        success: function (jsonData){
            if(jsonData){
                $('#financial_bill_date').val(jsonData.firstDate);
                $('#financial_bill_due_date').val(jsonData.lastDate);
            }
        }
    });
}

//To Do For Search

function getAllMemberTariffData(){
     var validate = $('#allMembersTariffForm').parsley().validate();
    if (validate) {
        showLoader();
        var postString = 'building_id=' + $('#building_id').val() + '&' + 'wing_id=' + $('#wing_id').val();
     $.ajax({
        type: "POST",
            url: webrootUrl + 'societys_ajax/getAllMemberTariffDetails',
            dataType: 'html',
        data: postString,
            success: function (tarrifData) {
                hideLoader();
                    $('#all_member_tariff_data').html(tarrifData);
            }
        });
    }
    return false;
}
     
function societyMemberBillEmail(){
    //var validate = $('#Society_Member_Generate_EMail').parsley().validate();
    //if (validate) 
    {
        showLoader();
        var postStr = $('#Society_Member_Generate_Email').serialize();
        redirectToActionController(webrootUrl+'society_bills/email_member_bills?'+postStr);
    }
}
       
function updateAllMemberTariffData(){
     var validate = $('#allMembersTariffForm').parsley().validate();
    if (validate) {
        showLoader();
        var postStr = $('#allMembersTariffForm').serialize();
        $.ajax({
            type: "POST",
            url: webrootUrl+'societys_ajax/updateAllMemberTariffDetails',
            dataType: 'json',
            data: postStr,
            success: function (tarrifData) {
                hideLoader();
                if (tarrifData.error === 0) {
                    showNotificationToastr('success',tarrifData.error_message);
                }else{
                    showNotificationToastr('error',tarrifData.error_message);
            }
        }
    });    
}
    return false;
}

function saveSocietyGeneralReciepts(){
    var validate = $('#society_general_receipt').parsley().validate();
    if (validate) {
        showLoader();
        $("#society_general_receipt").submit();
    }
}


$(document).ready(function(){

$('input:checkbox[id^="is_tax_applicable"]').click(function()
    { var res = $(this).attr('id').split('_');if ($(this).is(':checked')) $('#tax_'+res[3]).show(); else $('#tax_'+res[3]).hide(); });

});
