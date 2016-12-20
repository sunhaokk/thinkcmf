<?php
// 货柜信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class ContainerController extends WuliubaseController{
	//货柜主页
	public function index(){
		//获取wuliu_id
		$wuliu = session('WULIU');
		$wuliu_id = $wuliu['wuliu_id'];
		//查找货柜
		$container =M('OswContainer')->where(array('wuliu_id'=>$wuliu_id))->select();
		$this->assign('container',$container);
		$this->display();
	}

	//货柜添加
	public function container_add(){
		$this->display();
	}

	//货柜添加提交
	public function container_post(){
		//判断提交方法
		if(IS_POST){
			$wuliu = session('WULIU');
			$_POST['wuliu_id'] = $wuliu['wuliu_id'];
			$_POST['user_id'] = $wuliu['user_id'];
			$create_result = M('OswContainer')
			->field('wuliu_id,user_id,container_bulk,container_weight')
			->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswContainer')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}

	//货柜编辑
	public function container_edit(){
	 	//查找货柜ID
	 	$id = I("request.container_id",0,'intval');
	 	$container = M('OswContainer')->where(array('container_id'=>$id))->find();
	 	//保存货柜ID
	 	session('container_id',$container['container_id']);
	 	$this->assign('container',$container);
		$this->display();
	 }

	 //货柜编辑提交
	 public function container_editok(){
	 	$_POST['container_id'] = session('container_id');	
	 	if(IS_POST){
	 		if(M('OswContainer')->create()!==false){
	 			if(M('OswContainer')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	 //货柜删除
	 public function container_delete(){
	 	$id = I('request.container_id',0,'intval');
	 	if(M('OswContainer')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

}