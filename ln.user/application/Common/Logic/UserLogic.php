<?php

namespace Common\Logic;

class UserLogic
{
    public function recursiveArea($pid, $category){
        $data = [];
        foreach ($category as $item) {
            if ($item['pid'] == $pid) {
                $arr['id'] = $item['id'];
                $arr['name'] = $item['name'];
                $child = $this->recursiveArea($item['id'], $category);
                if (!empty($child)) {
                    $arr['child'] = $child;
                }
                $data[] = $arr;
                unset($arr);
            }
        }
        return $data;
    }





}