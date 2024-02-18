<?php 
	$Amount = 112000;
	$Interest = 0.06;
	$Days = 60;
	$Years = $Days / 365;
	$FutureAmount1 = $Amount * pow(1 + ($Interest/365),$Years * 365);
	echo '2 Months No Payments: ' . $FutureAmount1 . '<br>';
	$FutureAmount1Reduced = $FutureAmount1 - 2000;
	echo '2 Months Pay 2000: ' . $FutureAmount1Reduced . '<br>';
	
	$Days = 30;
	$Years = $Days / 365;
	
	$FutureAmount2 = $Amount * pow(1 + ($Interest/365),$Years * 365);
	echo '1 Month 0  Payment: ' . $FutureAmount2 . '<br>';
	$FutureAmount2-=1000;
	echo '1 Month 1 Payment: ' . $FutureAmount2 . '<br>';
	$FutureAmount3 = $FutureAmount2 * pow(1 + ($Interest/365),$Years * 365);
	
	echo '2 Month 1  Payment: ' . $FutureAmount3 . '<br>';
	$FutureAmount4 = $FutureAmount3 - 1000;
	echo '2 Month 2  Payment: ' . $FutureAmount4 . '<br>';
?>