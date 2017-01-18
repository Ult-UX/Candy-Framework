<?php
namespace Candy\bundle;
/**
 * QueryBuilder Class
 * SQL 查询语句构造器
 *
 * @package Package Name
 * @subpackage  Subpackage
 * @category    Category
 * @author  ult-ux@outook.com
 * @link    http://ultux.com
 */
class QueryBuilder
{
    /**
     * 构造出基本的 SQL ，包括默认的 SELECT *
     *
     * @param   string  $dbms   指定数据库管理系统，根据数据库类型进行兼容性检查
     */
    public function __construct($dbms = 'mysql')
    {
        $this->init();
    }
    public function init()
    {
        $this->builder = null;
        $this->builder['select'] = 'SELECT *';
    }
    /**
     * 将输入的内容强制转换为字符串并用单引号包裹
     *
     * @param   string  $string 字符串或者其它不合规变量
     * @return  string
     */
    public function quote($string)
    {
        return '\''.(string) $string.'\'';
    }
    /**
     * 最终编译出 SQL 查询语句
     *
     * @return  string
     */
    public function set_SQL($release = true)
    {
        if (!isset($this->builder['from'])) {
            die('No data tables specified!');
        }
        $map = array('select', 'from', 'join', 'where', 'group_by',  'having',  'order_by',  'limit',  'offset');
        $sql = implode(' ', $this->map_builder($map));
        if ($release == true) {
            $this->init();
        }
        return $sql;
    }
    public function map_builder($map)
    {
        foreach ($map as $key) {
            if (isset($this->builder[$key])) {
                $sql[] = $this->builder[$key];
            }
        }
        return $sql;
    }
    /**
     * SELECT
     * $this->builder['select'] string
     *
     * @param   string|array    $columns    字符串或者数组，如果是数组，索引不是数字时会给此列添加一个别名
     * @return  $this
     */
    public function select($columns = '*')
    {
        if (is_array($columns)) {
            foreach ($columns as $key=>$value) {
                if (!is_int($key)) {
                    $array[] = $key.' as '.$value;
                } else {
                    $array[] = $value;
                }
            }
            $this->select(implode(', ', $array));
        } else {
            if ($columns !== '*') {
                $this->builder['select'] = preg_replace('/\ \*/s', '', $this->builder['select']);
                $this->builder['select'] .= ' '.$columns;
            }
        }
        return $this;
    }
    /**
     * SELECT DISTINCT
     *
     * @return  $this
     */
    public function distinct()
    {
        $this->builder['select'] = preg_replace('/^SELECT\ /s', 'SELECT DISTINCT ', $this->builder['select']);
        return $this;
    }
    /**
     * FROM
     * $this->builder['from'] string
     *
     * @param   string|array    $tables    字符串或者数组，如果是数组，索引不是数字时会给此表添加一个别名
     * @return  $this
     */
    public function from($tables)
    {
        if (is_array($tables)) {
            foreach ($tables as $key=>$value) {
                if (!is_int($key)) {
                    $array[] = $key.' as '.$value;
                } else {
                    $array[] = $value;
                }
            }
            $this->from(implode(', ', $array));
        } else {
            // 首次调用需要添加前缀
            if (!isset($this->builder['from'])) {
                $this->builder['from'] = 'FROM';
            }
            $this->builder['from'] .= ' '.$tables;
        }
        return $this;
    }
    /**
     * JOIN
     * $this->builder['join'] string
     *
     * @param   string  $table      要关联的表，在内部进行别名定义
     * @param   string  $astrict    限制表达式
     * @param   string  $direction  JOIN 方式
     * @return  $this
     */
    public function join($table, $astrict, $direction='INNER')
    {
        $jion = ' '.$direction.' JOIN '.$table.' ON '.$astrict;
        if (!isset($this->builder['join'])) {
            $this->builder['join'] = $jion;
        } else {
            $this->builder['join'] .= $jion;
        }
        return $this;
    }
    // WHERE
    private function _where($combinator)
    {
        if (!isset($this->builder['where'])) {
            if (in_array($combinator, array('OR', 'OR NOT'))) {
                die('OR 语句不能作为 WHERE 的首个条件');
            }
            $this->builder['where'] = 'WHERE';
        }
        if (($this->builder['where'] !== 'WHERE' && substr($this->builder['where'], -1) !== '(') or ($combinator == 'NOT')) {
            $this->builder['where'] .= ' '.$combinator;
        }
    }
    public function where($columns, $value = null, $combinator = 'AND')
    {
        if (is_array($columns)) {
            foreach ($columns as $key=>$val) {
                $key = explode(' ', $key);
                if (!isset($key[1])) {
                    $key[1] = '=';
                }
                $key = implode(' ', $key);
                $arr[] = $key.' '.((is_int($val)) ? $val : $this->quote($val));
            }
            $this->_where($combinator);
            $this->builder['where'] .= ' '.implode(' '.$combinator.' ', $arr);
        } else {
            if ($value == null) {
                $this->_where($combinator);
                $this->builder['where'] .= ' '.$columns;
            } else {
                $this->where(array($columns=>$value), null, $combinator);
            }
        }
        return $this;
    }
    public function or_where($columns, $value = null)
    {
        $this->where($columns, $value, 'OR');
        return $this;
    }
    /**
     * WHERE $column IN ($value_1, $value_2, ... $value_n)
     * $this->builder['where'] string
     *
     * @param   string  $column     列
     * @param   string  $value      值($value_1, $value_2, ... $value_n)
     * @param   string  $combinator AND OR
     * @param   string  $reverse    ? NOT
     * @return  $this
     */
    public function where_in($column, $value, $combinator = 'AND', $reverse = '')
    {
        $this->_where($combinator);
        $this->builder['where'] .= ' '.$column.(($reverse) ? ' '.$reverse : '').' IN ('.$value.')';
        return $this;
    }
    public function where_not_in($column, $value)
    {
        $this->where_in($column, $value, null, 'NOT');
        return $this;
    }
    public function or_where_in($column, $value)
    {
        $this->where_in($column, $value, 'OR');
        return $this;
    }
    public function or_where_not_in($column, $value)
    {
        $this->where_in($column, $value, 'OR', 'NOT');
        return $this;
    }
    /**
     * WHERE $column BETWEEN $min AND $max
     * $this->builder['where'] string
     *
     * @param   string  $column     列
     * @param   integer $min        最小值
     * @param   integer $max        最大值
     * @param   string  $combinator AND OR
     * @param   string  $reverse    ? NOT
     * @return  $this
     */
    public function where_between($column, $min, $max, $combinator = 'AND', $reverse = '')
    {
        $this->_where($combinator);
        $this->builder['where'] .= ' '.$column.(($reverse) ? ' '.$reverse : '').' BETWEEN '.$min.' AND '.$max;
        return $this;
    }
    public function where_not_between($column, $min, $max)
    {
        $this->where_between($column, $min, $max, null, 'NOT');
        return $this;
    }
    public function or_where_between($column, $min, $max)
    {
        $this->where_between($column, $min, $max, 'OR');
        return $this;
    }
    public function or_where_not_between($column, $min, $max)
    {
        $this->where_between($column, $min, $max, 'OR', 'NOT');
        return $this;
    }
    public function like($columns, $value = null, $combinator = 'AND', $reverse = '')
    {
        if (is_array($columns)) {
            $this->_where($combinator);
            foreach ($columns as $key=>$val) {
                $this->builder['where'] .= ' '.$key.(($reverse) ? ' '.$reverse : '').' LIKE '.$this->quote($val);
            }
        } else {
            if ($value == null) {
                $this->_where($combinator);
                $this->builder['where'] .= $columns;
            } else {
                $this->like(array($columns=>$value), null, $combinator, $reverse);
            }
        }
        return $this;
    }
    public function not_like($key, $value = null)
    {
        $this->like($key, $value, null, 'NOT');
        return $this;
    }
    public function or_like($key, $value = null)
    {
        $this->like($key, $value, 'OR');
        return $this;
    }
    public function or_not_like($key, $value = null)
    {
        $this->like($key, $value, 'OR', 'NOT');
        return $this;
    }
    /**
     * Wrap conditions with () in WHERE
     *
     * @param   string  $combinator default (AND), can use (OR),(NOT),(OR NOT)
     * @return  $this
     */
    public function group_start($combinator = 'AND')
    {
        $this->_where($combinator);
        $this->builder['where'] .= ' (';
        return $this;
    }
    public function group_end()
    {
        $this->builder['where'] .= ')';
        return $this;
    }
    /**
     * ORDER BY
     * $this->builder['order_by'] string
     *
     * @param   string  $column     要排序的列
     * @param   string  $direction  排序方向
     * @return  $this
     */
    public function order_by($column, $direction = 'DESC')
    {
        // 因为后面需要引用 ORDER BY 的自身设定，故在其未设定时需要先进行设定
        if (!isset($this->builder['order_by'])) {
            $this->builder['order_by'] = 'ORDER BY';
        }
        if ($direction == 'RAND') {
            // 如果采用随机方式排序，则需要将 $column 转换成数字或者直接赋予数字作为随机的种子
            $column = abs((int) $column);
            $this->builder['order_by'] .= ' RAND('.$column.')';
        } else {
            $this->builder['order_by'] .= ' '.$column.' '.$direction;
        }
        return $this;
    }
    /**
     * LIMIT
     * $this->builder['limit'] string
     *
     * @param   integer  $limit     SQL TOP
     * @param   integer  $offset    排序方向
     * @return  $this
     */
    public function limit($limit, $offset = null)
    {
        // 强制转换为整数并取绝对值，如果错误的话就会默认设置为 0
        $limit = abs((int) $limit);
        if ($offset !== null) {
            $offset = abs((int) $offset);
            // 如果已经定义了 OFFSET 语句，就直接删掉它
            if (isset($this->builder['offset'])) {
                unset($this->builder['offset']);
            }
            // 在 LIMIT 语句中重新定义
            $this->builder['limit'] = 'LIMIT '.$offset.', '.$limit;
        } else {
            $this->builder['limit'] = 'LIMIT '.$limit;
        }
        return $this;
    }
    /**
     * OFFSET
     * $this->builder['offset'] string
     *
     * @param   integer  $offset    排序方向
     * @return  $this
     */
    public function offset($offset)
    {
        $offset = abs((int) $offset);
        // 如果已经使用 limit() 设置了 OFFSET 就去掉 OFFSET 的部分
        if (isset($this->builder['limit'])) {
            $this->builder['limit'] = preg_replace('/[0-9]+,\ /s', '', $this->builder['limit']);
        }
        // 单独定义 OFFSET 语法
        $this->builder['offset'] = 'OFFSET '.$offset;
        return $this;
    }
}
