<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\User\CustomerNumberService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\User\UserTransformer;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/list', name: 'user_list', methods: ['GET'])]
    public function list(UserRepository $userRepository, UserTransformer $userTransformer): JsonResponse
    {
        try {
            $users = $userRepository->findAll();
            $data = [];
            foreach ($users as $user) {
                $data[] = $userTransformer->transform($user);
            }
    
            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/add', name: 'user_add', methods: ['GET','POST'])]
    public function addUser(Request $request, EntityManagerInterface $entityManager, UserTransformer $userTransformer, CustomerNumberService $customerNumberService): JsonResponse
    {

        try {
            $data = json_decode($request->getContent(), true);
            $data['customerNumber'] = $customerNumberService->getMaxCustomerNumber();

            // Validate the required fields
            if (empty($data['customerNumber']) || empty($data['firstName']) || empty($data['email'])) {
                return $this->json(['message' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
            }

            // Create and set the new user entity
            $user = new User();
            $user->setCustomerNumber($data['customerNumber']);
            $user->setFirstName($data['firstName']);
            $user->setSecondName($data['secondName'] ?? null);
            $user->setStreet($data['street'] ?? null);
            $user->setHouseNumber($data['houseNumber'] ?? null);
            $user->setPin($data['pin'] ?? null);
            $user->setLocation($data['location'] ?? null);
            $user->setEmail($data['email']);
            $user->setAnrede($data['anrede'] ?? null);

            // Persist the user entity to the database
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'User successfully created',
                'user' => $userTransformer->transform($user)
                // 'user' => $data
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/user/update/{id}', name: 'user_update', methods: ['PUT'])]
    public function update(Request $request, UserTransformer $userTransformer, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        try {
            // Find the user by ID
            $user = $entityManager->getRepository(User::class)->find($id);
            if (!$user) {
                // Throw an exception if the user is not found
                throw $this->createNotFoundException(
                    'No user found for id ' . $id
                );
            }

            // Decode the JSON data from the request body
            $data = json_decode($request->getContent(), true);

            // Validate and set user data
            if (isset($data['firstName'])) {
                $user->setFirstName($data['firstName']);
            }
            if (isset($data['secondName'])) {
                $user->setSecondName($data['secondName']);
            }
            if (isset($data['street'])) {
                $user->setStreet($data['street']);
            }
            if (isset($data['houseNumber'])) {
                $user->setHouseNumber($data['houseNumber']);
            }
            if (isset($data['pin'])) {
                $user->setPin($data['pin']);
            }
            if (isset($data['location'])) {
                $user->setLocation($data['location']);
            }
            if (isset($data['email'])) {
                $user->setEmail($data['email']);
            }
            if (isset($data['anrede'])) {
                $user->setAnrede($data['anrede']);
            }

            // Persist the updated user entity to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Return a JSON response indicating success
            return $this->json([
                'message' => 'User successfully updated',
                'user' => $userTransformer->transform($user)
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            // Return a JSON response indicating an error occurred
            return $this->json([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[Route('/user/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(Request $request, UserTransformer $userTransformer, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        try {
            // Find the user by ID
            $user = $entityManager->getRepository(User::class)->find($id);
            if (!$user) {
                // Throw an exception if the user is not found
                throw $this->createNotFoundException(
                    'No user found for id ' . $id
                );
            }

            // Remove the user entity from the database
            $entityManager->remove($user);
            $entityManager->flush();

            // Return a JSON response indicating success
            return $this->json([
                'message' => 'User successfully deleted',
                'user' => $userTransformer->transform($user)
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            // Return a JSON response indicating an error occurred
            return $this->json([
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
