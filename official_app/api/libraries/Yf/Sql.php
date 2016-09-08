<?php
/**
 * 构造SQL语句
 * 
 * 为了操作Db方便，让控制器更灵活的操作数据库。
 * 
 * @category   Framework
 * @package    Db
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Sql
{
    private $where = ' WHERE 1  ';        /* 构造Sql语句where        */
    private $limit = ' LIMIT 200 ';        /* 构造Sql语句Limit        */
    private $order = null;
    private $db    = null;                /* 数据库对象            */
    private $dbId  = null;                /* 数据库对象id            */
    private $sqlRow = array();

    public $sql = null;
    public $lastSql = null;

    public static $arrTrans = array();
    public static $arrLogs = array();
    public static $arrMemcaches = array();
    public static $arrRedis = array();

    public function __construct($db_id = null)
    {
        $this->dbId     = $db_id;
    }

    /**
     * get sqlWhere
     *
     * @return string  $this->where
     * @access public
     *
     * @author 黄新泽
     */

    public function getWhere()
    {
        $where = $this->where;
        $this->resetWhere();

        return $where;
    }

    /**
     * set sqlWhere
     *
     * @param string  $sql;
     * @access public
     *
     * @author 黄新泽
     */
    public function setWhere($key, $val, $symbol='=', $join='AND')
    {
        $key = trim($key);
        //$val = trim($val);

        $symbol = strtoupper($symbol);

        switch ($symbol)
        {
            case '=' :
            case '<' :
            case '>' :
            case '<=' :
            case '>=' :
            case '!=' :
            case '<>' :
                    $val = untrim($val);
                    $this->where .= ' ' . $join . ' ' . $key . $symbol . $val;
                break;
            case 'IN' :
                if (is_array($val))
                {
                    $val = array_map('untrim', $val);
                    $this->where .= ' ' . $join . ' ' . $key . ' IN (' . implode(',', $val) . ')' ;
                }
                else
                {
                    $val = untrim($val);
                    $this->where .= ' ' . $join . ' ' . $key . ' IN (' . $val . ')' ;
                }

                break;
            case 'BETWEEN' :
                $val = untrim($val);

                $this->where .= ' ' . $join . ' ' . $key . ' ' . $symbol . ' ' . $val;
                break;
            case 'LIKE' :
                $val = untrim($val);

                $this->where .= ' ' . $join . ' ' . $key . ' LIKE ' . $val ;
                break;
            default    :
                break;
        }

        return $this;
    }

    public function resetConditions()
    {
        $this->resetWhere();
        $this->resetLimit();
    }

    public function resetWhere()
    {
        $this->where = ' WHERE 1  ';
    }

    public function getLimit() 
    {
        $limit = $this->limit;
        $this->resetLimit();
        return $limit;
    }

    /**
     * set Limit
     *
     * @param    int    $offset;
     * @param    int    $num;
     */
    public function setLimit($offset=0, $num=20) 
    {
        if ($num <= 0)
        {
            $this->resetLimit();
        }
        else
        {
            $this->limit = ' LIMIT ' . $offset . ', ' . $num;
        }

        return $this;
    }

    public function resetLimit()
    {
        $this->limit = ' LIMIT 200 ';
    }


    public function getOrder() 
    {
        $order = $this->order;
        $this->resetOrder();
        return $order;
    }

    /**
     * set Order
     *
     * @param    int    $offset;
     * @param    int    $num;
     */
    public function setOrder($order = null, $flag='ASC') 
    {
        if ($order)
        {
            $this->order = ' ORDER BY  ' . $order . ' ' . $flag;    
        }

        return $this;
    }

    public function resetOrder()
    {
        $this->order = '';
    }


    public function getGroup() 
    {
        $group = $this->group;
        $this->resetGroup();

        return $group;
    }

    /**
     * set Group
     *
     * @param    string    $group;
     */
    public function setGroup($group = null) 
    {
        if ($group)
        {
            $this->group = ' GROUP BY  ' . $group;    
        }

        return $this;
    }

    public function resetGroup()
    {
        $this->group = '';
    }

    public function update($table, $value, $where=null)
    {
        $this->sql = 'UPDATE ' . $table . ' SET ' . $value;

        if ($where)
        {
            $this->sql .= ' WHERE ' . $where;
        }

        return $this->sql;
    }

    public function insert($table, $field=null, $value=null)
    {
        $this->sql = 'INSERT INTO ' . $table;

        if ($value)
        {
            $this->sql .= ' SET ' . $field;
        }
        elseif($field)
        {

            $this->sql .= '(' . $field . ')';

            $this->sql .= ' VALUES(' . $value . ')';
        }
        else
        {
            
        }

        return $this->sql;
    }

    public function select($field='*', $table, $where=null, $order=null, $limit=null)
    {
        $this->sql = 'SELECT ' . $field . ' FROM ' . $table;

        if ($where)
        {
            $this->sql .= ' WHERE ' . $where;
        }

        if ($order) $this->sql .=' ORDER BY ' . $order;
        if ($limit) $this->sql .=' LIMIT ' . $limit;

        return $this->sql;
    }

    public function delete($table, $where=null)
    {
        $this->sql = 'DELETE FROM ' . $table;

        if ($where)
        {
            $this->sql .= ' WHERE ' . $where;
        }

        return $this->sql;
    }

    public function exec($sql)
    {
        $this->getDb();
        
        $rs = $this->db->exec($sql);

        return $rs;
    }

    public function query($sql)
    {
        $this->getDb();
        
        $rs = $this->db->query($sql);

        return $rs;
    }


    public function sqlAdd($sql)
    {
        $this->sqlArr[] = $sql;
    }

    public function sqlRun()
    {
        foreach ($this->sqlArr as $sql)
        {
            if (!$this->exec($sql))
            {
                return false;
            }
        }

        return true;
    }

    public function affectedRows()
    {
        return $this->db->affectedRows();
    }

    public function insertId()
    {
        return intval($this->db->insertId());
    }

    public function getAll($sql)
    {
        $this->getDb();
        
        $this->lastSql = $sql;

        $rs = $this->db->getAll($sql);

        /*
        if (empty($rs))
        {
            return false;
        }
        else
        {
            return $rs;
        }
        */

        return $rs;
        
    }

    public function getRow($sql)
    {
        $this->getDb();
        
        $this->lastSql = $sql;
        $rs = $this->db->getRow($sql);

        if (empty($rs))
        {
            return false;
        }
        else
        {
            return $rs;
        }
    }

    public function getSql() 
    {
        return $this->lastSql;
    }

    public function setDb($db)
    {
        $this->db = &$db;
    }

    public function getDb()
    {
        /*
        if (!$this->db ||  !$this->db->getDbHandle())
        {
            $this->db = Yf_Db::get($this->dbId);
        }
        
        return $this->db;
        */
        return $this->resetDb();
    }

    public function resetDb()
    {
        $this->db = Yf_Db::get($this->dbId);

        return $this->db;
    }

    /**
     * 事务开始
     *
     * @param string $id  database id
     *
     * @return bool   true/false
     */
    public function startTransaction()
    {
        if (self::$arrTrans)
        {
            $this->getDb();

            return $this->db->startTransaction();
        }
    }

    /**
     * 提交事务
     *
     * @param string $id  database id
     *
     * @return bool   true/false
     */
    public function commit()
    {
        $flag = false;

        if (self::$arrTrans)
        {
            $flag = true;
        }

        if (true ===  self::exePreTran())
        {
            if(true === self::exePreRedis())
            {
                if ($flag)
                {
                    $this->db->commit();
                }

                self::exePreMemcache();//提交memcache
                self::exePreLog();//提交日志

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }

    }

    /**
     * 回滚事务
     *
     * @return bool   true/false
     */
    public function rollBack()
    {
        self::$arrTrans = array();
        self::$arrRedis = array();
        self::$arrMemcaches = array();
        self::$arrLogs = array();

        if($this->db)
        {
            return $this->db->rollBack();
        }
    }


    /**
     * 事务开始
     *
     * @param string $id  database id
     *
     * @return bool   true/false
     */
    public function startTransactionDb()
    {
		$this->getDb();

		return $this->db->startTransaction();
    }

    /**
     * 提交事务
     *
     * @param string $id  database id
     *
     * @return bool   true/false
     */
    public function commitDb()
    {
		$this->db->commit();
    }

    /**
     * 回滚事务
     *
     * @return bool   true/false
     */
    public function rollBackDb()
    {
        return $this->db->rollBack();
    }

    public function debugSql_1($sql, $type='e')
    {
        if ('s' == $type)
        {
            $handle = @fopen(ROOT_PATH . '/server/log/' .date('Ymd'). '-debug_select.sql', a);
        }
        else
        {
            $handle = @fopen(ROOT_PATH . '/server/log/' .date('Ymd'). '-debug.sql', a);
        }

        if ($handle)
        {
            if (fwrite($handle, $sql) === FALSE)
            {
            }

            fclose($handle);
        }
    }

    //$result=1,执行结果影响行数必须为1，否则不允许往下执行。$result=0，则表示$result>=0都成功，执行结果只要!==false,都算执行成功
    public function setPreTran($function, $param_arr, $result=1)
    {
        $call_back = array(
            'function' => $function,
            'param_arr' => $param_arr,
            'result' => $result
        );
        self::$arrTrans[] = $call_back;

        return true;
    }

    
    public function exePreTran()
    {
        $result = true;
        foreach(self::$arrTrans as $key => $value)
        {
            $call_result = call_user_func_array($value['function'], $value['param_arr']);

            if (false === $call_result)
            {
                $result = false;
            }
            elseif (true === $call_result)
            {
                $result = true;
            }
            else
            {
                if (0 == $value['result'])
                {
                    if($call_result>=0)
                    {
                        $result = true;
                    }
                }
                else
                {
                    if ($call_result === 1)
                    {
                        $result = true;
                    }
                    else
                    {
                        $result = false;
                    }
                }
            }

            if (!$result)
            {
                $msg = sprintf('%s::%s(%s)', get_class($value['function'][0]), $value['function'][1], json_encode($value['param_arr']));

                Yf_Log::log(json_encode($msg), Yf_Log::ERROR, 'transaction');

                break;
            }

        }

        self::$arrTrans = array();

        return $result;
    }

    public function setPreLog($function,$param_arr)
    {
        $call_back = array(
            'function' => $function,
            'param_arr' => $param_arr
        );
        self::$arrLogs[] = $call_back;
    }

    public function exePreLog()
    {
        foreach(self::$arrLogs as $key => $value)
        {
            call_user_func_array($value['function'],$value['param_arr']);
        }

        self::$arrLogs = array();

        return true;
    }

    public function setPreMemcache($function,$param_arr)
    {
        $call_back = array(
            'function' => $function,
            'param_arr' => $param_arr
        );
        self::$arrMemcaches[] = $call_back;
    }

    public function exePreMemcache()
    {
        foreach(self::$arrMemcaches as $key => $value)
        {
            call_user_func_array($value['function'],$value['param_arr']);
        }

        self::$arrMemcaches = array();

        return true;
    }

    public function setPreRedis($function,$param_arr)
    {
        $call_back = array(
            'function' => $function,
            'param_arr' => $param_arr
        );
        self::$arrRedis[] = $call_back;

        return true;
    }

    public function exePreRedis()
    {
        if (self::$arrRedis)
        {
            $objRedis = Yf_Cache::create('redis_data');
            $objRedis->startTrans();

            foreach(self::$arrRedis as $key => $value)
            {
                $result = call_user_func_array($value['function'],$value['param_arr']);

                if(!$result)
                {
                    $msg = sprintf('%s::%s(%s)', get_class($value['function'][0]), $value['function'][1], json_encode($value['param_arr']));

                    Yf_Log::log(json_encode($msg), Yf_Log::ERROR, 'transaction');

                    return false;
                }
            }

            $arr_result = $objRedis->commit();//var_dump($arr_result);die("kkkkk");

            if($arr_result === false)
            {
                foreach(self::$arrRedis as $key => $value)
                {
                    $msg = sprintf('%s::%s(%s)', get_class($value['function'][0]), $value['function'][1], json_encode($value['param_arr']));
                    Yf_Log::log(json_encode($msg), Yf_Log::ERROR, 'transaction');
                }

                return false;
            }

            self::$arrRedis = array();
        }

        return true;
    }
}
?>