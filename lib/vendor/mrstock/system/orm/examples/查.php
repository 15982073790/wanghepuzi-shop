<?php

require_once "common.php";

Use MrStock\System\Orm\Model;

$model = new Model('member');

//Select 方法：取得查询信息，返回结果为数组，如果未找到数据返回null，select一般在where,order,tabale等方法的后面，作为最后一个方法来使用。如:
// 查询会员表全部信息
$re = $model->field("member_id")->select();
print_r($re[0]);


//取得性别为1的会员列表信息, 注意：select方法需要在连贯操作中的最后一步出现
$re =  $model->where(array('member_sex'=>1))->field("member_id")->select();
print_r($re[0]);

//Limit 方法：指定返回多少条记录数，
//等同于SELECT * FROM member LIMIT 4;
$re = $model->field("member_id")->limit(4)->select();
print_r($re[0]);


//等同于SELECT * FROM member LIMIT 4,10;
$re = $model->field("member_id")->limit('4,10')->select();
print_r($re[0]);


//find 方法：取得一条记录信息 返回对象,find同select一样，一般作为最后一个方法来使用，如：
// 查询ID为5的会员信息
$re = $model->where(array('member_id'=>5))->find();
print_r($re);


// 内链接查询member和store表,并返回前两条记录
$on = 'seller.member_id=member.member_id';
$re = $model->table('member,seller')->field("*")->join('inner')->on($on)->limit(2)->select();
print_r($re[0]);


//内链接查询member和store,然后左链接store_class,查询会员ID为6的记录信息
$field = 'member.member_name,member_risk.assess_content,member_extend.member_truename';

$on = 'member_risk.member_id=member.member_id,member_risk.member_id=member_extend.member_id';

$model->table('member,member_risk,member_extend')->field($field);

$re = $model->join('inner,left')->on($on)->where('member.member_id=6')->select();
print_r($re);


//返回会员ID大于15的记录数 返回对象
/*
Array
(
    [ag_count] => 1012282
)
*/
$re = $model->where('member_id>15')->count();
print_r($re);

//Page 方法：实现记录分页，格式为page(每页显示数)
//系统会跟据每页显示数和已知属性自动计算总记录数 注意：如果同时使用where和page方法时，where方法要在page方法前面使用，如：
$re = $model->page(10)->order('member_id desc')->select();
print_r(count($re));


//Group 方法：实现分组功能，如：
$re =$model->field('member_sex,count(*) as count')->group('member_sex')->select();
print_r($re);


//Having 方法：结合group方法，进行条件过滤，传入参数为字符串形式，如：

//查找发布商品超过500的店铺ID
$re =$model->field('member_sex,count(*) as count')->group('member_sex')->having('count > 10000')->select();
print_r($re);



//Distinct 方法：可以去除列中相同的值，distinct只接受一个参数值true,如果不需要重复值筛选，不使用该方法即可。
//查找拥有商品的店铺主键

$re =$model->field('member_sex')->distinct(true)->select();
print_r($re);


//Query/execute 方法，两个方法均用于直接执行SQL语句，query方法用于查询，execute方法用于更新、写入和删除操作，如：

$re = $model->query('SELECT * FROM `stock_member` LIMIT 10');
print_r($re);

$re = $model->execute('UPDATE `stock_member` SET member_sex =member_sex+1 WHERE member_id=6');
print_r($re);




//Sum/Avg/Max/Min 方法：求和、求平均值、取最大值、取最小值，如：

//返回所有member_id之和
$re = $model->sum('member_id');
print_r($re);

//取商品表中所有member_id平均

$re = $model->avg('member_id');
print_r($re);

//取商品的最member_id

$re = $model->max('member_id');
print_r($re);
//取商品的最member_id

$re = $model->min('member_id');
print_r($re);


//自增/自减：系统使用setInc和setDec完成自增和自减，示例如下：

//使主键值为6的member_sex加1000
$re = $model->where(array('member_id'=>6))->setInc('member_sex',1000);
print_r($re);

//使主键值为6的member_sex减1000
$re = $model->where(array('member_id'=>6))->setDec('member_sex',1000);
print_r($re);
//等同于：UPDATE `goods` SET goods_click=goods_click-1000 WHERE ( goods_id = '2' )

/*
系统对常用运算符的使用进行了二次封装，使用方便、快捷。

gt ： 大于（>）

egt ： 大于等于（>=）

lt ： 小于（<）

elt ： 小于等于（<=）

eq ： 等于（=）

neq ： 不等于（!=）

notlike ： NOT LIKE

like ： 同 sql 中的 LIKE

between：同 sql 中的 BETWEEN

[not] in：同 sql 中的 [NOT] IN

​

示例：
*/
//为便于演示，这里将所有运算符的使用均罗列出来，以下代码不可直接运行

$condition=array();

// uid > 5

$condition['uid'] = array('gt',5);

// uid < 5

$condition['uid'] = array('lt',5);

// uid = 5

$condition['uid'] = array('eq',5);

// uid >= 5

$condition['uid'] = array('egt',5);

// uid <= 5

$condition['uid'] = array('elt',5);

// uid 在 3,5,19 之间一个或多个

$condition['uid'] = array('in','3,5,19');

// uid 是 3,5,19 中的任何值

$condition['uid'] = array('not in','3,5,19');

// 5 <= uid <= 19

$condition['uid'] = array('between','5,19');

//product_name like 'a%'

$condition['product_name'] = array(array('like','a%'));

// product_name like 'a%' or product_name like 'b%'
$condition['product_name'] = array(array('like','a%'),array('like','b%'),'or');

//会员昵称或姓名有一个含有 shopnc 字样的即可满足
$condition['member_name|member_trname'] = array(array('like','%shopnc%'));

//会员昵称或姓名都必须含有 shopnc 字样的才可满足
$condition['member_name&member_trname'] = array(array('like','%shopnc%'));

//以上各条件默认均是 "AND" 关系，即每个条件都需要满足，如果想满足一个即可（ "OR" 关系） ，可增加以下条件

$condition['_op'] = 'or';

//最后将以上条件传入 where 方法

$list = Model(TABLE)->where($condition)->select();

//( ((member_name = '2b6e4235554f7555776d42626c66626742506e3143513d3d') ) OR ((member_mobile = '2b6e4235554f7555776d42626c66626742506e3143513d3d') ) ) 
//AND 
//( (member_passwd = '25d55ad283aa400af464c76d713c07ad') OR (member_passwd = '12345678') )
$map = [];
$map['member_name|member_mobile'] = [
    [
        'eq',
        $this->request->param['mobile_en']
    ]
];
$map['member_passwd'] = [
    [
        'eq',
        md5($this->request->param['password'])
    ],
    [
        'eq',
        $this->request->param['password']
    ],
    'or'
];

