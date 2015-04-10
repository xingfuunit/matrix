<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单状态更新 (erp 到ec)
 * @author Administrator
 *
 */

class Store_trade_staus_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	file_put_contents('api_juzhen.log', $request_data,FILE_APPEND);
    	$response_data = array();
    	$response_data['pay_bn'] = $request_data['payment_tid'];
    	
    	
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    
    
    
}

?>