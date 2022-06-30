<?php
namespace MrStock\Business\ServiceSdk\MessageService;

use MrStock\Business\ServiceSdk\MessageService\Exceptions\InException;
use \Httpful\Request;

/**
* Http的请求方式
*/
class GMessage
{
	//请求域名
	Const BASE_URL = 'http://message.guxiansheng.cn/index.php?';
	protected $uri;
	protected $client;

	/**
	* 构造函数初始化请求路径
	*/
	public function __construct($client,$resourse)
	{
		$this->uri = self::BASE_URL.$resourse;
		$this->client = $client;
	}

	/**
     * GET（SELECT）
     * @param string $uri 请求资源
     * @param string $mime 期望数据返回类型
     * @param array $body 请求body数据
     * @param string $mime 返回数据类型
     * @return object
     */
    public function get($uri, array $body = [], $mime = 'json')
    {
        if(!empty($body)){
            $query_string = http_build_query($body);
            $uri .= '?'.$query_string;
        }

        $request = Request::get($uri, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * POST（CREATE）
     * @param string $uri 请求资源
     * @param array|null $body body数据
     * @param string $mime 期望返回数据类型
     * @return object
     */
    public function post($uri, array $body = null, $mime = 'json')
    {
        $request = Request::post($uri, $body, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * PUT（UPDATE）
     * @param string $uri 请求资源地址
     * @param array|null $body
     * @param string $mime
     * @return object
     */
    public function put($uri, array $body = null, $mime = 'json')
    {
        $request = Request::put($uri, $body, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * DELETE（DELETE）
     * @param string $uri 请求资源地址
     * @param string $mime 期望返回数据类型
     * @return object
     */
    public function delete($uri, $mime = 'json')
    {
        $request = Request::delete($uri, $mime);
        $response = $this->requestSend($request);
        return $response;
    }

    /**
     * 统一send出口，公共请求头信息添加
     * @param object $request Request
     * @return object
     */
    private function requestSend($request)
    {
        $request->addHeaders($this->client->getAuth());
        try{
            $response = $request->send();
            $result = $this->processResp($response);
            return $result;
        }catch(\Exception $e){
            $result = [
                'body' =>[
                    'code' => -1,
                    'message' => $e->getMessage()
                ]
            ];
            return $result;
        }
    }

    /**
     * 请求返回处理
     * @param object $response 响应信息
     * @return array
     * @throws APIRequestException
     * @throws ServiceNotAvaliable
     */
    public function processResp($response) {
        $data = $response->body;
        if ($response->code === 200) {
            $result = [];
            $result['body'] = $data;
            $result['http_code'] = $response->code;
            $result['headers'] = $response->headers;
            return $result;
        } elseif (is_null($data)) {
            throw new \Exception("Resources can not be empty", $response);
        } else {
            throw new \Exception($data->message);
        }
    }
}