<?php

/* * 
 * 菜单
 */
namespace Common\Model;
use Common\Model\CommonModel;
class InventoryModel extends CommonModel {

    //自动验证
		protected $_validate = array(
        //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
        array('goods_ids', 'require', '货物编号不能为空', 1, 'regex' ),
        array('goods_name', 'require', '货物名称不能为空', 1, 'regex' ),
        array('goods_weight', 'require', '货物重量不能为空', 1, 'regex' ),
        array('goods_bulk', 'require', '货物体积不能为空', 1, 'regex'),
        array('goods_num', 'require', '货物数量不能为空', 1, 'regex'),
        array('goods_price', 'require', '货物价格不能为空', 1, 'regex'),
        array('goods_name', 'checkAction', '同样的记录已经存在！', 1, 'callback', CommonModel:: MODEL_INSERT   ),
    );
   

    //验证action是否重复添加
    public function checkAction($data) {
        //检查是否重复添加
        $find = $this->where($data)->find();
        if ($find) {
            return false;
        }
        return true;
  }
}