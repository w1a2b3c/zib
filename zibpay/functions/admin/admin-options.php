<?php
/*
 * @Author        : Qinver
 * @Url           : zibll.com
 * @Date          : 2020-11-03 00:09:44
 * @LastEditTime: 2024-06-26 12:01:02
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

//判断挂钩是否正常，防止挂钩被删除
if (!has_filter('csf_zibll_options_save', 'zib_save_options_filter')) {
    exit(base64_decode('5Li76aKY5paH5Lu25o2f5Z2P77yM6K+36YeN6KOF5Li76aKY77yB'));
}

function zibpay_admin_options()
{
    add_filter('csf_zibll_options_save', 'zib_options_save_filter', 20);
    add_action('admin_menu', 'add_settings_menu');
}
zibpay_admin_options();

function zib_options_save_filter($data)
{
    if (empty($data['post_key'])) {
        if (aut_required()) {
            wp_send_json_success(array('notice' => 'The theme file' . ' is abnormal,' . ' please ' . 'reinstall the theme!', 'errors' => array()));
            exit();
        } else {
            $data['post_key'] = true;
        }
    }

    //先判断第一个挂钩，第一个挂钩失效则执行一下代码
    if (empty($data['save_filter']) && !ZibAut::is_local()) {
        $time                 = _pz('current_time');
        $data['current_time'] = $time;
        $notice               = ($time && floor((strtotime(date("Y-m-d H:i:s")) - strtotime($time)) / 3600) > 385) ? ZibAut::curl_aut() : '';
        if (isset($notice['result']) && !$notice['result']) {
            delete_option('post_' . 'autkey');
            $data['post_key'] = false;
            wp_send_json_success(array('notice' => 'The theme file' . ' is abnormal,' . ' please ' . 'reinstall the theme!', 'errors' => array()));
            exit();
        }
        if (!empty($notice['result'])) {
            $data['post_key'] = true;
        }
        if (!empty($notice['result']) || !$time) {
            $user['current_time'] = date("Y-m-d H:i:s");
        }
    }

    return $data;
}

/**
 * 创建后台管理菜单
 */
function add_settings_menu()
{
    add_menu_page('Zibll商城', 'Zibll商城', 'administrator', 'zibpay_page', 'zibpay_page', 'dashicons-cart');
    add_submenu_page('zibpay_page', '商品明细', '商品明细', 'administrator', 'zibpay_product_page', 'zibpay_product_page');
    add_submenu_page('zibpay_page', '订单明细', '订单明细', 'administrator', 'zibpay_order_page', 'zibpay_order_page');
    if (_pz('pay_income_s')) {
        add_submenu_page('zibpay_page', '创作分成明细', '分成明细', 'administrator', 'zibpay_income_page', 'zibpay_income_page');
    }
    if (_pz('pay_rebate_s')) {
        add_submenu_page('zibpay_page', '推广返佣明细', '佣金明细', 'administrator', 'zibpay_rebate_page', 'zibpay_rebate_page');
    }
    add_submenu_page('zibpay_page', '卡密管理', '卡密管理', 'administrator', 'zibpay_charge_card_page', 'zibpay_charge_card_page');
    add_submenu_page('zibpay_page', '优惠码管理', '优惠码管理', 'administrator', 'zibpay_coupon_page', 'zibpay_coupon_page');
    add_submenu_page('zibpay_page', '提现记录', '提现记录', 'administrator', 'zibpay_withdraw', 'zibpay_withdraw_page');
    add_submenu_page('zibpay_page', '会员管理', '会员管理', 'administrator', 'users.php', '');
}

function zibpay_page()
{
    if (!_pz('post_key')) {
        zib_get_admin_aut_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/index.php';
    }
}
function zibpay_income_page()
{
    if (!_pz('post_key')) {
        zib_get_admin_aut_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/income.php';
    }
}
function zibpay_order_page()
{
    if (!_pz('post_key')) {
        zib_get_admin_aut_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/order.php';
    }
}

function zibpay_product_page()
{
    if (!_pz('post_key')) {
        zib_get_admin_aut_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/product.php';
    }
}

function zibpay_rebate_page()
{
    if (!_pz('post_key')) {
        zib_admin_aut_err_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/rebate.php';
    }
}

function zibpay_withdraw_page()
{
    if (!_pz('post_key')) {
        zib_admin_aut_err_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/withdraw.php';
    }
}

function zibpay_charge_card_page()
{
    if (!_pz('post_key')) {
        zib_admin_aut_err_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/charge-card.php';
    }
}

function zibpay_coupon_page()
{
    if (!_pz('post_key')) {
        zib_admin_aut_err_notice();
    } else {
        require_once get_stylesheet_directory() . '/zibpay/page/coupon.php';
    }
}
