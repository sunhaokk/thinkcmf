<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class InventoryController extends AdminbaseController{
	//库存列表
	public function inventory(){
		//实例化模型
		$Inventorymodel = D('Inventory');
		//获取数据
		$data = $Inventorymodel->select();
		//模板赋值
		$this->assign('data',$data);
		//输出模板
		$this->display();
	}

	//添加商品
	    public function add() {
	    	//实例化模型
	    	$Inventorymodel = D('Inventory');
	    	if(IS_POST){
	    		if($Inventorymodel->create()){
	    			//数据验证
	    			if($Inventorymodel->add()){
	    				//添加成功
	    				$this->success('添加商品类型成功',U('inventory'));
	    				exit;
	    			}else{
	    				//添加失败
	    				$this->error('添加商品失败');
	    			}
	    		}else{
	    			//添加失败
	    			$this->error($Inventorymodel->getError());
	    		}
	    	}
    	$this->display();
    }
}
