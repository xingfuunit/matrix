<?php
/*
	���ض���֧��״̬
*/
function get_pay_status($pay_status) {
	$return_status = 0;
	switch ($pay_status)
	{
	case 'PAY_NO':
	  $return_status = 0;
	  break;
	default:
	  $return_status = "";
	}
	return $return_status;
}


/*
	���ض���״̬
*/
function get_order_status($status) {
	$return_status = '';
	switch ($status)
	{
	case 'TRADE_ACTIVE':
	  $return_status = 'active';
	  break;
	default:
	  $return_status = "";
	}
	return $return_status;
}



/*
	����״̬
*/
function get_ship_status($status) {
	$return_status = 0;
	switch ($status)
	{
	case 'SHIP_NO':
	  $return_status = 0;
	  break;
	default:
	  $return_status = "";
	}
	return $return_status;
}