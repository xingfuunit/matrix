<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 添加退退款单
 * @author Administrator
 *
 */
class Store_trade_refund_add {
	var $right_away = FALSE;
	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    	$request_data = get_post(NULL);
    	$response_data = array();
    	//发送到ecstore数据
    	$response_data['node_id'] = $request_data['from_node_id'];
		$response_data['pay_account'] = $request_data['seller_account'];
		$response_data['memo'] = $request_data['memo'];
		$response_data['from_api_v'] = $request_data['from_api_v'];
		$response_data['app_id'] = $request_data['node_type'];
		//$response_data['sign'] = time();//签名
		$response_data['pay_name'] = $request_data['payment_type'];
		$response_data['currency'] = $request_data['currency'];
		$response_data['t_payed'] = '';//不确定
		$response_data['date'] = $request_data['date'];
		$response_data['payment_tid'] = $request_data['payment_tid'];
		$response_data['bank'] = $request_data['buyer_bank'];
		$response_data['trade_no'] = $request_data['outer_no'];
		$response_data['pay_type'] = $request_data['pay_type'];
		$response_data['account'] = $request_data['buyer_account'];
		$response_data['task'] = $request_data['task'];
		$response_data['cur_money'] = $request_data['currency_fee'];
		$response_data['refund_bn'] = $request_data['refund_id'];
		$response_data['t_confirm'] = strtotime($request_data['t_received']);
		$response_data['t_begin'] = strtotime($request_data['t_begin']);
		$response_data['order_bn'] = $request_data['tid'];
		$response_data['money'] = $request_data['refund_fee'];
		$response_data['method'] = 'b2c.refund.create';
		$response_data['buyer_id'] = $request_data['buyer_id'];
	
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    function callback($data) {		
    //	$request_data = get_post(NULL);
    	$request_data = $data['request_data'];
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);   	
    	if ($return_data['rsp'] == 'succ') {
			//回调接口
			$callback_data = array();
			$callback_data['res'] = '';
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode($return_data['data']);
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'succ';
		} else {
			$callback_data = array();
			$callback_data['res'] = $return_data['res'];
			$callback_data['msg_id'] = $data['msg_id'];
			$callback_data['err_msg'] = '';
			$callback_data['data'] = json_encode($return_data['data']);
			$callback_data['sign'] = '';
			$callback_data['rsp'] = 'fail';
		}
		return array('callback_data'=>$callback_data,'callback_url'=>$request_data['callback_url']);				
    }
    
    function result($params) {
    	$return_data = json_decode($params['return_data']);
    	$return_data = object_array($return_data);
    	$response_data = $params['response_data'];
    	if($return_data['rsp'] !=  'succ'){
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'fail', 'err_msg'=>'', 'data'=>''));
    	}else{
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'succ', 'err_msg'=>'', 'data'=>''));
    	}
    }
    
    
}

?>