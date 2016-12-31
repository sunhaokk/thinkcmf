<?php
// 外贸信息管理
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
class TradeController extends AdminbaseController{

	protected $users_model,$role_model;

	public function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/OswTrade");
		$this->role_model = D("Common/OswTradeRole");
	}
	

	//外贸公司信息
	public function index(){
		$where = array("user_type"=>1);
		/**搜索条件**/
		$trade_name = I('request.trade_name');
		$trade_contact = trim(I('request.trade_contact'));
		if($trade_name){
			$where['trade_name'] = array('like',"%$trade_name%");
		}
		if($trade_contact){
			$where['trade_contact'] = array('like',"%$trade_contact%");;
		}
		
		$count=$this->users_model->where($where)->count();
		$page = $this->page($count, 20);
        $trade = $this->users_model
            ->where($where)
            ->order("user_id DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
            $this->assign('trade',$trade);
			$this->display();
       /* var_dump($trade);exit;
		$trade_src=$this->role_model->select();var_dump($trade_src);exit;
		$trade=array();
		foreach ($trade_src as $r){
			$roleid=$r['id'];
			$roles["$roleid"]=$r;
		}
		$this->assign("page", $page->show('Admin'));
		$this->assign("roles",$roles);*/
	

	}

		//公司添加
	public function add(){
		$id = I("request.trade_id",0,'intval');
	 	$trade = M('OswTrade')->where(array('trade_id'=>$id))->find();
	 	//保存仓库ID
	 	session('trade_id',$trade['trade_id']);
	 	$this->assign('trade',$trade);
		$this->display();
	 }
	

	// 公司添加提交
	public function add_post(){
		if(IS_POST){
			$wuliu = session('TRADE');
			$_POST['trade_id'] = $wuliu['trade_id'];
			$create_result = M('OswTrade')
			->field('user_id,trade_id,trade_name,trade_add,trade_contact,trade_principal')
			->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswTrade')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}


	//公司编辑
	public function edit(){
		$id = I("request.trade_id",0,'intval');
	 	$trade = M('OswTrade')->where(array('trade_id'=>$id))->find();
	 	session('trade_id',$trade['trade_id']);
	 	$this->assign('trade',$trade);
		$this->display();
	 }
	

	 //公司编辑提交
		 public function edit_post(){
	 	 $_POST['trade_id'] = session('trade_id');	
	 	 if(IS_POST){
	 		if(M('OswTrade')->create()!==false){
	 			if(M('OswTrade')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	  //公司删除
		public function delete(){
	 	$id = I('request.trade_id',0,'intval');
	 	if(M('OswTrade')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }
	
}