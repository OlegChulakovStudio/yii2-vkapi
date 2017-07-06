<?php
namespace  OlegChulakovStudio\vkapi;

use VK\VK;
use yii\base\Component;

/**
 * Компонент для работы с API VK
 * @copyright Copyright (c) 2017, Oleg Chulakov Studio
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 *
 * @link http://chulakov.com/
 * @property-read VK|NULL $api [[getApi()]]
 */
class YiiVkComponent extends Component
{
    /**
     * Типа HTTP-запроса GET
     */
    const REQUEST_TYPE_GET = 'get';
    /**
     * Типа HTTP-запроса POST
     */
    const REQUEST_TYPE_POST = 'post';
    /**
     * Типа HTTP-запроса HEAD
     */
    const REQUEST_TYPE_HEAD = 'head';
    /**
     * Типа HTTP-запроса PUT
     */
    const REQUEST_TYPE_PUT = 'put';
    /**
     * Типа HTTP-запроса PATCH
     */
    const REQUEST_TYPE_PATCH = 'patch';
    /**
     * Типа HTTP-запроса DELETE
     */
    const REQUEST_TYPE_DELETE = 'delete';

    const ERROR_UNAVAILABLE = 1;
    const ERROR_APPLICATION_OFF = 2;
    const ERROR_NOT_FOUND_METHOD = 3;
    const ERROR_VALID_SIGN = 4;
    const ERROR_FAILED_AUTH = 5;
    const ERROR_MANY_REQUEST_IN_SEC = 6;
    const ERROR_FORBIDDEN = 7;
    const ERROR_VALID_REQUEST = 8;
    const ERROR_MANY_ACTIONS = 9;
    const ERROR_SERVER = 10;
    const ERROR_TEST_MODE_AUTH_ERROR = 11;
    const ERROR_CAPTCHA = 14;
    const ERROR_ACCESS_DENIED = 15;
    const ERROR_NEDD_HTTPS = 16;
    const ERROR_NEED_VALIDATION_USER = 17;
    const ERROR_PAGE_DELETED_OR_BLOCKED = 18;
    const ERROR_CLOSED_FOR_STANDALONE = 20;
    const ERROR_PERMITTED_FOR_STANDALONE_OR_OPEN_API = 21;
    const ERROR_REQUIRED_CONFIRM_OFUSER = 24;
    const ERROR_KEY_COMUNITY_VALID = 27;
    const ERROR_KEY_APPLICATION_VALID = 28;
    const ERROR_BAD_REQUEST = 100;
    const ERROR_VALID_APP_ID = 101;
    const ERROR_USER_ID = 113;
    const ERROR_TIMESTAMP = 150;
    const ERROR_ACCESS_DENIED_TO_ALBUM = 200;
    const ERROR_ACCESS_DENIED_TO_AUDIO = 201;
    const ERROR_ACCESS_DENIED_TO_GROUP = 203;
    const ERROR_ALBUM_IS_FULL = 300;
    const ERROR_ACCESS_DENIED_TRNSLATION_VOICE_NEED_ON = 500;
    const ERROR_ACCESS_DENIED_ADVERTISING_CABINET = 600;
    const ERROR_UNVAILABLE_IN_ADVERTISING_CABINET = 603;

    /**
     * Id приложения
     * @var string
     */
    public $clientId;

    /**
     * Секретынй ключ
     * @var string
     */
    public $secretCode;

    /**
     * Токен для запросов где требуется авторизация
     * @var string
     */
    public $accessToken;

    /**
     * Версия апи
     * @var string
     */
    public $apiVersion = '5.65';

    /**
     * Объект для работы с vk сервисом
     * @var VK
     */
    protected $_api;

    /**
     * Ошибки последнего запроса
     * @var array
     */
    protected $lastRequestErrors = [];

    /**
     * Получить объект апи
     * @return VK
     */
    protected function getApi()
    {
        if ($this->_api) {
            return $this->_api;
        }

        $this->_api = new VK($this->clientId, $this->secretCode, $this->accessToken);
        $this->_api->setApiVersion($this->apiVersion);

        return $this->_api;
    }

    /**
     * Сделать запрос к апи
     * @param $method
     * @param $parameters
     * @param string $requestMethod
     * @return array
     */
    public function request($method, $parameters, $requestMethod = 'get')
    {
        if (!in_array($requestMethod, self::getHttpTypes())) {
            throw new \yii\base\InvalidCallException("Неверный тип HTTP-запроса!");
        }

        $this->flushRequestErrors();
        $result = $this->api->api($method, $parameters, 'array', $requestMethod);

        if (isset($result['error'])) {
            $this->addRequestError($result['error']['error_code']);
            \Yii::error(var_export($result['error'], 1));
            return [];
        }

        return $result;
    }



    /**
     * Производит сброс ошибок запроса перед.
     * Вызывается перед непосредственным запросом к API
     */
    protected function flushRequestErrors()
    {
        $this->lastRequestErrors;
    }

    /**
     * Возвращает список ошибок (если они были) в виде массива с описанием
     * @return array
     */
    public function getRequestErrors()
    {
        return $this->lastRequestErrors;
    }

    /**
     * Возвращает список возможных HTTP-запросов
     * @return array
     */
    protected static function getHttpTypes()
    {
        return [
            static::REQUEST_TYPE_DELETE,
            static::REQUEST_TYPE_GET,
            static::REQUEST_TYPE_HEAD,
            static::REQUEST_TYPE_PATCH,
            static::REQUEST_TYPE_POST,
            static::REQUEST_TYPE_PUT
        ];
    }

    /**
     * Добавляет ошибку
     * @param $code
     */
    private function addRequestError($code)
    {
        $this->lastRequestErrors[$code] = static::getErrorMessage($code);
    }

    /**
     * Описания ошибок
     * @return array
     */
    public static function getErrorsDescription()
    {
        return [
            static::ERROR_UNAVAILABLE => 'Произошла неизвестная ошибка.',
            static::ERROR_APPLICATION_OFF  => 'Приложение выключено. ',
            static::ERROR_NOT_FOUND_METHOD => 'Передан неизвестный метод. ',
            static::ERROR_VALID_SIGN => 'Неверная подпись.',
            static::ERROR_FAILED_AUTH => 'Авторизация пользователя не удалась. ',
            static::ERROR_MANY_REQUEST_IN_SEC => 'Слишком много запросов в секунду. ',
            static::ERROR_FORBIDDEN => 'Нет прав для выполнения этого действия. ',
            static::ERROR_VALID_REQUEST => 'Неверный запрос. ',
            static::ERROR_MANY_ACTIONS => 'Слишком много однотипных действий. ',
            static::ERROR_SERVER => 'Произошла внутренняя ошибка сервера.',
            static::ERROR_TEST_MODE_AUTH_ERROR => 'В тестовом режиме приложение должно быть выключено или пользователь должен быть залогинен. ',
            static::ERROR_CAPTCHA => 'Требуется ввод кода с картинки (Captcha). ',
            static::ERROR_ACCESS_DENIED => 'Доступ запрещён. ',
            static::ERROR_NEDD_HTTPS => 'Требуется выполнение запросов по протоколу HTTPS, т.к. пользователь включил настройку, требующую работу через безопасное соединение. ',
            static::ERROR_NEED_VALIDATION_USER => 'Требуется валидация пользователя. ',
            static::ERROR_PAGE_DELETED_OR_BLOCKED => 'Страница удалена или заблокирована. ',
            static::ERROR_CLOSED_FOR_STANDALONE => 'Данное действие запрещено для не Standalone приложений. ',
            static::ERROR_PERMITTED_FOR_STANDALONE_OR_OPEN_API => 'Данное действие разрешено только для Standalone и Open API приложений.',
            static::ERROR_REQUIRED_CONFIRM_OFUSER => 'Требуется подтверждение со стороны пользователя.',
            static::ERROR_KEY_COMUNITY_VALID => 'Ключ доступа сообщества недействителен.',
            static::ERROR_KEY_APPLICATION_VALID => 'Ключ доступа приложения недействителен',
            static::ERROR_BAD_REQUEST => 'Один из необходимых параметров был не передан или неверен.',
            static::ERROR_VALID_APP_ID => 'Неверный API ID приложения. ',
            static::ERROR_USER_ID => 'Неверный идентификатор пользователя. ',
            static::ERROR_TIMESTAMP => 'Неверный timestamp',
            static::ERROR_ACCESS_DENIED_TO_ALBUM => 'Доступ к альбому запрещён. ',
            static::ERROR_ACCESS_DENIED_TO_AUDIO  => 'Доступ к аудио запрещён. ',
            static::ERROR_ACCESS_DENIED_TO_GROUP => 'Доступ к группе запрещён. ',
            static::ERROR_ALBUM_IS_FULL => 'Альбом переполнен. ',
            static::ERROR_ACCESS_DENIED_TRNSLATION_VOICE_NEED_ON => 'Действие запрещено. Вы должны включить переводы голосов в настройках приложения. ',
            static::ERROR_ACCESS_DENIED_ADVERTISING_CABINET => 'Нет прав на выполнение данных операций с рекламным кабинетом.',
            static::ERROR_UNVAILABLE_IN_ADVERTISING_CABINET => 'Произошла ошибка при работе с рекламным кабинетом.',
        ];
    }

    /**
     * Получить описание ошибки по коду
     * @param $code
     * @return mixed
     */
    public static function getErrorMessage($code)
    {
        $errors = self::getErrorsDescription();
        return isset($errors[$code]) ? $errors[$code] : $errors[static::ERROR_UNAVAILABLE];
    }
}
