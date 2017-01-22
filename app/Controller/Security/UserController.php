<?php

namespace BrasseursApplis\Arrows\App\Controller\Security;

use BrasseursApplis\Arrows\App\Form\Type\UserType;
use BrasseursApplis\Arrows\App\Form\UserForm;
use BrasseursApplis\Arrows\Id\UserId;
use BrasseursApplis\Arrows\Repository\UserRepository;
use BrasseursApplis\Arrows\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserController
{
    /** @var PasswordEncoderInterface */
    private $passwordEncoder;

    /** @var UserRepository */
    private $userRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var \Twig_Environment */
    private $twig;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * UserController constructor.
     *
     * @param PasswordEncoderInterface $passwordEncoder
     * @param UserRepository           $userRepository
     * @param FormFactoryInterface     $formFactory
     * @param \Twig_Environment        $twig
     * @param UrlGenerator             $urlGenerator
     */
    public function __construct(
        PasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    public function createUser()
    {
        $userName = 'toto';
        $rawPassword = 'toto';

        $salt = base64_encode(random_bytes(10));
        $password = $this->passwordEncoder->encodePassword($rawPassword, $salt);

        $user = new User(
            new UserId(Uuid::uuid4()),
            $userName,
            $password,
            $salt,
            []
        );

        $this->userRepository->persist($user);
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
        $userId = new UserId($userId);
        $user = $this->userRepository->get($userId);

        // Pre-fill the user
        $userForm = ($user === null) ? new UserForm($userId) : UserForm::fromEntity($user);
        $form = $this->formFactory->create(UserType::class, $userForm);

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

        // Save the new user information

        /** @var UserForm $userForm */
        $userForm = $form->getData();

        if ($user === null) {
            $salt = base64_encode(random_bytes(10));
            $user = new User($userId, $userForm->getUserName(), null, $salt, []);
        }

        $user->changePassword($this->passwordEncoder->encodePassword($userForm->getPassword(), $user->getSalt()));

        $removeRoles = array_diff($user->getRoles(), $userForm->getRoles());
        foreach ($removeRoles as $role) {
            $user->removeRole($role);
        }

        $addRoles = array_diff($userForm->getRoles(), $user->getRoles());
        foreach ($addRoles as $role) {
            $user->addRole($role);
        }

        $this->userRepository->persist($user);

        return new RedirectResponse($this->urlGenerator->generate('user_list'));
    }

    public function listAction()
    {
        $response = new Response();
        $response->setContent($this->twig->render('user/list.twig'));

        return $response;
    }
}
