<?php

namespace BrasseursApplis\Arrows\App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionTwoController
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
    public function showAction($sessionId)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'positionTwo.twig',
                [
                    'sessionId' => $sessionId
                ]
            )
        );

        return $response;
    }
}
