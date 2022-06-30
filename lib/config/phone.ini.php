<?php
$c_a = [
      //clb.stocksir
      'mobile@getId' => 'mobile',
      'to_third_api@remove_member_goods' => 'member_mobile',

      //content.stocksir
      'member_mobile@isMemberForCXT' => 'mobile',
      'best_combine@get_member' => 'mobile',
      'best_combine@get_member_compliance' => 'mobile',
      'risk_wechat@addRiskWechat' => 'member_mobile',
      'risk_wechat@isRisk' => 'member_mobile',
      'risk_wechat@updateRisk' => 'member_mobile',
      'orderinfo@get_order_list' => 'mobile',
      'orderinfo@get_online_order_list' => 'mobile_list',//app
      'orderinfo@check_order_valid' => 'mobile',
      'verification_code@verification' => 'mobile',

      //login.stocksir
      'check_user@checkPassword' => 'mobile',
      'crmuser@reg' => 'mobile',
      'password@find' => 'mobile',
      'user@login' => 'username',
      'user@reg' => 'mobile',
      'user@reg_lgd' => 'mobile',
      'user_cxt@login' => 'username',
      'user_cxt@appLogin' => 'username',
      'user_cxt@reg' => 'mobile',
      'user_cxt@find' => 'mobile',
      'user_cxt@import' => 'mobile',
      'user_cxt@search_member_info' => 'tel',
      'user_cxt@mobile' => 'tel',
      'user_cxt@appreg' => 'mobile',
      'validation_member@index' => 'member_mobile',
      'cxtapp_verification@send' => 'mobile',
      //u.stocksir
      'certification@post' => 'member_mobile2',
      'verification@send' => 'mobile',
      'verification@verification_code' => 'mobile',
      'verification@sendactive' => 'mobile',
      'verification@verification_active_code' => 'mobile',
      'verification_code@send' => 'mobile',
      'verification_code@verification_code' => 'mobile',
      'verification_code@sendactive' => 'mobile',
      'verification_code@verification_active_code' => 'mobile',
      'smallprogram@binding_mobile' => 'mobile',
      'wechat_message@binding_mobile' => 'mobile',
      'wechat_message@binding_mobile_jjjty' => 'mobile',
      'wechat_message@hgmobel' => 'mobile',
      'member@add_member_audit' => 'member_mobile',
      'cxtapp_verification@send' => 'mobile',
      'crm_push@push' => 'member_phone',

      //compliance
      'adapt@addAdapt' => 'member_mobile',
      'agency@list' => 'member_mobile',
      'common@getMemberSurveyRecode' => 'member_mobile',
      // 'compliance_status@member_status' => 'member_mobiles',   //特殊处理
      'contract@contractList' => 'member_mobile',
      'contract@agreement' => 'member_mobile',
      'contract@contract' => 'member_mobile',
      'contract@contractPost' => 'member_mobile',
      'contract@agreementGoDel' => 'member_mobile',
      'contract@h5Contract' => 'member_mobile',
      'contract@contractOrder' => 'member_mobile',
      'copy@getInfo' => 'member_mobile',
      'entrance@getApp' => 'member_mobile',
      'position@submit' => 'member_mobile',
      'position@idcard' => 'member_mobile',
      'position@video' => 'member_mobile',
      'question@getVisit' => 'member_mobile',
      'question@getVisitTime' => 'member_mobile',
      'question@postVisit' => 'member_mobile',
      'risk@addRisk' => 'member_mobile',
      'risk@isRisk' => 'member_mobile',
      'userlimit@getList' => 'member_mobile',
      'verify@list' => 'member_mobile',
//tps
      'agency_laozhou@addQuestion' => 'mobile',
      'agency_list@join' => 'agency_contacts_mobile',
      'agency_list_new@join_apply' => 'mobile',
      'agency_list@join_apply' => 'mobile',
      'agency_list@addMemberService' => 'member_mobile',
      'agency_list@is_buy' => 'member_mobile',
      'crmapi@get_service_time' => 'mobile_list',
      'crmapi@get_member_asc' => 'mobile_list',

//stockpay
      'buy@create' => 'buyer_mobile',
//trade.stocksir
      'wxbuy@step2' => 'phone',

];
return $c_a;