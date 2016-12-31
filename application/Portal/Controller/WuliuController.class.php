<?php
// 外贸信息管理
namespace Portal\Controller;

use Common\Controller\HomebaseController;
class WuliuController extends HomebaseController{

	protected $users_model,$role_model;

	public function _initialize() {
		parent::_initialize();
		echo $this->users_model = D("Common/OswWuliu");
		$this->role_model = D("Common/OswWuliuRole");
	}
	
	public function index() {
	    $term_id=I('get.id',0,'intval');
		$term=sp_get_term($term_id);
		
		if(empty($term)){
		    header('HTTP/1.1 404 Not Found');
		    header('Status:404 Not Found');
		    if(sp_template_file_exists(MODULE_NAME."/404")){
		        $this->display(":404");
		    }
		    return;
		}
		
		$tplname=$term["list_tpl"];
    	$tplname=sp_get_apphome_tpl($tplname, "list");
    	$this->assign($term);
    	$this->assign('cat_id', $term_id);
    	$this->display(":$tplname");
	}


		// 前台货运订单列表
	public function ydgz() {
	$freight=M("OswFreight");
	$result=$freight->where()->select();
	$wuliu_code = $freight->where('wuliu_code="FC22766331188297"')->select();	
		$this->assign('wuliu_code', $result);
    	$this->display(":ydgz");
    	 print_r($wuliu_code);exit;
	}

	public function yzgz_post(){
		$wuliu_code = I('request.wuliu_code');
		$where['wuliu_code'] = array('like',"%$wuliu_code%");
		$count=$this->M("OswFreight")->where($where)->count();
		$this->assign('wuliu_id', $result);
		$this->assign('freight_id', $result);
		$this->assign('wuliu_code', $result);
    	$this->display(":yzgz_post");
	}

	
}