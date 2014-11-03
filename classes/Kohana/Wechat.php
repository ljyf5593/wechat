<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Wechat {

    /**
     * 微信对象单例
     * @var Wechat
     */
    protected static $instance = NULL;

    /**
     * 微信相关配置文件
     * @var array
     */
    private $config = array();

    /**
     * 请求的api地址
     * @var string
     */
    protected $api_url = 'https://api.weixin.qq.com/cgi-bin';

    /**
     * 单例
     * @return null|Wechat
     */
    public static function instance() {
        if(self::$instance instanceof self){

        } else {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 验证微信签名
     * @param $param
     * @return bool
     */
    public function check_signature($param){
        $signature = $param['signature'];
        $signature_data = array($param['nonce'], $param['timestamp'], 'ijiaolian');
        sort($signature_data, SORT_STRING);
        return sha1(implode($signature_data)) == $signature;
    }

    /**
     * 获取access_token
     * @param $force 是否强制获取
     * @return bool
     */
    protected function get_token($force = FALSE){
        if(empty($this->config)){
            $this->config = Kohana::$config->load('wechat');
        }

        $time = $_SERVER['REQUEST_TIME'];

        // token 合法
        $token_correct = (!$force AND isset($this->config['token']['access_token']) AND isset($this->config['token']['expires_in']) AND $this->config['token']['expires_in'] AND $this->config['token']['expires_in'] > $time);

        if(! $token_correct){
            $response_data = Request::factory($this->api_url.'/token')->query(array(
                'grant_type' => 'client_credential',
                'appid' => $this->config['appid'],
                'secret' => $this->config['secret'],
            ))->execute();
            $response = json_decode($response_data, TRUE);
            if(isset($response['access_token'])){
                $this->config['token']['access_token'] = $response['access_token'];
                $this->config['token']['expires_in'] = $time + $response['expires_in'];
                $config_file = APPPATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'wechat.php';
                $config = array(
                    'appid' => $this->config['appid'],
                    'secret' => $this->config['secret'],
                    'token' => $this->config['token'],
                );
                file_put_contents($config_file, "<?php\nreturn ".var_export($config, TRUE).';');

            } else {
                $this->log($response);
                return FALSE;
            }
        }

        return $this->config['token']['access_token'];
    }

    /**
     * 获取信息追踪
     * @param $data
     */
    protected function log($data){
        Kohana::$log->add(Log::NOTICE, "wechat debug info: :errcode -- :errmsg", array(':errcode' => $data['errcode'], ':errmsg' => $data['errmsg']));
    }
}