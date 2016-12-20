<?php
// 物流信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class LibraryController extends WuliubaseController{
	//仓库主页
	public function index(){
		//获取wuliu_id
		$wuliu = session('WULIU');
		$wuliu_id = $wuliu['wuliu_id'];
		//查找仓库
		$library =M('OswWarehouse')->where(array('wuliu_id'=>$wuliu_id))->select();
		$this->assign('library',$library);
		$this->display();
	}

	//仓库添加
	public function library_add(){
		$this->display();
	}

	//仓库添加提交
	public function library_post(){
		//判断提交方法
		if(IS_POST){
			$wuliu = session('WULIU');
			$_POST['wuliu_id'] = $wuliu['wuliu_id'];
			$create_result = M('OswWarehouse')
			->field('wuliu_id,warehouse_add,warehouse_principal,warehouse_principal_contact')
			->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswWarehouse')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}

	//仓库编辑
	public function library_edit(){
	 	//查找仓库ID
	 	$id = I("request.warehouse_id",0,'intval');
	 	$library = M('OswWarehouse')->where(array('warehouse_id'=>$id))->find();
	 	//保存仓库ID
	 	session('warehouse_id',$library['warehouse_id']);
	 	$this->assign('library',$library);
		$this->display();
	 }

	 //仓库编辑提交
	 public function library_editok(){
	 	$_POST['warehouse_id'] = session('warehouse_id');	
	 	if(IS_POST){
	 		if(M('OswWarehouse')->create()!==false){
	 			if(M('OswWarehouse')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	 //仓库删除
	 public function library_delete(){
	 	$id = I('request.warehouse_id',0,'intval');
	 	if(M('OswWarehouse')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

}