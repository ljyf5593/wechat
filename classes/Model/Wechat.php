<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 微信公众号模型
 */
class Model_Wechat extends ORM {
    protected $_created_column = array(
        'column' => 'dateline',
        'format' => TRUE,
    );

    /**
     * 获取当前微信账号的接入地址
     */
    public function get_request_url() {
        return URL::site('/cgi/request/'.$this->pk(), 'http');
    }

    /**
     * 响应首次订阅事件
     * @param $request
     */
    public function response_subscribe(Wechat_Response $wechat_response) {
        return $wechat_response->reply_text('感谢您订阅我们的微信公众帐号');
    }

    /**
     * 响应文本信息
     * @param Wechat $wechat_request
     */
    public function response_text(Wechat_Response $wechat_response, $content) {
        return $wechat_response->reply_text('没有找到匹配项');
    }
}