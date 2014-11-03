<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Wechat_Cgi extends Controller {

    /**
     * 微信接入与请求地址
     */
    public function action_response(){
        $response = Wechat_Response::instance();
        if($this->request->method() == HTTP_Request::POST){
            $wechat_model = ORM::factory('Wechat', $this->request->param('id'));
            if ($wechat_model->loaded()) {
                $post_data = file_get_contents('php://input');
                echo $response->execute($wechat_model, $post_data);
            } else {
                echo '参数错误';
            }
        } elseif($this->request->query('echostr')){
            if($response->check_signature($this->request->query())){
                echo $this->request->query('echostr');
            }
        }
    }
}