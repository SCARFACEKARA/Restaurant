<?php

namespace App\Controller\API;

use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class ApiUserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 📌 Récupérer tous les utilisateurs (sans pagination)
     */
    #[Route('/all', name: 'api_user_list', methods: ['GET'])]
    public function findAllUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users, Response::HTTP_OK, [], ['groups' => ['users.list']]);
    }

    /**
     * 📌 Récupérer un utilisateur spécifique par ID
     */
    #[Route('/{id}', name: 'api_user_show', methods: ['GET'])]
    public function showUser(User $user): JsonResponse
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['users.list']]);
    }

    /**
     * 📌 Connexion utilisateur
     */
    #[Route('/login', name: 'api_user_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['mdp'])) {
            return $this->json(['error' => 'Missing fields: email, password'], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Invalid email format'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->$userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->passwordEncoder->isPasswordValid($user, $data['mdp'])) {
            return $this->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        // Générez un token JWT ici si vous utilisez une authentification basée sur un token

        return $this->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()->value
            ],
        ], Response::HTTP_OK);
    }

    /**
     * 📌 Créer un nouvel utilisateur
     */
    #[Route('/create', name: 'api_user_create', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['email'], $data['mdp'], $data['role'])) {
            return $this->json(['error' => 'Missing fields: email, password, role'], Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json(['error' => 'Invalid email format'], Response::HTTP_BAD_REQUEST);
        }

        $role = UserRole::tryFrom($data['role']);
        if (!$role) {
            return $this->json(['error' => 'Invalid role'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setMdp(password_hash($data['mdp'], PASSWORD_BCRYPT));
        $user->setRole($role);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => ['users.list']]);
    }

    /**
     * 📌 Modifier un utilisateur existant
     */
    #[Route('/edit/{id}', name: 'api_user_edit', methods: ['PUT'])]
    public function editUser(Request $request, User $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user->setEmail($data['email']);
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $user->setMdp(password_hash($data['password'], PASSWORD_BCRYPT));
        }

        if (isset($data['role'])) {
            $role = UserRole::tryFrom($data['role']);
            if (!$role) {
                return $this->json(['error' => 'Invalid role'], Response::HTTP_BAD_REQUEST);
            }
            $user->setRole($role);
        }

        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['users.list']]);
    }

    /**
     * 📌 Supprimer un utilisateur
     */
    #[Route('/delete/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function deleteUser(User $user): JsonResponse
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
    }
}
