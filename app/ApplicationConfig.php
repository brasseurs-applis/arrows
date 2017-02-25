<?php

namespace BrasseursApplis\Arrows\App;

use Monolog\Logger;

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
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;

        $this->logFilePath = dirname(__DIR__) . '/log/app.log';
        $this->logName = 'ARROWS';
        $this->logLevel = Logger::DEBUG;

        $this->dbConnectionOptions = [
            'driver'   => 'pdo_pgsql',
            'host'     => 'postgres',
            'dbname'   => 'arrows',
            'user'     => 'postgres',
            'password' => 'postgres'
        ];
        $this->ormMappingsFilePath = dirname(__DIR__) . '/config/doctrine';
        $this->ormCacheFilePath = dirname(__DIR__) . '/cache/orm';

        $this->jwtKey = 'myKey';

        $this->viewsFilePath = dirname(__DIR__) . '/views';

        $this->socketHost = 'wss.entartage.local';
        $this->socketPort = 1337;
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
}
