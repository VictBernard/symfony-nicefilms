<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Series;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/randomUsers', name: 'app_randomUsers')]
<<<<<<< HEAD
    public function randomU(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i = 0; $i <= 50;$i++) {
=======
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i=0; $i<=30;$i++){
>>>>>>> adedb8baf (push before merging main into our branch)
            $user = new User();
            // encode the plain password
            $user->setEmail($faker->email);
            $user->setName($faker->firstName);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    'password'
                )
            );

            $entityManager->persist($user);
<<<<<<< HEAD
            // if($i==50 or $i==100){
            //     $entityManager->flush();
            // }
        }
        $entityManager->flush();
        $this->addFlash('success', '50 utilisateurs ont été ajouté !');
=======
            $entityManager->flush();    
        }

        $this->addFlash('success', '30 utilisateurs ont été ajouté !');
>>>>>>> adedb8baf (push before merging main into our branch)

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
<<<<<<< HEAD


    public function generateRandomNumbers(int $center,int $min,int $max,int $count): Array
{
    $numbers = [];

    for ($i = 0; $i < $count; $i++) {
        $number = $center + rand(-2, 2); 
        $number = max($min, min($max, $number));
        $numbers[] = $number;
    }

    return $numbers;
}

    #[Route('/randomReviews', name: 'app_randomReviews')]
    public function randomR(Request $request, EntityManagerInterface $entityManager): Response
    {
        $faker = Faker\Factory::create('fr_FR');

        // ici
        $seriesRepository = $entityManager->getRepository(Series::class);

        $queryBuilder = $seriesRepository->createQueryBuilder('s')
            ->select('s.id')
            ->getQuery();

        $seriesIds = $queryBuilder->getResult();
        $highestIdSeries = max(array_column($seriesIds, 'id'));


        $userRepository = $entityManager->getRepository(User::class);

        $queryBuilder = $userRepository->createQueryBuilder('u')
        ->select('u.id')
        ->getQuery();

        $usersIDs = $queryBuilder->getResult();
        $highestIdUsers =  max(array_column($usersIDs, 'id'));


        $seriesTab = [];
        $usersTab = [];
        
        // Loop over series
        for ($i = 0; $i < 10; $i++) {           
            $idSeries = $this->getRandId($highestIdSeries);
            $usersTab = [];
            $generatedRating = $this->generateRandomNumbers(rand(1,10), 1, 10, 10);

            if (in_array($idSeries, array_column($seriesIds, 'id'))) {
                $serie = $seriesRepository->find($idSeries);
                if ($serie !== null && !in_array($serie, $seriesTab)) {
                    $seriesTab[] = $serie;
                    // Loop for reviews
                    for ($j = 0; $j < 10; $j++) {
                        $userId = $this->getRandId($highestIdUsers);
                        if (in_array($userId, array_column($usersIDs, 'id'))) {
                            $user = $userRepository->find($userId);
                            if ($user !== null && !in_array($user, $usersTab)) {

                                $rating = New Rating();
                                $rating
                                ->setUser($user)
                                ->setComment($faker->words(10, true))
                                ->setSeries($serie)
                                ->setValue($generatedRating[$j]);
                                $entityManager->persist($rating);
                            } else {
                                $j--;
                            }
                        } else {
                            $j--;
                        }
                    }
                    //Fin loop review
                    $averageScore = 0;
                    $query = $entityManager->getRepository(Rating::class)->createQueryBuilder('entity');

                    $query->andWhere('entity.series = :series')
                    ->setParameter('series', $serie);
                    $reviews = $query->orderBy('entity.date', 'DESC')
                    ->getQuery()->getResult();
                    foreach ($reviews as $r) {
                        $averageScore += $r->getValue();
                    }
                        $totalCount = count($reviews);
                        $averageScore = $totalCount > 0 ? (($averageScore) / $totalCount) / 2 : 0;
                        $serie->setAverageRating($averageScore);
                        $entityManager->flush();
                } else {
                    $i--;
                }
            } else {
                $i--;
            }
        }

        $entityManager->flush();    
        $this->addFlash('success', '10 avis ont été généré sur 10 séries !');

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    function getRandId($maxId)
    {    
        $randId = strval(random_int(1, $maxId));
        return $randId;
    }

=======
>>>>>>> adedb8baf (push before merging main into our branch)
}
