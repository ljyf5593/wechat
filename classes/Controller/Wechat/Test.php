<?php

class Controller_Wechat_Test extends Controller {
    public function action_index() {
        if ($this->request->method() === HTTP_Request::POST) {
            $request = Request::factory($this->request->post('url'));
            $request->method(HTTP_Request::POST)->body($this->request->post('data'));
            $response = $request->execute();
            $body = $response->body();
            if($response->status() == 200 ) {
                echo HTML::chars($body);die();
            } else {
                echo $body;die();
            }
        }
        $this->response->body(View::factory('wechat/test/index'));
    }
}