<?php
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class CsController extends AdminbaseController{
	//库存列表
	public function inventory(){
		$this->display();
	}

	//添加商品
	    public function add() {
	    	//实例化模型
	    	$csmodel = D('Cs');
	    	if(IS_POST){
	    		if($csmodel->create()){
	    			//数据验证
	    			if($csmodel->add()){
	    				//添加成功
	    				$this->success('添加商品类型成功',U('index'));
	    				exit;
	    			}else{
	    				//添加失败
	    				$this->error('添加商品失败');
	    			}
	    		}else{
	    			//添加失败
	    			$this->error($csmodel->getError());
	    		}
	    	}
    	$this->display();
    }
}
