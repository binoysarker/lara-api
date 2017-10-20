<?php
/**
 * Created by PhpStorm.
 * User: binoy
 * Date: 10/19/17
 * Time: 8:54 PM
 */

namespace App\Traits;


trait Orderable
{
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at','desc');
    }
    public function scopeOldestFirst($query)
    {
        return $query->orderBy('created_at','asc');
    }

}