<?php
namespace Spark\libraries;

class Pagination
{
    // 总页数
    private $total;
    // 当前页数
    private $current;

    public function __construct()
    {
    }
    public function set_config($config)
    {
        foreach($config as $key=>$value)
        {
            $this->{$key} = $value;
        }
        return $this;
    }
    public function parse_navs()
    {
        return $this;
    }
    public function create_navs($config = null)
    {
        if ($config !== null) {
            $this->set_config($config);
        }
        $this->parse_navs();
        // $this->total = ceil($total/6);
        // $this->current = $current;
        $output = '<ul class="pagination" role="navigation" aria-label="Pagination">';
        if ($this->current == 1) {
            $output .= '<li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>';
        } else {
            $output .= '<li class="pagination-previous"><a href="/category/default/'.($this->current-1).'.html" aria-label="Previous page">Previous <span class="show-for-sr">page</span></a></li>';
        }
        $min = (($this->current - 5) < 1) ? 1 : ($this->current - 5);
        $max = (($this->current + 5) > $this->total) ? $this->total : ($this->current + 5);
        if ($min > 1) {
            $output .= '<li><a href="/category/default.html" aria-label="Page 1">1</a></li>';
            $output .= '<li class="ellipsis" aria-hidden="true"></li>';
        }
        for ($i=$min; $i<=$max; $i++) {
            if ($this->current == $i) {
                $output .= '<li class="current"><span class="show-for-sr">You\'re on page</span> '.$i.'</li>';
            } else {
                $output .= '<li><a href="/category/default/'.$i.'.html" aria-label="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if ($max < $this->total) {
            $output .= '<li class="ellipsis" aria-hidden="true"></li>';
            $output .= '<li><a href="/category/default/'.$this->total.'.html" aria-label="Page '.$this->total.'">'.$this->total.'</a></li>';
        }
        if ($this->current == $this->total) {
            $output .= '<li class="pagination-next disabled">Next <span class="show-for-sr">page</span></li>';
        } else {
            $output .= '<li class="pagination-next"><a href="/category/default/'.($this->current+1).'.html" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>';
        }
        $output .= '</ul>';
        return $output;
    }
}

