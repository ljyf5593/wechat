<?php
/**
 * 发送被动响应消息
 */ 
class Wechat_Response extends Wechat {

    /**
     * 收到的来源用户
     * @var string
     */
    private $from_user;

    /**
     * 目标用户
     * 开发者微信账号
     * @var string
     */
    private $to_user;

    private $message_id;

    /**
     * 请求类型
     * @var string text|image|location|link|event
     */
    private $request_type;

    /**
     * 解析后的请求数据
     * @var array
     */
    private $request_data = array();

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
     * 根据请求内容返回对应的响应信息
     */
    public function execute(Model_Wechat $wechat, $request = NULL){
        $this->parse_request($request);
        switch ($this->request_type) {
            case 'text': // 文本信息
                return $wechat->response_text($this, $this->request_data['content']);
                break;
            case 'event': // 事件
                switch ($this->request_data['event']) {
                    case 'subscribe': // 订阅事件
                        return $wechat->response_subscribe($this);
                        break;
                    case 'unsubscribe': // 取消订阅事件
                        break;
                    case 'SCAN': // 扫描二维码事件
                        break;
                    case 'LOCATION': // 上报地理位置事件
                        break;
                    case 'CLICK': // 自定义菜单点击事件 [必须大写]
                        // 响应菜单的点击事件
                        return $this->response_click();
                        break;
                    case 'VIEW': // 点击菜单跳转链接事件
                        break;
                    default:
                        # code...
                        break;
                }
                break;
            case 'image': // 图片信息
            case 'location': // 位置信息
            case 'link': // 链接信息
            case 'voice': // 语音事件
            default:
                
                break;
        }
    }

    /**
     * 解析微信post的数据
     * @param $request
     */
    private function parse_request($request){
        $request_object = simplexml_load_string($request, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->from_user = $request_object->FromUserName;
        $this->to_user = $request_object->ToUserName;
        $this->message_id = $request_object->Msgid;
        $this->request_type = $request_object->MsgType;

        $parse_method = 'parse_'.$request_object->MsgType;
        $this->{$parse_method}($request_object);
    }

    /**
     * 响应用户的点击事件
     * @return xml
     */
    private function response_click(){
        $event_key = $this->request_data['event_key'];
        if(strpos($event_key, '_') !== FALSE){
            list($event_type, $event_value) = explode('_', $this->request_data['event_key']);
        } else {
            $event_type = $event_key;
        }

        return $event_key;
    }



    /**
     * 解析文本消息
     * @param $request_object
     */
    private function parse_text($request_object){
        $this->request_data['content'] = trim($request_object->Content);
    }

    /**
     * 解析图片消息
     * @param $request_object
     */
    private function parse_image($request_object){
        $this->request_data['pic_url'] = trim($request_object->PicUrl);
    }

    /**
     * 解析地理位置信息
     * @param $request_object
     */
    private function parse_location($request_object){
        $this->request_data['location']['x'] = $request_object->Location_X;
        $this->request_data['location']['y'] = $request_object->Location_Y;
        $this->request_data['location']['scale'] = $request_object->Scale;
        $this->request_data['location']['label'] = $request_object->Label;
    }

    /**
     * 解析连接信息
     * @param $request_object
     */
    private function parse_link($request_object){
        $this->request_data['link']['title'] = $request_object->Title;
        $this->request_data['link']['description'] = $request_object->Description;
        $this->request_data['link']['url'] = $request_object->Url;
    }

    /**
     * 解析事件信息
     * @param $request_object
     */
    private function parse_event($request_object){
        $this->request_data['event'] = $request_object->Event;
        $this->request_data['event_key'] = $request_object->EventKey;
    }

    /**
     * 推送文本消息
     * @param $content
     * @return string
     */
    public function reply_text($content){
        return <<<XML
        <xml>
            <ToUserName><![CDATA[{$this->from_user}]]></ToUserName>
            <FromUserName><![CDATA[{$this->to_user}]]></FromUserName>
            <CreateTime>{$_SERVER['REQUEST_TIME']}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[{$content}]]></Content>
        </xml>
XML;
    }

    /**
     * 推送音乐信息
     * @param $title
     * @param $description
     * @param $music_url
     * @param $hq_music_url
     * @return string
     */
    private function reply_music($title, $description, $music_url, $hq_music_url){
        return <<<XML
        <xml>
            <ToUserName><![CDATA[{$this->from_user}]]></ToUserName>
            <FromUserName><![CDATA[{$this->to_user}]]></FromUserName>
            <CreateTime>{$_SERVER['REQUEST_TIME']}</CreateTime>
            <MsgType><![CDATA[music]]></MsgType>
            <Music>
                <Title><![CDATA[{$title}]]></Title>
                <Description><![CDATA[{$description}]]></Description>
                <MusicUrl><![CDATA[{$music_url}]]></MusicUrl>
                <HQMusicUrl><![CDATA[{$hq_music_url}]]></HQMusicUrl>
            </Music>
        </xml>
XML;
    }

    /**
     * 推送图文消息
     * @param array $articles
     * @return string
     */
    public function reply_news(array $news){
        $article_xml = '<ArticleCount>'.count($news).'</ArticleCount>';
        $article_xml .= '<Articles>';
        foreach($news as $item){
            $article_xml .= <<<ITEM
            <item>
                <Title><![CDATA[{$item['title']}]]></Title>
                <Description><![CDATA[{$item['description']}]]></Description>
                <PicUrl><![CDATA[{$item['pic_url']}]]></PicUrl>
                <Url><![CDATA[{$item['url']}]]></Url>
            </item>
ITEM;

        }
        $article_xml .= '</Articles>';

        return <<<XML
        <xml>
            <ToUserName><![CDATA[{$this->from_user}]]></ToUserName>
            <FromUserName><![CDATA[{$this->to_user}]]></FromUserName>
            <CreateTime>{$_SERVER['REQUEST_TIME']}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            {$article_xml}
        </xml>
XML;
    }
}