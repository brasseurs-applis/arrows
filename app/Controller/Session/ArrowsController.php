<?php

namespace BrasseursApplis\Arrows\App\Controller\Session;

use Symfony\Component\HttpFoundation\Response;

class ArrowsController
{
    /** @var \Twig_Environment */
    private $twig;

    /**
     * IndexController constructor.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     */
    public function observerAction($sessionId)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/observer.twig',
                [
                    'sessionId' => $sessionId
                ]
            )
        );

        return $response;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     */
    public function positionOneAction($sessionId)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/positionOne.twig',
                [
                    'sessionId' => $sessionId
                ]
            )
        );

        return $response;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     */
    public function positionTwoAction($sessionId)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/positionTwo.twig',
                [
                    'sessionId' => $sessionId
                ]
            )
        );

        return $response;
    }
}
