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
	$table->integer("questionType")->comment("所属问题类型");
	$table->string("module",20)->comment("问卷模块");
	$table->integer("number",20)->comment("问卷序号");
	$table->integer("examineid")->comment("所属问卷");
	$table->string("title",200)->comment("问题标题");
	$table->string("description",200)->comment("问题说明");
	$table->tinyInt("isRequired")->defaultVal(1)->comment("是否必填项,0:非必填项，1：必填项");
	
	$table->tinyInt("itemType")->comment("选项类型，1:常规填空，2：横向填空");
	
	//城市选择题
	$table->tinyInt("isProvince")->comment("是否选择省");
	$table->tinyInt("isCity")->comment("是否选择市");
	$table->tinyInt("isCounty")->comment("是否选择县");
	
	//日期选择题
	$table->tinyInt("isYear")->comment("是否选择年");
	$table->tinyInt("isMonth")->comment("是否选择月");
	$table->tinyInt("isDay")->comment("是否选择日");
	
	//文件题
	$table->tinyInt("isPicture")->comment("限定图片：jpg,png,gif");
	$table->tinyInt("isFile")->comment("限定文件：docx,doc,xlsx,xls,txt");
	$table->tinyInt("isAudio")->comment("限定音频：mp3,mp4");
	$table->tinyInt("isVideo")->comment("限定视频：mp4");
	$table->tinyInt("limitUploadCount")->comment("限定数量");
	$table->tinyInt("uploadSize")->comment("限定大小：单位MB");
	
	//线性标度题
	$table->tinyInt("startValue")->comment("起始值");
	$table->tinyInt("startDesc")->comment("起始值说明");
	$table->tinyInt("endValue")->comment("终止值");
	$table->tinyInt("endDesc")->comment("终止值说明");
	
	$table->comment("问卷问题信息表");
});

//问题选项
$control->create("question_items",function($table){
	$table->integer("itemid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->string("title",200)->comment("选项说明");
	$table->string("filename",200)->comment("选项文件");
	
	//打分题
	$table->integer("scoreType")->comment("打分类型，1：数值打分，2: 5星打分，3: 半星计分");
	$table->integer("maxValue")->comment("最大值");
	$table->string("maxDesc",100)->comment("最大值描述");
	$table->integer("minValue")->comment("最小值");
	$table->string("minDesc",100)->comment("最小值描述");
	$table->string("otherDesc",200)->comment("特殊项描述, 如果选择特殊项，分数清0");
	
	//线性标度题选项
	$table->integer("referenceValue")->comment("参照值");
	$table->string("referenceDesc",100)->comment("参照值描述");
	
	$table->comment("问题选项信息表");
});


//问题答案
$control->create("question_answer",function($table){
	$table->integer("askid")->unsigned()->increments();
	$table->integer("qid")->comment("所属问题");
	$table->integer("itemid")->comment("所属问题选项");
	$table->string("content",200)->comment("用户反馈的内容");
	$table->integer("sort")->comment("用户对选项的排序");
	$table->integer("score")->comment("用户对选项打分整数部分");
	$table->tinyInt("ishalf")->comment("用户对选项打分半分部分,0:无半分，1：半分");
	$table->integer("userid")->comment("问题反馈的用户userid");
	$table->datetime("createtime")->comment("问题反馈的时间");
	$table->comment("问题用户反馈信息表");
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
	$table->integer("uploadSize")->comment("限制上传文件大小，单位:MB");
	$table->comment("问题选项规则内容设置");
});

//打分类型定义
$control->create("question_score_type",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("title",200)->comment("类型说明");
	$table->comment("打分类型定义");
});

//题目类型定义
$control->create("question_type",function($table){
	$table->integer("id")->unsigned()->increments();
	$table->string("title",200)->comment("类型说明");
	$table->comment("题目类型定义");
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


$model = new Model($config);

//问题输入规则表
$model->table("question_input_rules")->replaceInto(array(
	array('id'=>1,'title'=>'限数字'),
	array('id'=>2,'title'=>'限正整数'),
	array('id'=>3,'title'=>'限字母'),
	array('id'=>4,'title'=>'限手机号'),
	array('id'=>5,'title'=>'限中文'),
	array('id'=>6,'title'=>'限身份证')
),true);

//输入框类型列表
$model->table("question_input_type")->replaceInto(array(
	array('id'=>1,'name'=>'输入框'),
	array('id'=>2,'name'=>'传文件'),
	array('id'=>3,'name'=>'传图片'),
	array('id'=>4,'name'=>'传视频'),
	array('id'=>5,'name'=>'传音频')
),true);

//打分类型定义
$model->table("question_score_type")->replaceInto(array(
	array('id'=>1,'title'=>'数值打分'),
	array('id'=>2,'title'=>'五星打分'),
	array('id'=>3,'title'=>'半星打分')
),true);

//题目类型定义
$model->table("question_type")->replaceInto(array(
	array('id'=>1,'title'=>'单选题'),
	array('id'=>2,'title'=>'多选题'),
	array('id'=>3,'title'=>'下拉题'),
	array('id'=>4,'title'=>'填空题'),
	array('id'=>5,'title'=>'量表题'),
	array('id'=>6,'title'=>'日期选择题'),
	array('id'=>7,'title'=>'排序题'),
	array('id'=>8,'title'=>'打分题'),
	array('id'=>9,'title'=>'文件题'),
	array('id'=>10,'title'=>'城市选择题'),
	array('id'=>11,'title'=>'线性标度题')
),true);

//自动生成数据表结构文档
$tables = $control->getTableList();
(new phpWord())->create($tables);

?>