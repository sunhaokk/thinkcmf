<?php
// 货柜信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class ShopdetailsController extends WuliubaseController{
	public function index(){
		//获取跳转传递过来的物流编号
		$code = I('request.code');
		$trade_name = I('request.trade_name');
		$map = array('wuliu_code'=>$code);
		if($trade_name==''){

		}else{
			$where['trade_name'] = $trade_name;
		}
		if($trade_name){
			$where['trade_name'] = array('like',"%$trade_name%");
			$where['stock_id'] = array('like',"%$trade_name%");
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}
		$db = M("OswShopDetails");
		$count=$db->where($map)->count();
		$page = $this->page($count,5);
		$show = $page->show();
        $library =$db
            ->where($map)
            ->limit($page->firstRow, $page->listRows)
            ->select();
		//查找仓库
        $this->assign('wuliu_code',$code);
        $this->assign('page',$show);
		$this->assign('list',$library);
		$this->display();
	}

	//货柜添加
	public function shopdetails_add(){
		//获取到物流信息
		$wuliu_code = session('code');
		$trade = M('OswTrade')->field('trade_name')->select();
		$stock = M('OswStock')->field('stock_id')->select();
		$this->assign('wuliu_code',$wuliu_code);
		$this->assign('trade',$trade);
		$this->assign('stock',$stock);
		$this->display();
	}

	//添加提交
	public function shopdetails_post(){
		//判断提交方法
		if(IS_POST){
			$create_result = M('OswShopDetails')->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswShopDetails')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}

	//货柜编辑
	public function shopdetails_edit(){
		$shopdetails_id = I('request.shopdetails_id',0,'intval');
		//获取wuliucode
		$wuliu_code = session('code');
		$trade = M('OswTrade')->field('trade_name')->select();
		$stock = M('OswStock')->field('stock_id')->select();
		$number = M('OswShopDetails')->field('shopdetails_number')->where(array('shopdetails_id'=>$shopdetails_id))->find();
		//保存传值过来的id
		session('shopdetails_id',$shopdetails_id);
		$this->assign('wuliu_code',$wuliu_code);
		$this->assign('trade',$trade);
		$this->assign('stock',$stock);
		$this->assign('number',$number);
		$this->display();	 
}

	 //货柜编辑提交
	 public function shopdetails_editok(){
	 	$_POST['shopdetails_id']=session('shopdetails_id');
	 	if(IS_POST){
	 		if(M('OswShopDetails')->create()!==false){
	 			if(M('OswShopDetails')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }

	 //货柜删除
	 public function shopdetails_delete(){
	 	$id = I('request.shopdetails_id',0,'intval');
	 	if(M('OswShopDetails')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

	 public function select1(){
	 	$name = I('post.data');
	 	$info = M('OswStock')->where("trade_name = '$name'")->field('stock_id')->select();
	 	//转化为JSON格式
	 	$result['error'] = 0;
	 	$result['data'] = $info;
	 	echo json_encode($result);
	 }

}