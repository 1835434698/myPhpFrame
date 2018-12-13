<?php

/**
 * 管理员类
 *
 * @createtime		2018/03/01
 * @author          飘@&@
 * @copyright		自由开发（http://www.baidu.com）
 */
class Admin {

	public $id      = 0;           //管理员ID
	public $account = '';          //管理员账号
	public $gid     = 0;           //属组ID
    
	public function __construct($id = 0) {
		if(!empty($id)) {
			$admin = self::getInfoById($id);
			if($admin){
				$this->id      = $admin['id'];
				$this->account = $admin['account'];
				$this->gid     = $admin['group'];
			}else{
				throw new MyException('管理员不存在', 902);
			}
		}
	}
	
	/**
     * 管理员登录
     * 
     * @param $account   账号
     * @param $password  密码
     * @param $cookie    是否记录cookie
     * 
     */
    public function login($account, $password, $cookie = 0){
		if(empty($account))throw new MyException('账号不能为空', 101);
		if(empty($password))throw new MyException('密码不能为空', 102);

		//检查账号
		$Table_admin = new Table_admin();
		$admininfo = $Table_admin->getInfoByAccount($account);
        if(empty($admininfo)) {
			throw new MyException('账号或密码错误', 104);//不让用户准确知道是账号错误
		}else{
			//验证密码
			$password = self::buildPassword($password, $admininfo['salt']);
			if($password[0] == $admininfo['password']){
				//登录成功
				$this->id         = $admininfo['id'];
				$this->account    = $admininfo['account'];
				$this->gid        = $admininfo['group'];
				
				//设置cookie;
				if($cookie) $this->buildCookie();

				//设置session
				self::setSession(1, $this->id);
				
				//记录登陆信息
				$this->updateLoginInfo();
				
				//记录管理员日志log表
				$log = '成功登录!';
				Adminlog::add($log);

				return action_msg('登录成功', 1);//登陆成功
			}else{
				throw new MyException('账号或密码错误', 104);
			}
		}
	}

	//设置登陆cookie
	private function buildCookie(){
		global $cookie_ADMINID, $cookie_ADMINCODE;
		
		$cookie_time = time()+(3600*24*7);//7天

		setcookie($cookie_ADMINID, $this->id, $cookie_time);
		setcookie($cookie_ADMINCODE, self::getCookieCode($this->id, $this->account, $this->gid), $cookie_time);
	}

	//消除cookie
	static private function rebuildCookie(){
		global $cookie_ADMINID, $cookie_ADMINCODE;

		setcookie($cookie_ADMINID, '', time()-3600);
		setcookie($cookie_ADMINCODE, '', time()-3600);
	}
	
	//生成cookie校验码
	static private function getCookieCode($id = 0, $account = '', $group = 0){
		if(!ParamCheck::is_ID($id))throw new MyException('ID不合法', 101);
		if(empty($account))throw new MyException('账号不能为空', 102);
		if(!ParamCheck::is_ID($group))throw new MyException('Group不合法', 103);

		return md5(md5($account).md5($group).md5($id));//校验码算法
	}

	/**
	 * 设置登陆Session
	 * 
	 * @param $type  1--记录Session  2--清除记录
	 *
	 */
	static private function setSession($type, $id = 0){
		global $session_ADMINID;
		
		if($type == 1){
			if(!ParamCheck::is_ID($id))throw new MyException('ID不合法', 101);
			$_SESSION[$session_ADMINID]    = $id;
		}else{
			$_SESSION[$session_ADMINID]    = 0;
		}
	}
	
	//更新登陆信息
	public function updateLoginInfo(){
		$Table_admin = new Table_admin();
		return $Table_admin->updateLoginInfo($this->id);
	}
	
	/**
	 * 获得详细信息
	 * 
	 * @param $id  管理员ID
	 * 
	 */
	static public function getInfoById($id){
		if(!ParamCheck::is_ID($id))throw new MyException('ID不合法', 101);
		
		$Table_admin = new Table_admin();
		return $Table_admin->getInfoById($id);
	}
	
	//退出登录
	static public function logout(){
		$log = '退出登录!';
        Adminlog::add($log);

		self::setSession(2);
		self::rebuildCookie();

	}
	
	//检查是否登录
	static public function checkLogin(){
		global $session_ADMINID;
		global $cookie_ADMINID, $cookie_ADMINCODE;
		
		//是否存在session
		if(@$_SESSION[$session_ADMINID]){
			return true;
		}
		
		//不存在session则检查是否有cookie
		$cid   = isset($_COOKIE[$cookie_ADMINID]) ? $_COOKIE[$cookie_ADMINID] : null;
		if(empty($cid)){
			return false;
		}
		
		//检查cookie数据是否对应，防止伪造
		$vcode = $_COOKIE[$cookie_ADMINCODE];

		$Table_admin = new Table_admin();
        $admin = $Table_admin->getInfoById($cid);
		if(!$admin) {
			//cookie数据不正确，清理掉
			self::rebuildCookie();
			return false;
		}

		$code = self::getCookieCode($cid, $admin['account'], $admin['group']);
		
        if($vcode != $code){
			//cookie数据不正确，清理掉
			self::rebuildCookie();
            return false;
        }

		//cookie数据正确，重写Session
		self::setSession(1, $cid);
		return true;
	}
	
	/**
	 * 管理员列表
	 *
	 * @param $group
	 * 
	 */
	static public function getList($group = 0){
		if($group){
			$filter = array(
				'group' => $group
			);
		}else{
			$filter = array();
		}

		$Table_admin = new Table_admin();
		return $Table_admin->getList($filter);
	}

	//添加管理员
	static public function add($account, $password, $group){
		
		//检查参数
		if(empty($account))throw new MyException('账号不能为空', 101);
		if(!ParamCheck::is_ID($group))throw new MyException('管理员组ID不合法', 102);

		if(ParamCheck::is_weakPwd($password)) throw new MyException('密码太弱', 103);
        
		//获取信息//判断管理帐号是否重复
		$Table_admin = new Table_admin();
		$admin = $Table_admin->getInfoByAccount($account);
		if($admin) throw new MyException('账号已经存在', 104);

		//检查管理员组是否存在
		$Table_admingroup = new Table_admingroup();
		$admingroup = $Table_admingroup->getInfoById($group);
		if(!$admingroup) throw new MyException('管理员组不存在', 105);

        //生成管理员密码
        $password = self::buildPassword($password);
		
		$attr = array(
			'account'  => $account, 
			'password' => $password[0],
			'salt'     => $password[1],
			'group'    => $group
		);
        $rs = $Table_admin->add($attr);
		if($rs > 0){
			//记录管理员日志log表
			$msg = '成功添加管理员('.$account.')';
			Adminlog::add($msg);
            
            return action_msg($msg, 1);
		}else{
            throw new MyException('操作失败', 106);
		}

	}

	/**
	 * 生成管理员密码
	 * 
	 * @param $pwd   原始密码
	 * @param $salt  密码Salt
	 */
	static private function buildPassword($pwd, $salt = ''){
		if(empty($pwd))throw new MyException('密码不能为空', 101);
		if(empty($salt)) $salt = randcode(10, 4);//生成Salt

		$pwd_new = md5(md5($pwd).$salt);//加密算法

		return array($pwd_new, $salt);
	}

    /**
	 * 删除管理员
	 * 
	 * @param $adminId   管理员ID
	 * 
	 * @return
	 */
	static public function del($adminId){

        if(!ParamCheck::is_ID($adminId))throw new MyException('管理员ID不合法', 101);
		//不能删除当前登录的管理员2018/4/14
		if(self::getSession() == $adminId) throw new MyException('不能删除当前登陆的管理员', 103);
        
		$Table_admin = new Table_admin();
        $rs = $Table_admin->del($adminId);
        if($rs == 1){
            $msg = '删除管理员('.$adminId.')成功!';
            Adminlog::add($msg);
			
            return action_msg($msg, 1);
        }else{
			throw new MyException('操作失败', 102);
        }
	}

	/**
	 * 修改管理员信息
	 * 
	 * @param $id      管理员ID
	 * @param $account 账号
	 * @param $group   群组
	 * 
	 * @return
	 */
	static public function edit($id, $account, $group){
		if(!ParamCheck::is_ID($id))throw new MyException('管理员ID不合法', 101);
		if(empty($account))throw new MyException('管理员账号不能为空', 102);
		if(!ParamCheck::is_ID($group))throw new MyException('管理员组ID不合法', 103);

		//验证ID是否存在
		$Table_admin = new Table_admin();
		$admin = $Table_admin->getInfoById($id);
		if(empty($admin)) throw new MyException('管理员不存在', 104);

		//验证账号是否改变，如果改变则需要检查账号的重复性
		if($admin['account'] != $account){
			$admin2 = $Table_admin->getInfoByAccount($account);
			if($admin2) throw new MyException('账号已经存在', 105);
		}
		
		$attr = array(
			'account' => $account,
			'group'   => $group
		);
        $rs = $Table_admin->edit($id, $attr);
        if($rs >= 0){
            $msg = '修改管理员('.$id.')信息成功!';
            Adminlog::add($msg);

            return action_msg($msg, 1);
        }else{
            throw new MyException('操作失败', 106);
        }
	}

	/**
	 * 重置密码
	 * @param $id   管理员ID
	 * @param $newpass   新密码
	 * 
	 */
	static public function resetPwd($id, $newpass){
		if(!ParamCheck::is_ID($id))throw new MyException('管理员ID不合法', 101);
		if(empty($newpass))throw new MyException('新的密码不能为空', 102);

		if(ParamCheck::is_weakPwd($newpass)) throw new MyException('新密码太弱', 103);

		$pass = self::buildPassword($newpass);
		
		$Table_admin = new Table_admin();
		$rs = $Table_admin->updatePwd($id, $pass[0], $pass[1]);
		if($rs == 1){
			$msg = '管理员('.$id.')密码成功重置为'.$newpass.'。';
			Adminlog::add($msg);

			return action_msg($msg, 1);
        }else{
			throw new MyException('操作失败', 104);
        }
	}

	/**
	 * 修改密码
	 * 
	 * @param string  $oldpwd   旧密码
	 * @param string  $newpwd   新密码
	 * 
	 */
	public function updatePwd($oldpwd, $newpwd){
		if(empty($oldpwd))throw new myException('旧密码不能为空', 101);
		if(empty($newpwd))throw new myException('新密码不能为空', 102);
		if(ParamCheck::is_weakPwd($newpwd)) throw new myException('新密码太弱', 104);

		$admin = self::getInfoById($this->id);

		//验证密码是否正确
		$oldpass = self::buildPassword($oldpwd, $admin['salt']);
		if($oldpass[0] != $admin['password']){
			throw new myException('旧密码错误', 103);
		}

		//产生新密码
		$newpass = self::buildPassword($newpwd);

		//修改密码
		$Table_admin = new Table_admin();
		$rs = $Table_admin->updatePwd($this->id, $newpass[0], $newpass[1]);
		if($rs == 1){
			$msg = '修改密码成功';
			Adminlog::add($msg);

			return action_msg($msg, 1);
		}else{
			throw new myException('操作失败', 444);
		}
	}
	
	//获得Session
	static public function getSession(){
		global $session_ADMINID;

		return $_SESSION[$session_ADMINID];
    }

	//获得管理组ID
	public function getGroupID(){
		return $this->gid;
    }
	
	//获得账号
	public function getAccount(){
        return $this->account;
	}

	//检查是否拥有权限
	static function checkAuth($powerId, $auth){
		if(empty($powerId))throw new MyException('权限编号不能为空', 101);
		//if(empty($auth))throw new MyException('权限序列不能为空', 102);

		$powers = explode('|', $auth);
		if(in_array($powerId, $powers)) {
			return true;
		}else{
			die('无访问权限');
		}
	}
}

?>