<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class store_trade_shipping_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	//发送到ecstore数据
    	$response_data['logi_code'] = $request_data['logistics_code'];
		$response_data['app_id'] = 'ecos.b2c';
		$response_data['sign'] = '';//签名
		$response_data['order_bn'] = $request_data['tid'];//订单
		$response_data['method'] = 'b2c.delivery.update';
		$response_data['from_api_v'] = '2.2';
		$response_data['logi_name'] = $request_data['logistics_company'];//物流公司
		$response_data['node_id'] = $request_data['from_node_id'];
		$response_data['date'] = $request_data['date'];
		$response_data['delivery_bn'] = $request_data['shipping_id'];
		$response_data['task'] = $request_data['task'];
		$response_data['logi_no'] = $request_data['logistics_no'];
		
		//回调接口
		
		$callback_data = array();
		$callback_data['res'] = '';
		$callback_data['err_msg'] = '';
		$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'delivery_id'=>$request_data['shipping_id']));
		$callback_data['sign'] = '';
		$callback_data['rsp'] = 'succ';
		
		
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type'],'callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    
    function result($post_data) {
    	
    }
    
    
}

?>