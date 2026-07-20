<?php
$hostInfo = parse_url(Router::url('/', true));
$config = array('InterestType' => array('Compound' => 3, 'Simple' => 2, 'No' => 1),
    'BillingFrequency' => array('Monthly' => 1, 'BiMonthly' => 2, 'Quarterly' => 3, 'Quadruple' => 4, 'HalfYearly' => 5, 'Yearly' => 6),
    'InterestMethod' => array('DelayDays' => 1, 'DelayMonths' => 2, 'CompleteCycleDays' => 3,'CompleteCycleMonthly' => 4),
    'TariffType' => array('Building' => 1, 'Wing' => 2, 'UnitNo' => 3, 'Composition' => 4, 'UnitType' => 5, 'TotalArea' => 6, 'PerUnitArea' => 7, 'PerPerson' => 8, 'Floor' => 9),
    'PaymentMode' => array('Cash' => 1,'cheque' => 3,'NEFT' => 2,'Other' => 4),
    'UnitType' => array('Commercial' => 'C', 'Residency' => 'R'),//member unii type
    'PaymentType' => array('Cash' => 'Cash', 'Bank' => 'Bank'),
    'AccessLevel'=>array('admin'=>1,'society'=>2,'reseller'=>3),
    'BillType'=>array('Supplementary'=>'sup','Regular'=>'reg'),
    'Months'=>array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December',)
);
Configure::write('Active',1);
Configure::write('InActive',0);
Configure::write('CounterName','bill_counter');
Configure::write('FileVersion',0.6);
?>