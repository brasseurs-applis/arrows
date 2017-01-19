<?php

use BrasseursApplis\Arrows\App\Doctrine\ResearcherIdType;
use BrasseursApplis\Arrows\App\Doctrine\ScenarioTemplateIdType;
use BrasseursApplis\Arrows\App\Doctrine\SessionIdType;
use BrasseursApplis\Arrows\App\Doctrine\SubjectIdType;
use BrasseursApplis\Arrows\App\Doctrine\UserIdType;
use BrasseursApplis\Arrows\App\Doctrine\SequenceCollectionType;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Doctrine\DBAL\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\Tools\Psr3SqlLogger;
use Monolog\Logger;
use Pimple\Container;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container([ 'debug' => false ]);

$container->register(
    new MonologServiceProvider(),
    [
        'monolog.logfile' => dirname(__DIR__) . '/log/app.log',
        'monolog.name'    => 'ARROWS',
        'monolog.level'   => Logger::DEBUG
    ]
);

$container->register(
    new DoctrineServiceProvider(),
    [
        'db.options' => [
            'driver'   => 'pdo_pgsql',
            'host'     => 'default',
            'dbname'   => 'arrows',
            'user'     => 'postgres',
            'password' => 'postgres'
        ],
    ]
);

$container->extend('db.config', function (Configuration $configuration) use ($container) {
    $configuration->setSQLLogger(new Psr3SqlLogger($container['logger']));

    return $configuration;
});

$container->register(
    new DoctrineOrmServiceProvider(),
    [
        'orm.proxies_dir' => 'cache/orm',
        'orm.em.options'  => [
            'mappings' =>
            [
                [
                    'type' => 'xml',
                    'namespace' => 'BrasseursApplis\Arrows\VO',
                    'path' => dirname(__DIR__) . '/config/doctrine/embed'
                ],
                [
                    'type' => 'xml',
                    'namespace' => 'BrasseursApplis\Arrows',
                    'path' => dirname(__DIR__) . '/config/doctrine/entity'
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

$container['orm.em'] = $container->extend('orm.em', function (EntityManager $em) {
    $platform = $em->getConnection()->getDatabasePlatform();

    $platform->registerDoctrineTypeMapping(ResearcherIdType::RESEARCHER_ID, ResearcherIdType::RESEARCHER_ID);
    $platform->registerDoctrineTypeMapping(ScenarioTemplateIdType::SCENARIO_TEMPLATE_ID, ScenarioTemplateIdType::SCENARIO_TEMPLATE_ID);
    $platform->registerDoctrineTypeMapping(SessionIdType::SESSION_ID, SessionIdType::SESSION_ID);
    $platform->registerDoctrineTypeMapping(SubjectIdType::SUBJECT_ID, SubjectIdType::SUBJECT_ID);
    $platform->registerDoctrineTypeMapping(UserIdType::USER_ID, UserIdType::USER_ID);

    $platform->registerDoctrineTypeMapping(SequenceCollectionType::SEQUENCE_COLLECTION, SequenceCollectionType::SEQUENCE_COLLECTION);

    return $em;
});

return ConsoleRunner::createHelperSet($container['orm.em']);