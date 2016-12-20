<?php
namespace Trade\Controller;
use Common\Controller\TradebaseController;
class InventoryController extends TradebaseController{
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

	    public function edit() {
	    	//实例化模型
	   
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

    //添加商品
	    public function wuliuindex() {
	    	//实例化模型
	    	$Inventorymodel = D('Inventory');
	    	if(IS_POST){
	    		if($Inventorymodel->create()){
	    			//数据验证
	    			if($Inventorymodel->add()){
	    				//添加成功
	    				$this->success('正在查询，请稍后！',U('inventory'));
	    				exit;
	    			}else{
	    				//添加失败
	    				$this->error('请输入正确的物流编号');
	    			}
	    		}else{
	    			//添加失败
	    			$this->error($Inventorymodel->getError());
	    		}
	    	}
    	$this->display();
    }
}
