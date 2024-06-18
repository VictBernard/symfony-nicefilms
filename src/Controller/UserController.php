<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Rating;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        $query = $entityManager->getRepository(User::class)->createQueryBuilder('entity');

        $searchTerm = $request->query->get('search');

        if ($searchTerm && $searchTerm != "") {
            // Add a condition to filter by email if a search term is provided
            $query->andWhere('entity.email LIKE :searchTerm')
                  ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $query->orderBy('entity.registerDate', 'DESC')
            ->getQuery();

        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);

        $pagination = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Current page number
            $itemsPerPage,
            ['pageParameterName' => 'page'] // Number of items per page
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
        ]);

    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user, EntityManagerInterface $entityManager): Response
    {
        $queryRating = $entityManager->getRepository(Rating::class)
        ->createQueryBuilder('rating')
            ->andWhere('rating.user = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult();




        return $this->render('user/show.html.twig', [
            'user' => $user,
            'ratings' => $queryRating
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/promote', name: 'app_user_promote', methods: ['GET', 'POST'])]
    public function promote(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(["ROLE_ADMIN"]);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/promote.html.twig', [
            'user' => $user,
        'form' => $form,]);
    }

    #[Route('/{id}/promoteAction', name: 'app_user_promote_action', methods: ['POST'])]
    public function promoteAction(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        if ($this->isCsrfTokenValid('promote'.$user->getId(), $request->request->get('_token'))) {
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setAdmin(true);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route("/impersonate/{email}", name:"app_user_impersonate")]
    public function impersonateUser(string $email): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');



        return $this->redirectToRoute('app_home');
    }




}
