<?php

namespace BrasseursApplis\Arrows\App\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    /** @var \Twig_Environment */
    private $twig;

    /**
     * IndexController constructor.
     *
     * @param \Twig_Environment     $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Response
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function indexAction()
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
