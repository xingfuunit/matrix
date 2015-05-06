<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 新增支付单 (erp 到ec)
 * @author Administrator
 *
 */

class Store_trade_payment_add {
	var $right_away = FALSE;
	public function __construct()
	{

	}


    function _init() {
    	$request_data = get_post(NULL);
    	
    	$response_data['paymethod'] = $request_data['payment_type'];
    	$response_data['pay_account'] = $request_data['payment_type'];
    	$response_data['memo'] = $request_data['memo'];
    	$response_data['from_api_v'] = $request_data['from_api_v'];
    	$response_data['paycost'] = $request_data['paycost'];
    	$response_data['app_id'] = $request_data['node_type'];
    	$response_data['currency'] = $request_data['currency'];
    	$response_data['node_id'] = $request_data['from_node_id'];
    	$response_data['date'] = $request_data['date'];
    	$response_data['payment_tid'] = $request_data['payment_tid'];
    	$response_data['bank'] = $request_data['seller_bank'];
    	$response_data['t_end'] = strtotime($request_data['t_end']);
    	$response_data['trade_no'] = $request_data['trade_no'];
    	$response_data['pay_type'] = $request_data['pay_type'];
    	$response_data['account'] = $request_data['seller_account'];
    	$response_data['task'] = $request_data['task'];
    	$response_data['cur_money'] = $request_data['currency_fee'];
    	$response_data['t_begin'] = strtotime($request_data['t_begin']);
    	$response_data['order_bn'] = $request_data['tid'];
    	$response_data['money'] = $request_data['pay_fee'];
    	$response_data['method'] = 'b2c.payment.create';
//     	$response_data['sign'] = $request_data['payment_type'];


    	return array('response_data'=>$response_data,
    			'order_bn'=>$response_data['order_bn'],
    			'from_method'=>$request_data['method'],
    			'node_type'=>$request_data['node_type']);
    	
    }
    
    /**
     * 处理结果返回
     */
    function result($params){
    	$return_data = json_decode($params['return_data']);
    	$return_data = object_array($return_data);
    	
    	//订单取消必须 返回 succ
    	return json_encode(array('res'=>'', 'msg_id'=>md5(time()), 'rsp'=>'running', 'err_msg'=>'', 'data'=>''));
    }
    
    
    function callback($data){
    	$request_data = get_post(NULL);
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
    
}

?>