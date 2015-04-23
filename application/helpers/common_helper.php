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
	case 'PAY_FINISH':
	  $return_status = 1;
	  break;
	case 'PAY_TO_MEDIUM':
	  $return_status = 2;
	  break;
	case 'PAY_PART':
	  $return_status = 3;
	  break;
	case 'REFUND_PART':
	  $return_status = 4;
	  break;
	case 'REFUND_ALL':
	  $return_status = 5;
	  break;
	case 'REFUNDING':
	  $return_status = 6;
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
	case  'TRADE_FINISHED':
	  $return_status = 'finish';
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
	case 'SHIP_FINISH':
	  $return_status = 1;
	  break;
	case 'SHIP_PREPARE':
	  $return_status = 0;
	  break;
	case 'SHIP_PART':
	  $return_status = 2;
	  break;
	case 'RESHIP_PART':
	  $return_status = 3;
	  break;
	case 'RESHIP_ALL':
	  $return_status = 4;
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


//stdClassת����
function object_array($array)
{
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return $array;
}

/**
 * ���php unserialize ����false�Ľ������
 */
function mb_unserialize($serial_str) {  
    $serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );  
    $serial_str= str_replace("\r", "", $serial_str);  
    return unserialize($serial_str);  
}