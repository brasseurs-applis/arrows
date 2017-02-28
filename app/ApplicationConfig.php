<?php

namespace BrasseursApplis\Arrows\App;

use Assert\Assertion;
use Assert\AssertionFailedException;

class ApplicationConfig
{
    /** @var bool */
    private $debug;


    /** @var string */
    private $logFilePath;

    /** @var string */
    private $logName;

    /** @var int */
    private $logLevel;


    /** @var string[] */
    private $dbConnectionOptions;

    /** @var string */
    private $ormMappingsFilePath;

    /** @var string */
    private $ormCacheFilePath;


    /** @var string */
    private $jwtKey;


    /** @var string */
    private $viewsFilePath;


    /** @var string */
    private $socketHost;

    /** @var int */
    private $socketPort;


    /**
     * ApplicationConfig constructor.
     *
     * @throws AssertionFailedException
     */
    public function __construct()
    {
        $configArray = self::getConfigArray();

        $this->debug = $configArray['debug'];

        $this->logFilePath = $configArray['log']['file'];
        $this->logName     = $configArray['log']['name'];
        $this->logLevel    = $configArray['log']['level'];

        $this->dbConnectionOptions = $configArray['db'];
        $this->ormMappingsFilePath = dirname(__DIR__) . '/config/doctrine';
        $this->ormCacheFilePath = dirname(__DIR__) . '/cache/orm';

        $this->jwtKey = $configArray['jwtKey'];

        $this->viewsFilePath = dirname(__DIR__) . '/views';

        $this->socketHost = $configArray['websocket']['host'];
        $this->socketPort = $configArray['websocket']['port'];
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @return string
     */
    public function getLogFilePath()
    {
        return $this->logFilePath;
    }

    /**
     * @return string
     */
    public function getLogName()
    {
        return $this->logName;
    }

    /**
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @return string[]
     */
    public function getDbConnectionOptions()
    {
        return $this->dbConnectionOptions;
    }

    /**
     * @return string
     */
    public function getOrmMappingsFilePath()
    {
        return $this->ormMappingsFilePath;
    }

    /**
     * @return string
     */
    public function getOrmCacheFilePath()
    {
        return $this->ormCacheFilePath;
    }

    /**
     * @return string
     */
    public function getJwtKey()
    {
        return $this->jwtKey;
    }

    /**
     * @return string
     */
    public function getViewsFilePath()
    {
        return $this->viewsFilePath;
    }

    /**
     * @return string
     */
    public function getSocketHost()
    {
        return $this->socketHost;
    }

    /**
     * @return int
     */
    public function getSocketPort()
    {
        return $this->socketPort;
    }

    /**
     * @return array
     * @throws AssertionFailedException
     */
    private static function getConfigArray()
    {
        $configArray = require dirname(__DIR__) . '/config/app-config.php';

        Assertion::keyExists($configArray, 'debug');

        Assertion::keyExists($configArray, 'log');
        Assertion::keyExists($configArray['log'], 'file');
        Assertion::keyExists($configArray['log'], 'name');
        Assertion::keyExists($configArray['log'], 'level');

        Assertion::keyExists($configArray, 'db');
        Assertion::keyExists($configArray['db'], 'driver');
        Assertion::keyExists($configArray['db'], 'host');
        Assertion::keyExists($configArray['db'], 'dbname');
        Assertion::keyExists($configArray['db'], 'user');
        Assertion::keyExists($configArray['db'], 'password');

        Assertion::keyExists($configArray, 'jwtKey');

        Assertion::keyExists($configArray, 'websocket');
        Assertion::keyExists($configArray['websocket'], 'host');
        Assertion::keyExists($configArray['websocket'], 'port');

        return $configArray;
    }
}
