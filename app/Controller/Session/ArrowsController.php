<?php

namespace BrasseursApplis\Arrows\App\Controller\Session;

use BrasseursApplis\Arrows\App\DTO\SessionDTO;
use BrasseursApplis\Arrows\App\Finder\SessionFinder;
use BrasseursApplis\Arrows\App\Security\Voter\SessionVoter;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArrowsController
{
    const PAGE_CATEGORY = 'session';

    /** @var SessionFinder */
    private $sessionFinder;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var \Twig_Environment */
    private $twig;

    /** @var string */
    private $webSocketHost;

    /**
     * IndexController constructor.
     *
     * @param SessionFinder                 $sessionFinder
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param \Twig_Environment             $twig
     * @param string                        $webSocketHost
     */
    public function __construct(
        SessionFinder $sessionFinder,
        AuthorizationCheckerInterface $authorizationChecker,
        \Twig_Environment $twig,
        $webSocketHost
    ) {
        $this->sessionFinder = $sessionFinder;
        $this->authorizationChecker = $authorizationChecker;
        $this->twig = $twig;
        $this->webSocketHost = $webSocketHost;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     *
     * @throws AccessDeniedException
     * @throws ORMInvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function observerAction($sessionId)
    {
        /** @var SessionDTO $session */
        $session = $this->sessionFinder->find($sessionId);

        if (! $this->authorizationChecker->isGranted(SessionVoter::OBSERVE, $session)) {
            throw new AccessDeniedException();
        }

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/observer.twig',
                $this->getTwigParameters($session)
            )
        );

        return $response;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     *
     * @throws AccessDeniedException
     * @throws ORMInvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function positionOneAction($sessionId)
    {
        /** @var SessionDTO $session */
        $session = $this->sessionFinder->find($sessionId);

        if (! $this->authorizationChecker->isGranted(SessionVoter::RESPOND, $session)) {
            throw new AccessDeniedException();
        }

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/positionOne.twig',
                $this->getTwigParameters($session)
            )
        );

        return $response;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     *
     * @throws AccessDeniedException
     * @throws ORMInvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function positionTwoAction($sessionId)
    {
        /** @var SessionDTO $session */
        $session = $this->sessionFinder->find($sessionId);

        if (! $this->authorizationChecker->isGranted(SessionVoter::PREVIEW, $session)) {
            throw new AccessDeniedException();
        }

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/positionTwo.twig',
                $this->getTwigParameters($session)
            )
        );

        return $response;
    }

    /**
     * @param $session
     *
     * @return array
     */
    private function getTwigParameters($session)
    {
        return [
            'pageCategory' => self::PAGE_CATEGORY,
            'session' => $session,
            'webSocketHost' => $this->webSocketHost
        ];
    }
}
