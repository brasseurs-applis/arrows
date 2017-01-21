<?php

namespace BrasseursApplis\Arrows\App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
