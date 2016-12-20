<?php
// 物流信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class WuliuController extends WuliubaseController{

	protected $users_model,$role_model;

	public function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/OswWuliu");
		$this->role_model = D("Common/OswWuliuRole");
	}
	

	//物流公司信息修改
	public function wuliuinfo(){
		$user_id = session('USER_ID');
		$wuliu=$this->users_model->where(array("user_id"=>$user_id))->find();
		$this->assign($wuliu);
		//显示模板页面
		$this->display();
	}

	public function wuliuinfo_post(){
		if (IS_POST) {
			$wuliu= session('WULIU');
			$_POST['wuliu_id'] = $wuliu['wuliu_id'];
			$create_result=$this->users_model
			->field("wuliu_id,wuliu_name,wuliu_add,wuliu_contact,wuliu_principal")
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
}