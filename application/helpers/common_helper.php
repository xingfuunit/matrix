<?php
	
function get_post($index = '', $xss_clean = FALSE) {
	$CI =& get_instance();
	$get_data = $CI->input->get(NULL, $xss_clean);
	$post_data = $CI->input->post(NULL, $xss_clean);
	$get_data = empty($get_data) || $get_data == false ? array() : $get_data;
	$post_data = empty($post_data) || $post_data == false ? array() : $post_data;
	
	$all = array_merge($get_data,$post_data);
	
	if (empty($index)) {
		return $all;
	} else {
		$return_rs = isset($all[$index]) ? $all[$index] : '';
		return $return_rs;
	}
	 
}

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
	  //����ȡ�����ر�
	case  'TRADE_CLOSED':
	  $return_status = 'dead';
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


function get_sign($params,$token){ 
    return strtoupper(md5(strtoupper(md5(assemble($params))).$token)); 
} 

function assemble($params) 
{ 
    if(!is_array($params))  return null; 
    ksort($params,SORT_STRING); 
    $sign = ''; 
    foreach($params AS $key=>$val){ 
        $sign .= $key . (is_array($val) ? assemble($val) : $val); 
    } 
    return $sign; 
} 

/**
 * ����tark ��ֹ�ظ�ʹ��
 */
function get_tark($string){
	return md5($string.time());
}