<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @author EC端请求售后服务接口
 *
 */
class Store_trade_aftersale_add {
	var $right_away = FALSE;
	public function __construct()
	{
	//	 $data =  $this->_init();
	//	 return $data;
	}
    
    function _init() {
    //	$request_data = get_post(NULL);
    	$request_data = $data['request_data'];
    	$response_data = array();
    	$response_data['comment'] = $request_data['messager'];//未确定字段
    	$response_data['status'] = $request_data['status'];
    	$response_data['task'] = $request_data['task'];
    	$response_data['title'] = $request_data['title'];  
    	
    	$aftersale_items = json_decode($request_data['aftersale_items']);
    	$items_objects = array();
    	if($aftersale_items){
    		foreach ($aftersale_items as $k=>$v) {
    			$std = new stdClass;
    			$std->bn = $v->sku_bn;
    			$std->num = $v->number;
    			$std->name = $v->sku_name;
    			$items_objects[] = $std;
    		}
    	}
    	$response_data['return_product_items'] = json_encode($items_objects);
    	
    	$response_data['node_version'] = '';
    	$response_data['app_id'] = 'ecos.ome';
    	$response_data['content'] = $request_data['content'];
    	$response_data['order_bn'] = $request_data['tid'];
    	$response_data['attachment'] = $request_data['attachment'];
    	$response_data['member_id'] = $request_data['buyer_id'];
    	$response_data['date'] = $request_data['date'];
    	$response_data['sign'] = $request_data['sign'];
    	$response_data['return_bn'] = $request_data['aftersale_id'];
    	$response_data['method'] = 'ome.aftersale.add';
    	$response_data['node_id'] = $request_data['from_node_id'];
    	$response_data['add_time'] = strtotime($request_data['created']);
    	    	
    	return array('response_data'=>$response_data,'order_bn'=>$response_data['order_bn'],'from_method'=>$request_data['method'],'node_type'=>$request_data['node_type']);
    //	$CI->load->library('common/httpclient');
    	
    }
    
    function callback($data) {
    	//$request_data = get_post(NULL);
    	$request_data = $data['request_data'];
    	$return_data = json_decode($data['return_data']);
    	$return_data = object_array($return_data);
    	if ($return_data['rsp'] == 'succ') {
    		//回调接口
    		$callback_data = array();
    		$callback_data['res'] = '';
    		$callback_data['msg_id'] = $data['msg_id'];
    		$callback_data['err_msg'] = '';
    		$callback_data['data'] = json_encode(array('tid'=>$request_data['tid'],'aftersale_id'=>$request_data['aftersale_id']));
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
    		return json_encode(array('res'=>$return_data['res'], 'msg_id'=>$params['msg_id'], 'rsp'=>'running', 'err_msg'=>'', 'data'=>''));
    	}
    }
    
    
}

?>