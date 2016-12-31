<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace Portal\Controller;
use Common\Controller\HomebaseController;

class ListController extends HomebaseController {

	// 前台文章列表
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
		$this->assign('result', $result);
    	$this->display(":ydgz");
	}


	public function ydgz_post(){
		$freight=M("OswFreight");
		$wuliu_code = I('post.wuliu_code');
		$result=$freight->where("wuliu_code='$wuliu_code'")->find();
		$this->assign('result', $result);
    	$this->display(":ydgz_post");
    	
	}

	public function sxcx() {
		$freight=M("OswFreight");
		$result=$freight->where()->select();
		$this->assign('result', $result); 
    	$this->display(":sxcx");
	}

	public function sxcx_post() {
		$freight=M("OswFreight");
		$initial_address = I('post.initial_address');
		$final_address = I('post.final_address');
		$result=$freight->where("final_address='$final_address' AND initial_address='$initial_address' ")->find();
		$wuliu_id=$result['wuliu_id'];
		$stock=M("OswStock");
		$stock=$stock->where("wuliu_id='$wuliu_id'")->find();
		$this->assign('result', $result);
		$this->assign('stock', $stock);
    	$this->display(":sxcx_post");
	}


		public function fwwd() {
			$wuliu=M("OswWuliu");
			$result=$wuliu->where()->select();
			$warehouse=M("OswWarehouse");
			$warehouse=$warehouse->where()->select();
			$this->assign('result', $result);
			$this->assign('warehouse', $warehouse);
    		$this->display(":fwwd");
	}

	
		public function fwwd_post() {
			$warehouse=M("OswWarehouse");
			$warehouse_add = I('post.warehouse_add');
			$result=$warehouse->where("warehouse_add='$warehouse_add'")->find();
			$this->assign('result',$result);
    		$this->display(":fwwd_post");
	}



		public function ssfw() {
    	$this->display(":ssfw");
	}

		public function ssfw_post() {
    	$this->display(":ssfw_post");
	}

		public function sjbz() {
    	$this->display(":sjbz");
	}
	// 文章分类列表接口,返回文章分类列表,用于后台导航编辑添加
	public function nav_index(){
		$navcatname="文章分类";
        $term_obj= M("Terms");

        $where=array();
        $where['status'] = array('eq',1);
        $terms=$term_obj->field('term_id,name,parent')->where($where)->order('term_id')->select();
		$datas=$terms;
		$navrule = array(
		    "id"=>'term_id',
            "action" => "Portal/List/index",
            "param" => array(
                "id" => "term_id"
            ),
            "label" => "name",
		    "parentid"=>'parent'
        );
		return sp_get_nav4admin($navcatname,$datas,$navrule) ;
	}

	public function select1(){
	 	$name = I('post.data');
	 	$info = M('OswWuliu')->where("wuliu_name='$name'")->field('wuliu_add')->select();
	 	//转化为JSON格式
	 	$result['error'] = 0;
	 	$result['data'] = $info;
	 	echo json_encode($result);
	 }
}
