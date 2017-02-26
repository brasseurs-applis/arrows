<?php

namespace BrasseursApplis\Arrows\App\Controller\Security;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\Controller\Util\Paginator;
use BrasseursApplis\Arrows\App\DTO\UserDTO;
use BrasseursApplis\Arrows\App\Finder\UserFinder;
use BrasseursApplis\Arrows\App\Form\UserType;
use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Service\UserService;
use Doctrine\ORM\ORMInvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UserController
{
    const PAGE_CATEGORY = 'user';

    /** @var UserFinder */
    private $userFinder;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var UserService */
    private $userService;

    /** @var \Twig_Environment */
    private $twig;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * UserController constructor.
     *
     * @param UserFinder           $userFinder
     * @param FormFactoryInterface $formFactory
     * @param UserService          $userService
     * @param \Twig_Environment    $twig
     * @param UrlGenerator         $urlGenerator
     */
    public function __construct(
        UserFinder $userFinder,
        FormFactoryInterface $formFactory,
        UserService $userService,
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator
    ) {
        $this->userFinder = $userFinder;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->userService = $userService;
    }

    /**
     * @return RedirectResponse
     *
     * @throws \InvalidArgumentException
     */
    public function createAction()
    {
        return new RedirectResponse($this->urlGenerator->generate('user_edit', [ 'userId' => Uuid::uuid4() ]));
    }

    /**
     * @param Request $request
     * @param string  $userId
     *
     * @return Response
     *
     * @throws ORMInvalidArgumentException
     * @throws AssertionFailedException
     * @throws InvalidOptionsException
     * @throws RouteNotFoundException
     * @throws MissingMandatoryParametersException
     * @throws InvalidParameterException
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function editAction(Request $request, $userId)
    {
        $user = $this->userFinder->find($userId);
        $new = false;

        if ($user === null) {
            $user = new UserDTO($userId);
            $new = true;
        }

        $form = $this->formFactory->create(UserType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || ! $form->isValid()) {
            $response = new Response();
            $response->setContent(
                $this->twig->render(
                    'user/edit.twig',
                    [
                        'pageCategory' => self::PAGE_CATEGORY,
                        'form' => $form->createView(),
                        'userId' => (string) $userId
                    ]
                )
            );

            return $response;
        }

        /** @var UserDTO $user */
        $user = $form->getData();
        $domainUserId = new UserId($user->getId());

        if ($new) {
            $this->userService->createUser($domainUserId, $user->getUserName(), $user->getPassword(), $user->getRoles());
        } else {
            $this->userService->updateUser($domainUserId, $user->getPassword(), $user->getRoles());
        }

        return new RedirectResponse($this->urlGenerator->generate('user_list'));
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function listAction(Request $request)
    {
        $paginator = new Paginator($request);
        $pagination = $paginator->getPaginatedArray(function ($sort, $page, $elementsPerPage) {
            try {
                return $this->userFinder->getPaginatedUsers($sort, $page, $elementsPerPage);
            } catch (\OutOfBoundsException $e) {
                throw new NotFoundHttpException();
            }
        });

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'user/list.twig',
                array_merge([ 'pageCategory' => self::PAGE_CATEGORY ], $pagination->toArray())
            )
        );

        return $response;
    }
}
