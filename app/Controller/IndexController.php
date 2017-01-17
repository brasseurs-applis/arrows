<?php

namespace BrasseursApplis\Arrows\App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController
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
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'index.twig'
            )
        );

        return $response;
    }
}
