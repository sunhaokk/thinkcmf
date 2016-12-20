<?php
// 外贸信息管理
namespace Trade\Controller;

use Common\Controller\TradebaseController;
class TradeController extends TradebaseController{

	protected $users_model,$role_model;

	public function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/OswTrade");
		$this->role_model = D("Common/OswTradeRole");
	}
	

	//外贸公司信息修改
	public function tradeinfo(){
		$user_id = session('USER_ID');
		$trade=$this->users_model->where(array("user_id"=>$user_id))->find();
		$this->assign($trade);
		/*echo $trade[trade_name];exit;*/
		//显示模板页面
		$this->display();
	}

	public function tradeinfo_post(){
		if (IS_POST) {
			$trade= session('TRADE');
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
}