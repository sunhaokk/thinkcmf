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
		$user_login = I('request.user_login');
		$user_email = trim(I('request.user_email'));
		if($user_login){
			$where['user_login'] = array('like',"%$user_login%");
		}
		
		if($user_email){
			$where['user_email'] = array('like',"%$user_email%");;
		}
		
		$count=$this->users_model->where($where)->count();

		$page = $this->page($count, 20);
		
        $trade = $this->users_model
            ->where($where)
            ->order("user_id DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
       /* var_dump($trade);exit;
		$trade_src=$this->role_model->select();var_dump($trade_src);exit;
		$trade=array();
		foreach ($trade_src as $r){
			$roleid=$r['id'];
			$roles["$roleid"]=$r;
		}
		$this->assign("page", $page->show('Admin'));
		$this->assign("roles",$roles);*/
		$this->assign('trade',$trade);

		$this->display();
		
		/*$trade = session('ADMIN_ID');
		$trade_id = $trade['trade_id'];
		//查找仓库
		$trade =M('OswTrade')->where(array('trade_id'=>$trade_id))->select();

		$this->assign('trade',$trade);
		$this->display();*/
	}






	public function tradeinfo_post(){
		if (IS_POST) {
			$trade= session('TRADE_ID');
			$_POST['trade_id'] = $trade['trade_id'];
			$create_result=$this->users_model
			->field("trade_id,trade_name,trade_add,trade_contact,trade_principal")
			->create();
			if ($create_result!==false) {
				if ($this->users_model->save()!==false) {
					$this->success("保存成功！");
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->users_model->getError());
			}
		}
	}





	public function edit(){
	    $id = I('get.id',0,'intval');
		$trade=$this->role_model->where(array('status' => 1))->order("id DESC")->select();
		$this->assign("trade",$trade);
		$trade_user_model=M("Trade");
		$trade_ids=$trade_user_model->where(array("trade_id"=>$id))->getField("trade_id",true);
		$this->assign("trade_ids",$trade_ids);
		echo $trade_ids;exit;
		$user=$this->users_model->where(array("id"=>$id))->find();
		$this->assign($user);
		$this->display();
	}
	//公司添加
	public function add(){
		$trade=$this->role_model->where(array('status' => 1))->order("id DESC")->select();
		$this->assign("trade",$trade);
		$this->display();
	}

	// 管理员添加提交
	public function add_post(){
		if(IS_POST){
			if(!empty($_POST['role_id']) && is_array($_POST['role_id'])){
				$role_ids=$_POST['role_id'];
				unset($_POST['role_id']);
				if ($this->users_model->create()!==false) {
					$result=$this->users_model->add();
					if ($result!==false) {
						$role_user_model=M("RoleUser");
						foreach ($role_ids as $role_id){
							if(sp_get_current_admin_id() != 1 && $role_id == 1){
								$this->error("为了网站的安全，非网站创建者不可创建超级管理员！");
							}
							$role_user_model->add(array("role_id"=>$role_id,"user_id"=>$result));
						}
						$this->success("添加成功！", U("user/index"));
					} else {
						$this->error("添加失败！");
					}
				} else {
					$this->error($this->users_model->getError());
				}
			}else{
				$this->error("请为此用户指定角色！");
			}

		}
	}
	
}