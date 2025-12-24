<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/signup', name: 'app_signup')]
    public function signup(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('signup.html.twig');
    }

    #[Route('/api/auth/signup', name: 'api_auth_signup', methods: ['POST'])]
    public function apiSignup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['username']) || empty($data['password'])) {
            return $this->json(['error' => 'Username and password are required'], Response::HTTP_BAD_REQUEST);
        }

        if (strlen($data['password']) < 6) {
            return $this->json(['error' => 'Password must be at least 6 characters'], Response::HTTP_BAD_REQUEST);
        }

        // Check if user already exists
        if ($this->userRepository->findOneBy(['username' => $data['username']])) {
            return $this->json(['error' => 'Username already exists'], Response::HTTP_CONFLICT);
        }

        $user = new User();
        $user->setUsername($data['username']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Account created successfully',
            'username' => $user->getUsername()
        ], Response::HTTP_CREATED);
    }

    #[Route('/api/auth/login', name: 'api_auth_login', methods: ['POST'])]
    public function apiLogin(): JsonResponse
    {
        // This will be handled by Symfony's form login
        // This endpoint is mainly for API documentation
        if ($this->getUser()) {
            return $this->json([
                'message' => 'Already logged in',
                'user' => $this->getUser()->getUserIdentifier()
            ]);
        }

        return $this->json(['message' => 'Login via form'], Response::HTTP_OK);
    }

    #[Route('/api/auth/logout', name: 'api_auth_logout', methods: ['POST'])]
    public function apiLogout(): JsonResponse
    {
        // This will be handled by Symfony's logout
        return $this->json(['message' => 'Logged out']);
    }
}
