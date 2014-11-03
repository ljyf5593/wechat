<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 自定义菜单模型
 *
 * @author Liu.Jie <ljyf5593@gmail.com>
 *
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Model_Menu extends ORM {

    const TYPE_CLICK = 1;
    const TYPE_VIEW = 2;
    const TYPE_CHILD = 3;

    public static $type_maps = array(
        self::TYPE_CLICK => '按钮',
        self::TYPE_VIEW => '连接',
        self::TYPE_CHILD => '子菜单',
    );

    protected $_serialize_columns = array(
        'menu_data',
    );

    /**
     * 生成菜单需要的json字符串
     * @return string
     */
    public function generate_menu_json() {
        $menu_json = array();
        foreach ($this->menu_data as $item) {
            $menu_json[] = $this->get_json_menu_item($item);
        }

        return $menu_json;
    }

    private function get_json_menu_item($item) {
        $menu = array(
            'name' => $item['name'],
        );
        switch ($item['type']) {
            case self::TYPE_CLICK:
                $menu['type'] = 'click';
                $menu['key'] = $item['value'];
                break;
            case self::TYPE_VIEW:
                $menu['type'] = 'view';
                $menu['url'] = $item['value'];
                break;
            case self::TYPE_CHILD:
                foreach ($item['value'] as $child) {
                    $menu['sub_button'][] = $this->get_json_menu_item($child);
                }
        }

        return $menu;
    }

    /**
     * 获取微信的自定义菜单
     * @param $wechat_id
     * @return array
     */
    public function get_wechat_menu($wechat_id) {
        $menu_list = array(
            array(
                'name' => '文章',
                'type' => self::TYPE_CHILD,
                'value' => array(
                    array(
                        'name' => '教学文章',
                        'type' => self::TYPE_CLICK,
                        'value' => '教学文章'
                    )
                ),
            ),
        );

        return $menu_list;
    }
} 