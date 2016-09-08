<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_GroupModel extends Sns_Group
{
	public $_multiCondConcernSingle   = array('uid'=>null, 'state'=>1);  //单方关注
	public $_multiCondConcern         = array('uid'=>null, 'state'=>2);  //双方关注
}
?>