<?php
include_once("../includes/global.php");
$script_tmp = explode('/', $_SERVER['SCRIPT_NAME']);
$sctiptName = array_pop($script_tmp);
include_once("auth.php");
//===========================================
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?php echo lang_show('admin_system');?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</HEAD>

<body>
<?php
include_once("../includes/arrcache_class.php");
$caches     = new ArrCache('../cache/front');
$cachetime = 0;//数据调用缓存时间。
if(!$caches->begin('pageview',$cachetime))
{
		
	if($config['openstatistics'])
	{
		
		$sql="select ip,username,count(*) as num from ".PV." where to_days(time)  = to_days(now()) group by ip order by num desc, ip asc,username desc limit 10"; 
		$db->query($sql);
		$ipNum=$db->num_rows();
		$list=$db->getRows();
		
		$sql="select count(*) as num from ".PV." where to_days(time) = to_days(now())";
		$db->query($sql);
		$pvs=$db->fetchField('num');//pv总数
		
		//------------------------------
		$sql="select count(distinct ip) as ips from ".PV." where to_days(time) = to_days(now())";
		$db->query($sql);
		$ips=$db->fetchField('ips');//独立ip总数
		
		//-----------------------------------
		$sql="select count(url) as urls from ".PV." where to_days(time) = to_days(now())";
		$db->query($sql);
		$urls=$db->fetchField('urls');//url总数
		
		//-------------------------------------
		$sql="select url,count(*) as num from ".PV." where to_days(time) = to_days(now()) group by url order by num desc limit 1";
		$db->query($sql);
		$rs=$db->fetchRow();
		$mostpopurl=$rs['url'];//最受欢迎的url
		$urlvisitnum=$rs['num'];//访问次数
		
		//-----------------------------------
		$sql="select count(distinct username) as users from ".PV." where to_days(time) = to_days(now()) and  username<>''";
		$db->query($sql);
		$onusers=$db->fetchField('users');//上线会员数
		
		//-----------------------------------
		$sql="select count(*) as reguser from ".MEMBER." where TO_DAYS(NOW())-TO_DAYS(regtime)=0";
		$db->query($sql);
		$nregusers=$db->fetchField('reguser');//新注册会员数
		
		
		//-------------------------------------
		$sql="select count(*) as product from ".PRODUCT." where TO_DAYS(NOW())=TO_DAYS(from_unixtime(uptime))";
		$db->query($sql);
		$product=$db->fetchField('product');//新发布产品数
		
		//-------------------------------------
		$sql="select count(*) as orders from ".ORDER." where TO_DAYS(NOW())=TO_DAYS(from_unixtime(create_time))";
		$db->query($sql);
		$order=$db->fetchField('orders');//订单数
		
		//--------------------------目前游客数
		$nowonline=time()-600;
		$nt=date("Y-m-d H:i:s",$nowonline);
		$sql="select count(distinct ip) as nouss from ".PV." where username='' and time>'$nt' order by time desc";
		$db->query($sql);
		$nousers=$db->fetchField('nouss');//游客数
	
		//--------------------------目前在线会员
		$nowonline=time()-600;
		$nt=date("Y-m-d H:i:s",$nowonline);
		$sql="select * from ".PV." where username<>'' and time>='$nt' group by username order by time desc";
		$db->query($sql);
		$rs=$db->getRows();
	}
?>
<link href="main.css" rel="stylesheet" type="text/css" />
<?php
if(!$config['openstatistics'])
{
admin_msg('system_config.php',"统计功能已关闭，请在系统设置中将统计功能打开");
}
else
{
?>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('ttitle');?></div>
    <div class="bigboxbody">
    <table width="100%" border="0" cellpadding="1" cellspacing="0">
        <tr>
            <td width="138">IP总数</td>
            <td width="304"><?php echo $ips;?></td>
            <td width="170">PV总数</td>
        	<td width="386"><?php echo $pvs;?></td>
        </tr>
        
        <tr>
            <td>URL总数</td>
            <td><?php echo $urls;?></td>
            <td>最受欢迎URL</td>
            <td><?php echo urldecode($mostpopurl);?>  (<?php echo $urlvisitnum;?>)</td>
        </tr>
        
        <tr>
            <td>新增商品数</td>
            <td><?php echo $product;?></td>
            <td>新增订单数</td>
            <td><?php echo $order;?></td>
        </tr>
        <tr>
            <td>今日上线会员数</td>
            <td><?php echo $onusers;?></td>
            <td>新注册会员数</td>
            <td><?php echo $nregusers;?></td>
        </tr>
        <tr>
            <td>目前在线游客数</td>
            <td><?php echo $nousers;?></td>
        	<td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr> 
            <td>目前在线活动会员</td>
            <td colspan="3">
            <?php 
            foreach($rs as $key=>$u)
            {
              echo "<a href='#' title='$u[url]' >".$u['username']."</a>&nbsp;&nbsp;";
              if(($key+1)%10==0)
                echo '<br>';
            }
            ?>
            </td>
        </tr>
    </table>
    </div>
</div>

<div style="float:left; height:20px; width:80%;">&nbsp;</div>
<div class="bigbox">
	<div class="bigboxhead"><?php echo lang_show('ipnot');?></div>
        <div class="bigboxbody">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="theader">
        <td width="291" ><?php echo lang_show('ip');?></td>
        <td width="339"><?php echo lang_show('user');?></td>
        <td width="293"><?php echo lang_show('view_count');?></td>
        <td width="100"><?php echo lang_show('option');?></td>
        </tr>
        <?php
        if(is_array($list))
        {
			foreach($list as $value)
			{
        ?>
            <tr>
            <td><?php echo $value['ip']; echo '['.convertip($value['ip'], '../lib/tinyipdata.dat').']';?></td>
            <td><?php echo $value['username']; ?>&nbsp;</td>
            <td><?php echo $value['num']; ?></td>
            <td><a href="iplockset.php?ip=<?php echo $value['ip'];?>"><?php echo lang_show('forbidden');?></a></td>
            </tr>
        <?php
        	}
        }
        ?>
        </table>
    </div>
</div>
<?php 
}
}
$caches->end();
?>
</body>
</html>
