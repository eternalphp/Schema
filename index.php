<?php 

require __DIR__ . "/vendor/autoload.php";

use framework\Database\Schema\Control;
use framework\Database\Eloquent\Model;

$config = array(
	'driver'=>'MySqli',
	'servername'=>'127.0.0.1',
	'username'=>'root',
	'password'=>'123',
	'database'=>'dif.songdian.net.cn',
	'prefix'=>'sd_'
);


$control = new Control($config);

$control->displayMessage();

//管理员帐号
$control->create("admin",function($table){
	$table->integer("userid")->unsigned()->increments();
	$table->string("username",30)->comment("用户名");
	$table->string("password",50)->comment("用户密码");
	$table->string("name",50)->comment("姓名");
	$table->string("mobile",20)->comment("手机号");
	$table->integer("creater")->comment("创建人");
	$table->datetime("createtime")->nullable();
	$table->timestamp("updatetime");
	$table->tinyInt("isdelete");
	$table->comment("管理员帐号");
});

//管理员帐号关联项目
$control->create("admin_project",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("userid")->comment("所属用户");
	$table->integer("projectid")->comment("所属项目");
	$table->comment("管理员帐号关联项目");
});


//项目分类
$control->create("category",function($table){
	$table->integer("catid")->unsigned()->increments();
	$table->string("name",100)->comment("项目分类名称");
	$table->string("code",10)->comment("项目分类编号");
	$table->integer("userid")->alias("creater")->comment("创建人");
	$table->datetime("createtime")->comment("创建时间");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("项目分类信息表");
});

//项目管理
$control->create("project",function($table){
	$table->integer("projectid")->unsigned()->increments();
	$table->integer("catid")->comment("项目分类ID");
	$table->string("title",100)->comment("项目名称");
	$table->string("code",100)->comment("项目编号");
	$table->string("description",200)->comment("项目介绍");
	$table->integer("userid")->alias("creater")->comment("创建人");
	$table->datetime("createtime")->comment("创建时间");
	$table->datetime("starttime")->nullable()->comment("开始时间");
	$table->datetime("endtime")->nullable()->comment("完成时间");
	$table->tinyInt("status")->comment("项目状态：0:准备中，1:进行中,2: 已完成");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("项目管理信息表");
});


//问卷管理
$control->create("examine",function($table){
	$table->integer("examineid")->unsigned()->increments();
	$table->string("code",20)->comment("问卷编号");
	$table->integer("projectid")->comment("所属项目");
	$table->string("title",200)->comment("问卷标题");
	$table->string("description",200)->comment("问卷介绍");
	$table->integer("userid")->alias("creater")->comment("创建人");
	$table->datetime("createtime")->comment("创建时间");
	$table->tinyInt("status")->comment("问卷状态：0:草稿，1:已上架,2: 已下架");
	$table->timestamp("updatetime")->comment("更新时间");
	$table->tinyInt("isdelete")->comment("是否逻辑删除");
	$table->comment("问卷管理信息表");
});

//问卷问题
$control->create("question",function($table){
	$table->integer("qid")->unsigned()->increments();
	$table->string("module",20)->comment("问卷模块");
	$table->integer("number",20)->comment("问卷序号");
	$table->integer("examineid")->comment("所属问卷");
	$table->string("title",200)->comment("问题标题");
	$table->string("description",200)->comment("问题说明");
	$table->tinyInt("isRequired")->defaultVal(1)->comment("是否必填项,0:非必填项，1：必填项");
	$table->comment("问卷问题信息表");
});

//问题选项
$control->create("question_items",function($table){
	$table->integer("itemid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("title",200)->comment("选项标题");
	$table->string("filename",200)->comment("选项文件");
	$table->comment("问题选项信息表");
});

//问题选项规则
$control->create("question_rules",function($table){
	$table->integer("ruleid")->unsigned()->increments();
	$table->integer("itemid")->comment("所属问题选项");
	$table->tinyInt("dsplayMode")->comment("选项显示方式：0:按添加顺序显示,1:随机排列");
	$table->tinyInt("relationItemModule")->comment("关联选项的模块");
	$table->tinyInt("relationItemNumber")->comment("关联选项的序号");
	$table->comment("问题选项规则设置");
});

//问题选项规则内容设置
$control->create("question_rules_content",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("ruleid")->comment("所属问题选项规则");
	$table->integer("inputType")->comment("选项类型,输入框类型:question_input_type");
	$table->integer("itemIndex")->comment("选择第几个选项");
	$table->tinyInt("isRequired")->defaultVal(1)->comment("是否必填项,0:非必填项，1：必填项");
	$table->integer("inputRule")->comment("输入规则, 查看输入规则表question_input_rules");
	$table->tinyInt("isLimitUploadCount")->comment("是否限制上传文件数量");
	$table->integer("uploadCount")->comment("限制上传文件数量");
	$table->tinyInt("isLimitUploadSize")->comment("是否限制上传文件大小");
	$table->integer("uploadSize")->comment("限制上传文件大小，单位:M");
	$table->comment("问题选项规则内容设置");
});

//问题输入规则表
$control->create("question_input_rules",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("title",50)->comment("输入规则名称");
	$table->comment("问题输入规则表");
});

//输入框类型列表
$control->create("question_input_type",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("name",50)->comment("输入框名称");
	$table->comment("输入框类型列表");
});

//问题逻辑规则设置
$control->create("question_logic_rules",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("inputItemIndex")->comment("选项序号");
	$table->integer("skipModule")->comment("跳转到模块");
	$table->integer("skipIndex")->comment("跳转到序号");
	$table->comment("问题逻辑规则设置");
});


//自动生成数据表结构文档
$tables = $control->getTableList();
(new phpWord())->create($tables);

?>