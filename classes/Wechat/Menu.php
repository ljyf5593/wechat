<?php 
class Wechat_Menu extends Wechat {
    /**
     * 创建菜单
     * @param $menu
     */
    public function create_menu($menu){
        $token = $this->get_token();
        if($token){
            $response_data = Request::factory($this->api_url.'/menu/create')
                ->query(array(
                    'access_token' => $token,
                ))
                ->method(HTTP_Request::POST)
                ->body($menu)
                ->execute();
            $response = json_decode($response_data, TRUE);
            if($response['errcode'] > 0){
                if($response['errcode'] == '40001'){
                    $this->get_token(TRUE);
                }
                $this->log($response);
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * 菜单删除
     * @return bool
     */
    public function delete_menu() {
        $token = $this->get_token();
        if($token){
            $response_data = Request::factory($this->api_url.'/menu/delete')
                ->query(array(
                    'access_token' => $token,
                ))->execute();
            $response = json_decode($response_data, TRUE);
            if($response['errcode'] > 0){
                if($response['errcode'] == '40001'){
                    $this->get_token(TRUE);
                }
                $this->log($response);
                return FALSE;
            }

            return TRUE;
        }

        return FALSE;
    }

    /**
     * 获取菜单
     */
    public function get_menu(){
        $token = $this->get_token();
        if($token){
            $response_data = Request::factory($this->api_url.'/menu/get')
                ->query(array(
                    'access_token' => $token,
                ))->execute();

            return $response_data;
        }
    }
}