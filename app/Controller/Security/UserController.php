<?php

namespace BrasseursApplis\Arrows\App\Controller\Security;

use BrasseursApplis\Arrows\App\DTO\UserDTO;
use BrasseursApplis\Arrows\App\Finder\UserFinder;
use BrasseursApplis\Arrows\App\Form\UserType;
use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Service\UserService;
use BrasseursApplis\Arrows\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class UserController
{
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
                    [ 'form' => $form->createView(), 'userId' => (string) $userId ]
                )
            );

            return $response;
        }

        /** @var User $user */
        $user = $form->getData();
        $userId = new UserId($user->getId());

        if ($new) {
            $this->userService->createUser($userId, $user->getUserName(), $user->getPassword(), $user->getRoles());
        } else {
            $this->userService->updateUser($userId, $user->getPassword(), $user->getRoles());
        }

        return new RedirectResponse($this->urlGenerator->generate('user_list'));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $page = $request->get('page') ? : 1;
        $elementsPerPage = $request->get('elements') ? : 20;
        $sortParamString = $request->get('sort') ? : '';
        $sort = array_reduce(explode(',', $sortParamString), function ($sortArray, $sortString) {
            if ($sortString === '') {
                return $sortArray;
            }

            $sortParameters = explode(':', $sortString);
            $orientation = isset($sortParameters[1]) ? strtoupper($sortParameters[1]) : 'ASC';
            if (! in_array($orientation, [ 'ASC', 'DESC' ])) {
                $orientation = 'ASC';
            }
            $sortArray[$sortParameters[0]] = $orientation;

            return $sortArray;
        }, []);

        try {
            $paginatedUsers = $this->userFinder->getPaginatedUsers($sort, $page, $elementsPerPage);
        } catch (\OutOfBoundsException $e) {
            throw new NotFoundHttpException();
        }

        $totalElements = $paginatedUsers->count();

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'user/list.twig',
                [
                    'users' => $paginatedUsers->getIterator()->getArrayCopy(),
                    'pagination' => [
                        'page' => $page,
                        'elements' => $elementsPerPage,
                        'sort' => $sortParamString,
                        'totalElements' => $totalElements,
                        'totalPages' => ceil($totalElements / $elementsPerPage)
                    ]
                ]
            )
        );

        return $response;
    }
}
