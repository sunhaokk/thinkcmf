<?php
// 货柜信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class CargoController extends WuliubaseController{
	//货柜主页
	public function index(){
		//获取wuliu_id
		$wuliu = session('WULIU');
		$wuliu_id = $wuliu['wuliu_id'];
		//查找货柜
		$cargo =M('OswCargoType')->where(array('wuliu_id'=>$wuliu_id))->select();
		$this->assign('cargo',$cargo);
		$this->display();
	}

	//货柜添加
	public function cargo_add(){
		$this->display();
	}

	//货柜添加提交
	public function cargo_post(){
		//判断提交方法
		if(IS_POST){
			$wuliu = session('WULIU');
			$_POST['wuliu_id'] = $wuliu['wuliu_id'];
			$create_result = M('OswCargoType')
			->field('wuliu_id,cargo_type_name,cargo_type_remark')
			->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswCargoType')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}

	//货柜编辑
	public function cargo_edit(){
	 	//查找货柜ID
	 	$id = I("request.cargo_type_id",0,'intval');
	 	$cargo = M('OswCargoType')->where(array('cargo_type_id'=>$id))->find();
	 	//保存货柜ID
	 	session('cargo_id',$cargo['cargo_id']);
	 	$this->assign('cargo',$cargo);
		$this->display();
	 }

	 //货柜编辑提交
	 public function cargo_editok(){
	 	$_POST['cargo_id'] = session('cargo_id');	
	 	if(IS_POST){
	 		if(M('OswCargoType')->create()!==false){
	 			if(M('OswCargoType')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	 //货柜删除
	 public function cargo_delete(){
	 	$id = I('request.cargo_type_id',0,'intval');
	 	if(M('OswCargoType')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

}