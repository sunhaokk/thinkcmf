<?php
namespace Common\Model;
use Common\Model\CommonModel;
class OswWuliuUsersModel extends CommonModel
{
	
	protected $_validate = array(
		//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
		array('wuliu_name', 'require', '公司名称不能为空！', 1, 'regex', CommonModel:: MODEL_INSERT  ),
		array('wuliu_add', 'require', '公司地址不能为空!', 1, 'regex', CommonModel:: MODEL_INSERT ),
		array('wuliu_contact', 'require', '联系方式不能为空!', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('wuliu_principal', 'require', '负责人不能为空！', 0, 'regex', CommonModel:: MODEL_UPDATE  ),
		array('wuliu_name','','公司名称已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证user_login字段是否唯一
	    array('mobile','','手机号已经存在！',0,'unique',CommonModel:: MODEL_BOTH ), // 验证mobile字段是否唯一
	);
	

	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	protected function _before_write(&$data) {
		parent::_before_write($data);
		
		if(!empty($data['user_pass']) && strlen($data['user_pass'])<25){
			$data['user_pass']=sp_password($data['user_pass']);
		}
	}
	
}

