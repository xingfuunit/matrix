<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 订单状态更新 (erp 到ec)
 * @author Administrator
 *
 */

class Store_trade_status_update {

	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
	
// 	接收erp的数据
// post_data:Array
// (
//     [tid] => 150413102745968
//     [status] => TRADE_CLOSED
//     [type] => status
//     [modify] => 2015-04-13 10:28:49
//     [is_update_trade_status] => true
//     [reason] => 订单被取消 qqqq
//     [to_node_id] => 1964902530
//     [node_type] => ecos.b2c
//     [to_api_v] => 2.0
//     [from_api_v] => 2.2
//     [app_id] => ecos.ome
//     [method] => store.trade.status.update
//     [date] => 2015-04-13 10:28:49
//     [format] => json
//     [certi_id] => 1066139131
//     [v] => 1
//     [from_node_id] => 1266942530
//     [sign] => FC86280D44E258B6F8A3F084A5F479F2
// )
	
// 	发送到ecstore数据
// 1接收:Array
// (
//     [status] => dead
//     [task] => 552B2A49C0A81729CDF91D9A56F39D24
//     [from_api_v] => 2.2
//     [consignee] => {}
//     [app_id] => ecos.b2c
//     [sign] => 9675464A69C039AF155D1DEBE426FB19
//     [order_bn] => 150413102745968
//     [date] => 2015-04-13 10:28:49
//     [method] => b2c.order.status_update
//     [node_id] => 1266942530
// )
    
    function _init() {
    	$request_data = get_post(NULL);
    	file_put_contents('api_juzhen.log', print_r($request_data,1),FILE_APPEND);
    	
    	
    	if($request_data['type'] == 'status'){
    		switch ($request_data['status'])
    		{
    			//取消订单
    			case 'TRADE_CLOSED':
    				$response_data['status'] = 'dead';
    				$response_data['task'] = '';
    				$response_data['from_api_v'] = $request_data['from_api_v'];
    				$response_data['consignee'] = json_encode(array());
    				$response_data['app_id'] = $request_data['node_type'];
    				$response_data['order_bn'] = $request_data['tid'];
    				$response_data['date'] = $request_data['date'];
    				$response_data['method'] = 'b2c.order.status_update';
    				$response_data['node_id'] = $request_data['from_node_id'];
    				
    				break;
    				
    			case '':
    				break;
    				
    			default:
    				
    		}
    	}
    	
    	return array('response_data'=>$response_data,
    			'order_bn'=>$response_data['order_bn'],
    			'from_method'=>$request_data['method'],
    			'node_type'=>$request_data['node_type']);
    	
    }
    
    /**
     * 处理结果返回
     */
    function result($params){
    	if($params['return_data']){
//     		response:{"res": "", "msg_id": "552B2A49C0A81729CDF91D9A56F39D24", "err_msg": "", "data": "{\"tid\": \"150413102745968\"}", "rsp": "succ"}
    		$re = array(
    				'res' => '',
    				'msg_id' 	=> md5(time()),
    				'err_msg'	=> '',
    				'data'		=> json_encode(array('tid'=>$data['order_bn'])),
    				'rsp'		=> 'success',
    				);
    		return json_encode($re);
    	}else{
    		echo json_encode(array('merr_msg'=>'error'));
    	}
    }
    
    
}

?>