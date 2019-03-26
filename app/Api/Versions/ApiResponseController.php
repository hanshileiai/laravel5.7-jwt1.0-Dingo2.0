<?php
/**
 * Created by PhpStorm.
 * User: shilei
 * Date: 2018/11/12
 * Time: 15:56
 */

namespace App\Api\Versions;


use Illuminate\Routing\Controller;

/**
 * Class ApiResponseController
 * @package Api\Version
 */
class ApiResponseController extends Controller
{
    /**
     * error_code: 返回状态码
     */
    const CODE_SUCCESS = 10000;
    const CODE_LOGIN_EXCEPTION = 10001;
    const CODE_OFFLINE = 10003;
    const CODE_NOT_OBJECT = 10009;
    const CODE_NOT_ENOUGH_PARAM = 10010;
    const CODE_TOKEN_EXPIRED = 10011;
    const CODE_ALREADY_OPERATED = 10012;
    const CODE_NO_PERMISSION = 10013;
    const CODE_TOAST_MESSAGE = 20000;
    const CODE_INTERNAL_ERROR = 50000;

    /**
     * 默认 message 信息
     * @var array
     */
    const CODE_MESSAGES = [
        self::CODE_SUCCESS          => 'Success',
        self::CODE_LOGIN_EXCEPTION  => 'Error: Login Exception!',
        self::CODE_OFFLINE          => 'You are offline!',
        self::CODE_NOT_OBJECT       => 'No object found!',
        self::CODE_NOT_ENOUGH_PARAM => 'Error: Not enough parameters!',
        self::CODE_TOKEN_EXPIRED    => 'Invalid token!',
        self::CODE_ALREADY_OPERATED => 'Unable to repeat the action!',
        self::CODE_NO_PERMISSION    => 'No permission to do that!',
        self::CODE_TOAST_MESSAGE    => 'Toast message!',
        self::CODE_INTERNAL_ERROR   => 'Error: Internal Error!',
    ];

    /**
     * message: 自定义message信息
     * @var array
     */
    const RESPONSE_MESSAGES = [
        // Exception 异常信息
        'NOT_ENOUGH_PARAM'       => 'Error: Login Exception! [1]',  // 参数不足
        'TOKEN_INVALID'          => 'Error: Login Exception! [2]',  // token 无效
        'NOT_EQ_UUID_BASIC'      => 'Error: Login Exception! [3]',  // uuid和basic不匹配
        'NOT_EQ_TOKEN_UUID'      => 'Error: Login Exception! [4]',  // token和uuid不匹配
        'HEADER_ERROR'           => 'Error: Login Exception! [5]',  // http头缺少对应参数
        'LOGIN_FAILd'            => 'Error: Login Exception! [6]',  // 登录失败
        'USER_NOT_FOUND'         => 'Error: Login Exception! [7]',  // 用户没有找到

        // NO_PERMISSION 没有权限操作
        'CAN_NOT_LIKE_ANSWER'    => 'Sorry, you can\'t like your answer.',
        'CAN_NOT_DISLIKE_ANSWER' => 'Sorry, you can\'t dislike your answer.',
        'CAN_NOT_REPLY_ANSWER'   => 'Sorry, you can\'t reply to your answer.',

        // ALREADY_OPERATED 已操作过（重复动作）
        'ALREADY_LIKED'          => 'You have already liked.',
        'ALREADY_DISLIKED'       => 'You have already disliked.',
        'ALREADY_MASTED'         => 'You have already mastered.',
        'ALREADY_FOLLOWED'       => 'You have already followed.',


    ];


    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $data;

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * make 返回api格式化数据
     * @return array
     */
    public function make()
    {
        return [
            'error_code' => $this->errorCode,
            'message'    => $this->message,
            'data'       => $this->data,
        ];
    }

    public function __toString()
    {
        return json_encode($this->make());
    }

    /**
     * setAttributes 设置属性数据
     * @param $data
     * @param $errorCode
     * @param $message
     * @return array
     */
    public function setAttributes($data, $errorCode, $message)
    {
        $this->setErrorCode($errorCode);
        $this->setMessage(empty($message) ? self::CODE_MESSAGES[(int)$errorCode] : $message);
        $this->setData($data);

        return $this->make();
    }

    /**
     * responseSuccess 数据正常
     * @param $data
     * @param string $message
     * @return array
     */
    public function responseSuccess($data = null, $message = '', $objFlag = false)
    {
        $this->setAttributes($data, self::CODE_SUCCESS, $message);
        if( $objFlag ){
            return response($this->make());
        }
        return $this->make();
    }

    /**
     * responseLoginException 异常处理
     * @param string $message
     * @return array
     */
    public function responseLoginException($message = '')
    {
        $this->setAttributes(null, self::CODE_LOGIN_EXCEPTION, $message);

        return $this->make();
    }

    /**
     * responseOffline 用户掉线
     * @param string $message
     * @return array
     */
    public function responseOffline($message = '')
    {
        $this->setAttributes(null, self::CODE_OFFLINE, $message);

        return $this->make();
    }

    /**
     * responseNotObject 数据对象没找到
     * @param string $message
     * @return array
     */
    public function responseNotObject($message = '')
    {
        $this->setAttributes(null, self::CODE_NOT_OBJECT, $message);

        return $this->make();
    }

    /**
     * responseNotEnoughParam 参数不足
     * @param string $message
     * @return array
     */
    public function responseNotEnoughParam($message = '')
    {
        $this->setAttributes(null, self::CODE_NOT_ENOUGH_PARAM, $message);

        return $this->make();
    }

    /**
     * responseTokenExpired token 过期,无效
     * @param string $message
     * @return array
     */
    public function responseTokenExpired($message = '')
    {
        $this->setAttributes(null, self::CODE_TOKEN_EXPIRED, $message);

        return $this->make();
    }

    /**
     * responseNoPermission 没有权限操作
     * @param string $message
     * @return array
     */
    public function responseNoPermission($message = '')
    {
        $this->setAttributes(null, self::CODE_NO_PERMISSION, $message);

        return $this->make();
    }

    /**
     * responseAlreadyOperated 已操作过（重复动作）
     * @param string $message
     * @return array
     */
    public function responseAlreadyOperated($message = '')
    {
        $this->setAttributes(null, self::CODE_ALREADY_OPERATED, $message);

        return $this->make();
    }

    /**
     * responseToastMessage 附加状态，用来 toast 操作
     * @param string $message
     * @return array
     */
    public function responseToastMessage($message = '')
    {
        $this->setAttributes(null, self::CODE_TOAST_MESSAGE, $message);

        return $this->make();
    }

    /**
     * responseInternalError try catch 内部错误
     * @param string $message
     * @return array
     */
    public function responseInternalError($message = '')
    {
        $this->setAttributes(null, self::CODE_INTERNAL_ERROR, $message);

        return $this->make();
    }
}