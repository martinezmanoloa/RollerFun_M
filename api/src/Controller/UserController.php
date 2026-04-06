<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;

#[Route('/api')]
final class UserController extends AbstractController
{

    // SHOW ALL USERS
    #[Route('/show/users', name: 'get_users', methods: ['GET'])]
    public function getUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        return $this->json([
            'users' => $users,
        ]);
    }

    //CREATE NEW USER
    #[Route('/new/users', name: 'post_users', methods: ['POST'])]
    public function postUsers(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'error' => 'Invalid JSON'
            ], 400);
        }

        $errors = [];

        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'][] = 'Name is required';
        } elseif (strlen($data['name']) < 3) {
            $errors['name'][] = 'Name must be at least 3 characters long';
        }

        if (!isset($data['age']) || $data['age'] === null) {
            $errors['age'][] = 'Age is required';
        } elseif (!is_int($data['age']) || $data['age'] <= 5) {
            $errors['age'][] = 'Age must be greater than 5';
        }

        if (!empty($errors)) {
            return $this->json([
                'error' => 'Validation failed',
                'details' => $errors
            ], 400);
        }

        $user = new User();
        $user->setName($data['name']);
        $user->setAge($data['age']);
        $user->setCreatedAt(new \DateTime());

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User created successfully',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'age' => $user->getAge(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    // GET USER BY ID
    #[Route('/show/users/{id}', name: 'get_users_by_id', methods: ['GET'])]
    public function getUsersById(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json([
                'error' => 'User not found'
            ], 404);
        }

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'age' => $user->getAge(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    // UPDATE USER BY ID
    #[Route('/edit/users/{id}', name: 'put_users_by_id', methods: ['PUT'])]
    public function putUsersById(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json([
                'error' => 'User not found'
            ], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json([
                'error' => 'Invalid JSON'
            ], 400);
        }

        $errors = [];

        if (isset($data['name'])) {
            if (empty($data['name'])) {
                $errors['name'][] = 'Name cannot be empty';
            } elseif (strlen($data['name']) < 3) {
                $errors['name'][] = 'Name must be at least 3 characters long';
            } else {
                $user->setName($data['name']);
            }
        }

        if (isset($data['age'])) {
            if ($data['age'] === null) {
                $errors['age'][] = 'Age cannot be null';
            } elseif (!is_int($data['age']) || $data['age'] <= 5) {
                $errors['age'][] = 'Age must be greater than 5';
            } else {
                $user->setAge($data['age']);
            }
        }

        if (!empty($errors)) {
            return $this->json([
                'error' => 'Validation failed',
                'details' => $errors
            ], 400);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'User updated successfully',
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'age' => $user->getAge(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    // DELETE USER BY ID
    #[Route('/delete/users/{id}', name: 'delete_users_by_id', methods: ['DELETE'])]
    public function delUsersById(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json([
                'error' => 'User not found'
            ], 404);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
