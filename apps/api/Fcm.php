<?php

use Zttp\Zttp;

use Helpers\Fcm as FcmUtil;
use Helpers\Util;

/**
 * 网络游戏防沉迷实名认证系统 后端接口DEMO
 * Class Fcm
 */
class Fcm extends Base
{
    protected static self $instance;
    protected string $env;
    protected array $header_data = [];

    public function __construct()
    {
        parent::__construct();

        $this->env = strtolower($_ENV['APP_ENV']);
        $this->header_data = [
            'Content-Type' => 'application/json; charset=utf-8',
            'appId' => $_ENV['FCM_APPID'],
            'bizId' => $_ENV['FCM_BIZID'],
            'timestamps' => $this->timestamp,
            'sign' => '',
        ];
    }

    /**
     * Get the instance of self.
     *
     * @return self
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 实名认证提交
     */
    public function idcard_check()
    {
        $api_data = [
            'ai' => $this->reqData['ai'] ?? '',
            'name' => $this->reqData['name'] ?? '',
            'idNum' => $this->reqData['idNum'] ?? '',
        ];
        dump($api_data);exit;

        if (empty($api_data['ai'])) {
            Util::jsonReturn(400, '参数 ai 不能为空');
        }

        if (empty($api_data['name'])) {
            Util::jsonReturn(400, '参数 name 不能为空');
        }

        if (empty($api_data['idNum'])) {
            Util::jsonReturn(400, '参数 idNum 不能为空');
        }

        $api_key = $_ENV['FCM_IDCARD_CHECK_KEY'];
        $urls = [
            'production' => 'https://xxx' . $api_key,
            'dev' => 'https://xxx' . $api_key,
        ];

        $response = Zttp::timeout(60)->withHeaders($this->header_data)->post($urls[$this->env]);

        $status = $response->status();
        $is_ok = $response->isOk();

        if ($status != 200 or !$is_ok) {
            Util::jsonReturn(401, MSG_CODE[401]);
        }

        $json = $response->json();

        if ($json['errcode'] != 0) {
            Util::jsonReturn(400, MSG_CODE[400], $json);
        }

        Util::jsonReturn(200, MSG_CODE[200], $json['data']['result']);

//        {
//            "code": 200,
//            "msg": "request successed.",
//            "data": {
//                "status": 2,
//                "pi": null
//            }
//        }
    }
}
