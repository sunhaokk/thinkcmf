<?php
// 货柜信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class InventoryController extends WuliubaseController{
	//货柜主页
	public function index(){
		$wuliu = session('WULIU');
		$wuliu_id = $wuliu['wuliu_id'];
		//查找货柜
		$map['wuliu_id']=$wuliu_id;
		//接受查找参数
		$trade_name = I('request.trade_name');
		if($trade_name){
			$where['a.trade_name'] = array('like',"%$trade_name%");
			$where['a.warehouse_add'] = array('like',"%$trade_name%");
			$where['a.cargo_type_name'] = array('like',"%$trade_name%");
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}

		$db = M("OswStock");
		$count=$db->alias('a')->where($map)->count();
		$page = $this->page($count,2);
		$show = $page->show();

		$library = $db->alias('a')
		->join('left join cmf_osw_shop_details b on a.stock_id = b.stock_id')
		->field('a.*,a.customer_number_surplus- ifnull(sum(shopdetails_number),0) as customer_number_surplus')
		->where($map)
		->limit($page->firstRow, $page->listRows)
		->group('a.stock_id')
		->select();
		//获取wuliu_id
		//dump($stock);



        // $library =$db
        //     ->where($map)
        //     ->limit($page->firstRow, $page->listRows)
        //     ->select();
       
        $this->assign('page',$show);
		$this->assign('inventory',$library);
		$this->display();
	}

	//货柜添加
	public function inventory_add(){
		//剩余数量
		//获取到仓库信息
		$wuliu = session('WULIU');
		$row = M('OswWarehouse')->field('warehouse_add')->where(array('wuliu_id'=>$wuliu['wuliu_id']))->select();
		$trade = M('OswTrade')->field('trade_name')->select();
		$cargo = M('OswCargoType')->field('cargo_type_name')->select();
		$this->assign('cargo',$cargo);
		$this->assign('trade',$trade);
		$this->assign('row',$row);
		$this->display();
	}

	//货柜添加提交
	public function inventory_post(){
		$wuliu = session('WULIU');
		$_POST['wuliu_id'] = $wuliu['wuliu_id'];
		//判断提交方法
		if(IS_POST){
			$create_result = M('OswStock')
			->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswStock')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}

	//货柜编辑
	public function inventory_edit(){
		//获取wuliuid
		$wuliu = session('WULIU');
		$wuliu_id = $wuliu['wuliu_id'];
		$row = M('OswWarehouse')->field('warehouse_add')->where(array('wuliu_id'=>$wuliu['wuliu_id']))->select();
		$trade = M('OswTrade')->field('trade_name')->select();
		$cargo = M('OswCargoType')->field('cargo_type_name')->select();
	 	//查找货柜ID
	 	$id = I("request.stock_id",0,'intval');
	 	$inventory = M('OswStock')->where(array('stock_id'=>$id))->find();
	 	//保存货柜ID
	 	
	 	session('stock_id',$inventory['stock_id']);
	 	$this->assign('cargo',$cargo);
		$this->assign('trade',$trade);
		$this->assign('row',$row);
	 	$this->assign('inventory',$inventory);
		$this->display();
	 }

	 //货柜编辑提交
	 public function inventory_editok(){
	 	
	 	$_POST['stock_id'] = session('stock_id');	
	 	if(IS_POST){
	 		if(M('OswStock')->create()!==false){
	 			if(M('OswStock')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	 //货柜删除
	 public function inventory_delete(){
	 	$id = I('request.stock_id',0,'intval');
	 	if(M('OswStock')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

	 
}