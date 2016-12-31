<?php
// 外贸信息管理
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
class WuliuController extends AdminbaseController{

	protected $users_model,$role_model;

	public function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/OswWuliu");
		$this->role_model = D("Common/OswWuliuRole");
	}
	

	//外贸公司信息
	public function index(){
		$where = array("user_type"=>2);
		/**搜索条件**/
		$wuliu_name = I('request.wuliu_name');
		$wuliu_contact = trim(I('request.wuliu_contact'));
		if($wuliu_name){
			$where['wuliu_name'] = array('like',"%$wuliu_name%");
		}
		if($wuliu_contact){
			$where['wuliu_contact'] = array('like',"%$wuliu_contact%");;
		}
		
		$count=$this->users_model->where($where)->count();
		$page = $this->page($count, 20);
        $wuliu = $this->users_model
            ->where($where)
            ->order("user_id DESC")
            ->limit($page->firstRow, $page->listRows)
            ->select();
       /* var_dump($wuliu);exit;
		$wuliu_src=$this->role_model->select();var_dump($wuliu_src);exit;
		$wuliu=array();
		foreach ($wuliu_src as $r){
			$roleid=$r['id'];
			$roles["$roleid"]=$r;
		}
		$this->assign("page", $page->show('Admin'));
		$this->assign("roles",$roles);*/
		$this->assign('wuliu',$wuliu);
		$this->display();

	}

		//公司添加
	public function add(){
		$id = I("request.wuliu_id",0,'intval');
	 	$wuliu = M('OswWuliu')->where(array('wuliu_id'=>$id))->find();
	 	//保存仓库ID
	 	session('wuliu_id',$wuliu['wuliu_id']);
	 	$this->assign('wuliu',$wuliu);
		$this->display();
	 }
	

	// 公司添加提交
	public function add_post(){
		if(IS_POST){
			$wuliu = session('WULIU');
			$_POST['wuliu_id'] = $wuliu['wuliu_id'];
			$create_result = M('OswWuliu')
			->field('wuliu_id,wuliu_name,wuliu_add,wuliu_contact,wuliu_principal')
			->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswWuliu')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}


	//公司编辑
	public function edit(){
		$id = I("request.wuliu_id",0,'intval');
	 	$wuliu = M('OswWuliu')->where(array('wuliu_id'=>$id))->find();
	 	session('wuliu_id',$wuliu['wuliu_id']);
	 	$this->assign('wuliu',$wuliu);
		$this->display();
	 }
	

	 //公司编辑提交
		 public function edit_post(){
	 	 $_POST['wuliu_id'] = session('wuliu_id');	
	 	 if(IS_POST){
	 		if(M('OswWuliu')->create()!==false){
	 			if(M('OswWuliu')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	  //公司删除
		public function delete(){
	 	$id = I('request.wuliu_id',0,'intval');
	 	if(M('OswWuliu')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }
	
}