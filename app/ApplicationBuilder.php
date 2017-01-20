<?php

namespace BrasseursApplis\Arrows\App;

use BrasseursApplis\Arrows\App\Controller\IndexController;
use BrasseursApplis\Arrows\App\Controller\Session\ArrowsController;
use BrasseursApplis\Arrows\App\Doctrine\ResearcherIdType;
use BrasseursApplis\Arrows\App\Doctrine\ScenarioTemplateIdType;
use BrasseursApplis\Arrows\App\Doctrine\SequenceCollectionType;
use BrasseursApplis\Arrows\App\Doctrine\SessionIdType;
use BrasseursApplis\Arrows\App\Doctrine\SubjectIdType;
use BrasseursApplis\Arrows\App\Doctrine\UserIdType;
use BrasseursApplis\Arrows\App\Message\ArrowsMessageComponent;
use BrasseursApplis\Arrows\App\Repository\InMemory\InMemorySessionRepository;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Id\SubjectId;
use BrasseursApplis\Arrows\Session;
use BrasseursApplis\Arrows\VO\Orientation;
use BrasseursApplis\Arrows\VO\Position;
use BrasseursApplis\Arrows\VO\Scenario;
use BrasseursApplis\Arrows\VO\Sequence;
use BrasseursApplis\Arrows\VO\SequenceCollection;
use BrasseursApplis\Arrows\VO\SubjectsCouple;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\DBAL\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\Tools\Psr3SqlLogger;
use Monolog\Logger;
use Pimple\Container;
use Ramsey\Uuid\Uuid;
use Ratchet\App;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;

class ApplicationBuilder
{
    /** @var Application */
    private $application;

    /** @var ApplicationConfig */
    private $config;

    /**
     * ApplicationBuilder constructor.
     *
     * @param ApplicationConfig $config
     *
     * @internal param bool $debug
     */
    public function __construct(ApplicationConfig $config)
    {
        $this->application = new Application(['debug' => $config->isDebug()]);

        $this->config = $config;

        $this->log(
            $config->getLogFilePath(),
            $config->getLogName(),
            $config->getLogLevel()
        );

        $this->orm(
            $config->getDbConnectionOptions(),
            $config->getOrmMappingsFilePath(),
            $config->getOrmCacheFilePath()
        );

        $this->domain();
    }

    /**
     * @return Application
     */
    public function httpApplication()
    {
        $this->web($this->config->getViewsFilePath());

        return $this->application;
    }

    /**
     * @return App
     */
    public function webSocketServer()
    {
        $this->socket($this->config->getSocketHost(), $this->config->getSocketHost());

        return $this->application['socket.application'];
    }

    /**
     * @return Container
     */
    public function container()
    {
        return $this->application;
    }

    /**
     * @param string $filePath
     * @param string $name
     * @param int    $level
     */
    private function log($filePath, $name, $level = Logger::DEBUG)
    {
        $this->application->register(
            new MonologServiceProvider(),
            [
                'monolog.logfile' => $filePath,
                'monolog.name'    => $name,
                'monolog.level'   => $level
            ]
        );
    }

    /**
     * @param array  $databaseOptions
     * @param string $mappingPath
     * @param string $proxyPath
     */
    private function orm(array $databaseOptions, $mappingPath, $proxyPath)
    {
        $this->application->register(
            new DoctrineServiceProvider(),
            [
                'db.options' => $databaseOptions
            ]
        );

        $this->application->extend('db.config', function (Configuration $configuration) {
            $configuration->setSQLLogger(new Psr3SqlLogger($this->application['logger']));

            return $configuration;
        });

        $this->application->register(
            new DoctrineOrmServiceProvider(),
            [
                'orm.proxies_dir' => $proxyPath,
                'orm.em.options'  => [
                    'mappings' =>
                        [
                            [
                                'type' => 'xml',
                                'namespace' => 'BrasseursApplis\Arrows\VO',
                                'path' => $mappingPath . '/embed'
                            ],
                            [
                                'type' => 'xml',
                                'namespace' => 'BrasseursApplis\Arrows',
                                'path' => $mappingPath . '/entity'
                            ]
                        ],
                    'types' => [
                        ResearcherIdType::RESEARCHER_ID => ResearcherIdType::class,
                        ScenarioTemplateIdType::SCENARIO_TEMPLATE_ID => ScenarioTemplateIdType::class,
                        SessionIdType::SESSION_ID => SessionIdType::class,
                        SubjectIdType::SUBJECT_ID => SubjectIdType::class,
                        UserIdType::USER_ID => UserIdType::class,

                        SequenceCollectionType::SEQUENCE_COLLECTION => SequenceCollectionType::class
                    ]
                ],
            ]
        );

        $this->application['orm.em'] = $this->application->extend('orm.em', function (EntityManager $em) {
            $platform = $em->getConnection()->getDatabasePlatform();

            $platform->registerDoctrineTypeMapping(ResearcherIdType::RESEARCHER_ID, ResearcherIdType::RESEARCHER_ID);
            $platform->registerDoctrineTypeMapping(ScenarioTemplateIdType::SCENARIO_TEMPLATE_ID, ScenarioTemplateIdType::SCENARIO_TEMPLATE_ID);
            $platform->registerDoctrineTypeMapping(SessionIdType::SESSION_ID, SessionIdType::SESSION_ID);
            $platform->registerDoctrineTypeMapping(SubjectIdType::SUBJECT_ID, SubjectIdType::SUBJECT_ID);
            $platform->registerDoctrineTypeMapping(UserIdType::USER_ID, UserIdType::USER_ID);

            $platform->registerDoctrineTypeMapping(SequenceCollectionType::SEQUENCE_COLLECTION, SequenceCollectionType::SEQUENCE_COLLECTION);

            return $em;
        });
    }

    private function domain()
    {
        // TODO add real impl

        $session = new Session(
            new SessionId('ddf5ddfa-0990-4c30-9c4c-db2214ed06c1'),
            new Scenario(
                new SequenceCollection(
                    [
                        new Sequence(
                            Position::top(),
                            Orientation::left(),
                            Orientation::right(),
                            Orientation::left()
                        ),
                        new Sequence(
                            Position::top(),
                            Orientation::right(),
                            Orientation::right(),
                            Orientation::left()
                        ),
                        new Sequence(
                            Position::top(),
                            Orientation::left(),
                            Orientation::right(),
                            Orientation::right()
                        ),
                        new Sequence(
                            Position::top(),
                            Orientation::left(),
                            Orientation::left(),
                            Orientation::right()
                        )
                    ]
                )
            ),
            new SubjectsCouple(new SubjectId(Uuid::uuid4()), new SubjectId(Uuid::uuid4())),
            new ResearcherId(Uuid::uuid4())
        );
        $sessionRepository = new InMemorySessionRepository();
        $sessionRepository->persist($session);

        $this->application['arrows.session.repository'] = function () use ($sessionRepository) {
            return $sessionRepository;
        };
    }

    /**
     * @param string $twigPath
     */
    private function web($twigPath)
    {
        $this->application->register(new ServiceControllerServiceProvider());
        $this->application->register(new TwigServiceProvider(), [ 'twig.path' => $twigPath ]);

        $this->application['index.controller'] = function() {
            return new IndexController($this->application['twig']);
        };

        $this->application['arrows.controller'] = function() {
            return new ArrowsController($this->application['twig']);
        };

        $this->application->get('/', 'index.controller:indexAction');
        $this->application->get('/session/{sessionId}/observer', 'arrows.controller:observerAction');
        $this->application->get('/session/{sessionId}/one', 'arrows.controller:positionOneAction');
        $this->application->get('/session/{sessionId}/two', 'arrows.controller:positionTwoAction');
    }

    /**
     * @param string $httpHost
     * @param int    $port
     */
    private function socket($httpHost, $port)
    {
        $this->application['socket.arrows.message.component'] = function() {
            return new ArrowsMessageComponent($this->application['arrows.session.repository']);
        };

        $this->application['socket.application'] = function () use ($httpHost, $port) {
            $application = new App($httpHost, $port);

            $application->route(
                '/arrows/{sessionId}/{role}',
                $this->application['socket.arrows.message.component'],
                ['*']
            );

            return $application;
        };
    }
}
