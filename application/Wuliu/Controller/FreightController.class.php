<?php
// 货柜信息管理
namespace Wuliu\Controller;

use Common\Controller\WuliubaseController;
class FreightController extends WuliubaseController{
	public function index(){
		//搜索条件--START--
		$code = I('request.code');
		$wuliu = session('WULIU');
		$wuliu_id = $wuliu['wuliu_id'];
		$map = array('wuliu_id'=>$wuliu_id);
		if($code){
			$where['wuliu_code'] = array('like',"%$code%");
			$where['initial_address'] = array('like',"%$code%");
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}
		//搜索条件--END--

		//分页操作--START--
		$db = M("OswFreight");
		$count=$db->where($map)->count();
		$page = $this->page($count,2);
		$show = $page->show();
        $feright =$db
            ->where($map)
            ->limit($page->firstRow, $page->listRows)
            ->select();
		//查找仓库
        $this->assign('page',$show);
		$this->assign('feright',$feright);
		$this->display();
	}

	public function freight_add(){
		//生成物流编号
		$yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
		$order = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(),2,5).sprintf('%02d',rand(0, 99));
		$containel = M('OswContainer')->field('container_id')->select();
		$this->assign('order',$order);
		$this->assign('container',$containel);
		$this->display();

	}

	public function freight_post(){
		$_POST['delivery_time'] = strtotime(I('post.delivery_time'));
		$_POST['estimated_time'] = strtotime(I('post.estimated_time'));
		$_POST['arrive_time'] = strtotime(I('post.arrive_time'));
		$wuliu = session('WULIU');
		$_POST['wuliu_id'] = $wuliu['wuliu_id'];
		//判断提交方法
		if(IS_POST){
			$create_result = M('OswFreight')->create();
			//判断是否完成创建
			if($create_result!==false){
				if(M('OswFreight')->add()){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}
			}
		}
	}

	public function freight_edit(){
	 	$id = I("request.freight_id",0,'intval');
	 	$freight = M('OswFreight')->where(array('freight_id'=>$id))->find();
	 	//货柜id
	 	$container = M('OswContainer')->field('container_id')->select();
	 	//保存出货ID
	 	session('freight_id',$freight['freight_id']);
	 	$this->assign('container',$container);
	 	$this->assign('freight',$freight);
		$this->display();
	 }


	 public function freight_editok(){
	 	//接收时间转换成时间戳
	 	$_POST['delivery_time'] = strtotime(I('post.delivery_time'));
		$_POST['estimated_time'] = strtotime(I('post.estimated_time'));
		$_POST['arrive_time'] = strtotime(I('post.arrive_time'));
		//获取出货ID
	 	$_POST['freight_id'] = session('freight_id');
	 	if(IS_POST){
	 		if(M('OswFreight')->create()!==false){
	 			if(M('OswFreight')->save()!==false){
	 				$this->success('修改成功');
	 			}else{
	 				$this->error('修改失败');
	 			}
	 		}
	 	}
	 }


	 public function freight_delete(){
	 	$id = I('request.freight_id',0,'intval');
	 	if(M('OswFreight')->delete($id)!==false){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error('删除失败');
	 	}
	 }

}