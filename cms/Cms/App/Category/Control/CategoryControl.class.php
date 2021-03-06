<?php
//栏目管理模块
class CategoryControl extends AuthControl{
	//数据模型对象
	private $_db;
	//目录缓存
	private $_category;
	//构造函数
	public function __init(){
		$this->_db= K('Category');
		//栏目缓存数据
		$this->_category =F("category");
	}
		//显示栏目列表
    	public function index(){
			//向视图分配数据
    		$this->category =$this->_category;
    		$this->display();
    	}
    	//添加栏目
    	public function add(){
    		if(IS_POST){
    			if($this->_db->add_category()){
    				$this->ajax(array('state'=>1,'message'=>'栏目添加成功'));
    				// $this->success('栏目添加成功 ！');
    			}else{
    				$this->ajax(array('state'=>0,'message'=>'栏目添加失败'));
    				// $this->error($this->_db->error);
    			}
    		}else{
    			$this->display();
    		}
   	 }
   	 //修改栏目
   	 public function edit(){
		//打印用户自定义常量
		//print_const();
   	 	if(IS_POST){
   	 		if($this->_db->edit_category()){
   	 			$this->ajax(array('state'=>1,'message'=>'栏目修改成功'));
   	 		}else{
    				$this->ajax(array('state'=>0,'message'=>$this->_db->error));
    			}
   	 	}else{
   	 		//分配编辑栏目旧的数据
   	 		$field = $this->_db->find(Q("cid"));
   	 		//分配所有栏目
   	 		$category = $this->_db->all();
    			$category = Data::tree($category,'cname');
    			foreach($category as $n=>$v){
    				//将父级栏目添加selected（选中状态)
    				$v['selected']='';
    				if($field['pid']==$v['cid']){
    					$v['selected']=' selected="selected" ';
    				}
    				//将子栏目与自身添加disabled（禁止选择)
    				$v['disabled']='';
    				if($field['cid']==$v['cid'] || Data::isChild($category,$v['cid'],$field['cid'])){
    					$v['disabled']=' disabled="disabled" class="disabled" ';
    				}
    				$category[$n]=$v;
    			}
    			//分配当前栏目数据
    			$this->field= $field;
    			//分配栏目数据
    			$this->category= $category;
   	 		$this->display();
   	 	}
   	 }

   	 //删除栏目
	public function del(){
		if($this->_db->del_category()){
			$this->ajax(array('state'=>1,'message'=>'删除成功 '));
		}else{
			$this->ajax(array('state'=>0,'message'=>$this->_db->error));
		}
	}
	//更新栏目缓存
	public function update_cache(){
		if($this->_db->update_cache()){
			$this->success('更新缓存成功!','index');
		}else{
			$this->error('缓存更新失败,请检测缓存目录:'.CACHE_PATH.'写权限','index');
		}
	}
}
?>