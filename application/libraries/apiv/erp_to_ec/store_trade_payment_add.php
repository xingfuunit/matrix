<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单状态更新 (erp 到ec)
 * @author Administrator
 *
 */

class Store_trade_payment_add {

	public function __construct()
	{

	}
	//erp 发起数据
// 	[shop_id] => e3f99b1f460a1f3125b8a6dbd67bef19
// 	[tid] => 150416102587286
// 	[payment_id] =>
// 	[buyer_id] => olwklj0zy0jwwbbjon1mshgerppa
// 	[seller_account] =>
// 	[seller_bank] =>
// 	[buyer_account] =>
// 	[currency] => CNY
// 	[pay_fee] => 91.140
// 	[paycost] =>
// 	[currency_fee] => 91.140
// 	[pay_type] => deposit
// 	[payment_tid] => deposit
// 	[payment_type] => 预存款
// 	[t_begin] => 2015-04-16 10:41:17
// 	[t_end] => 2015-04-16 10:41:17
// 	[memo] => 测试支付订单
// 	[status] =>
// 	[payment_operator] => admin
// 	[op_name] => admin
// 	[outer_no] =>
// 	[modify] => 2015-04-16 10:41:17
// 	[to_node_id] => 1964902530
// 	[node_type] => ecos.b2c
// 	[to_api_v] => 2.0
// 	[from_api_v] => 2.2
// 	[app_id] => ecos.ome
// 	[method] => store.trade.payment.add
// 	[date] => 2015-04-16 10:41:17
// 	[callback_url] => http://lwqerp.pinzhen365.com/index.php/openapi/rpc_callback/async_result_handler/id/1429152077557817412905-1429152077/app_id/ome
// 	[format] => json
// 	[certi_id] => 1066139131
// 	[v] => 1
// 	[from_node_id] => 1266942530
// 	[task] => 1429152077557817412905
// 	[sign] => DC9508B5DD29BA2A319D6655204194F2

//	ec 接收数据
// 	[paymethod] => 预存款
// 	[pay_account] =>
// 	[memo] => 测试支付订单
// 	[from_api_v] => 2.2
// 	[paycost] =>
// 	[app_id] => ecos.b2c
// 	[currency] => CNY
// 	[node_id] => 1266942530
// 	[date] => 2015-04-16 10:41:17
// 	[payment_tid] => deposit
// 	[bank] =>
// 	[t_end] => 1429152077
// 	[trade_no] =>
// 	[pay_type] => deposit
// 	[account] =>
// 	[task] => 1429152077557817412905
// 	[cur_money] => 91.140
// 	[t_begin] => 1429152077
// 	[order_bn] => 150416102587286
// 	[money] => 91.140
// 	[method] => b2c.payment.create
// 	[sign] => 835FEA0780D6D9790DC201E0BC99895D


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
    	
    	//订单取消必须 返回 succ
    	return json_encode(array('res'=>'', 'msg_id'=>md5(time()), 'rsp'=>'running', 'err_msg'=>'', 'data'=>array('tid'=>$return_data->data->tid)));
    }
    
    
    function callback($data){
    	$request_data = get_post(NULL);
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	
    	file_put_contents('api_juzhen.log', date("Y-m-d H:i:s",time()).' callback_excu_data:'.$return_data."\r\n",FILE_APPEND);
    	
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