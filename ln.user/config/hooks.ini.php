<?php
$config = [];
$config['hooks']['application'] = [
    'before_select'      => ['Common\Hook\BeforeSelect'],

    'before_insert'      => ['Common\Hook\BeforeInsert'],

    'before_update'      => ['Common\Hook\BeforeUpdate'],

    'before_delete'      => ['Common\Hook\BeforeDelete'],
];
return $config;