<?php

namespace App\Controller;

use App\Entity\Series;
use App\Entity\Episode;
use App\Entity\Genre;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\RatingType;
use App\Form\SeriesType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OrderBy;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Helper\ProgressBar;

#[Route('/series')]
class SeriesController extends AbstractController
{
    #[Route('/', name: 'app_series_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('entity');

        $result = $queryBuilder
            ->select('MIN(entity.yearStart) as minYearStart', 'MAX(entity.yearStart) as maxYearStart', 'MIN(entity.yearEnd) as minYearEnd', 'MAX(entity.yearEnd) as maxYearEnd')
            ->getQuery()
            ->getScalarResult();

        $yearStartMin = $result[0]['minYearStart'];
        $yearStartMax = $result[0]['maxYearStart'];

        $yearEndMin = $result[0]['minYearEnd'];
        $yearEndMax = $result[0]['maxYearEnd'];

        $ecart = 10;
        $startYears = range($yearStartMin, $yearStartMax, $ecart);
        $endYears = range($yearEndMin, $yearEndMax, $ecart);

        $query = $entityManager->getRepository(Series::class)->createQueryBuilder('entity');
        $genres = $entityManager->getRepository(Genre::class)->findAll();

        $searchTerm = $request->query->get('search');
        $genreCoche = $request->query->get('genre');
        $yearStartFilters = $request->query->get('anneeDebut');
        $yearEndFilters = $request->query->get('anneeFin');
        $noteFilters = $request->query->get('notes');
        $genresArray = is_array($genreCoche) ? $genreCoche : [$genreCoche];

        if ($searchTerm && $searchTerm != "") {
            $query->andWhere('entity.plot LIKE :searchTerm OR entity.title LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        if ($noteFilters != null && $noteFilters == "Croissant") {
            $query->orderBy('entity.averageRating', 'ASC');
        }

        if ($noteFilters != null && $noteFilters == "Decroissant") {
            $query->orderBy('entity.averageRating', 'DESC');
        }

        if ($genreCoche != null) {
            $query->andWhere(':genres MEMBER OF entity.genres')
                ->setParameter('genres', $genresArray);
        }

        // Filtrer par année de début si des filtres sont sélectionnés
        if ($yearStartFilters != null && $yearEndFilters == null) {
            $fin = $yearStartFilters + ($ecart - 1);
            $query->andWhere('entity.yearStart >= :startYear and entity.yearStart <= :fin')
                ->setParameter('startYear', $yearStartFilters)
                ->setParameter('fin', $fin);
        }

        // Filtrer par année de fin si des filtres sont sélectionnés
        if ($yearEndFilters != null && $yearStartFilters == null) {
            $fin = $yearEndFilters + ($ecart - 1);
            $query->andWhere('entity.yearEnd >= :endYear and entity.yearEnd <= :fin')
                ->setParameter('endYear', $yearEndFilters)
                ->setParameter('fin', $fin);
        }

        // Filtrer par plage d'années si les deux filtres sont spécifiés
        // Filtrer par plage d'années si les deux filtres sont spécifiés
        if ($yearStartFilters != null && $yearEndFilters != null) {
            $fin = $yearStartFilters + ($ecart);
            $debut = $yearEndFilters + ($ecart);
            $query->andWhere(
                '
                (entity.yearStart >= :startYear and entity.yearStart <= :fin) AND
                (entity.yearEnd >= :endYear and entity.yearEnd <= :debut)'
            )
            ->setParameter('startYear', $yearStartFilters)
            ->setParameter('fin', $fin)
            ->setParameter('debut', $debut)
            ->setParameter('endYear', $yearEndFilters);
        }




        $searchTerm = $request->query->get('search');

        if ($searchTerm && $searchTerm != "") {
            // Add a condition to filter by email if a search term is provided
            $query->andWhere('entity.plot LIKE :searchTerm OR entity.title LIKE :searchTerm')
                  ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $query->getQuery();

        $pagination = $paginator->paginate(
<<<<<<< HEAD
            $query, // Requête à paginer
            $request->query->getInt('page', 1), // Numéro de page actuel
            10 // Nombre d'éléments par page
=======
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Current page number
            10 // Number of items per page
>>>>>>> adedb8baf (push before merging main into our branch)
        );

        return $this->render('series/index.html.twig', [
            'series' => $pagination,
<<<<<<< HEAD
            'genres' => $genres,
            'startYears' => $startYears,
            'endYears' => $endYears,
=======
>>>>>>> adedb8baf (push before merging main into our branch)
        ]);

    }

    protected function getAppUser(): ?User
    {
        return $this->getUser();
    }

    #[Route('/listeFiltre', name: 'app_liste', methods: ['GET'])]
    public function liste(EntityManagerInterface $entityManager, Request $request,PaginatorInterface $paginator): Response
    {
        $serieRepository = $entityManager->getRepository(Series::class);
        $allSeries = $serieRepository->findAll();

        $searchTerm = $request->query->get('search');

        $filteredSeries = $allSeries;

        if ($searchTerm && $searchTerm !== "") {
            // Filtrez les séries par titre ou par plot si un terme de recherche est fourni
            $filteredSeries = array_filter(
                $filteredSeries,
                function ($s) use ($searchTerm) {
                    $titleMatch = stripos($s->getTitle(), $searchTerm) !== false;
                    $plotMatch = stripos($s->getPlot(), $searchTerm) !== false;

                    return $titleMatch || $plotMatch;
                }
            } else {
                if(!$user->watchedAllSerie($s)) {
                    $seriesCollection->add($s);
                }
            }
        }

        return $this->render('series/liste.html.twig', [
            'series' => $seriesCollection,
=======

        // Paginer les résultats filtrés
        $pagination = $paginator->paginate(
            $filteredSeries,
            $request->query->getInt('page', 1), // Current page number
            10 // Number of items per page
        );

        return $this->render('series/liste.html.twig', [
            'series' => $pagination,
>>>>>>> adedb8baf (push before merging main into our branch)
        ]);
    }

    #[Route('/{id}/addU', name: 'app_add', methods: ['GET'])]
    public function addU(Series $series, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $user->addSeries($series);
        $entityManager->flush();
        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/addSerie', name: 'app_add_serie', methods: ['GET'])]
    public function addSerie(Series $series, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $user->addSeries($series);
        $id = $series->getId();
        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $id], Response::HTTP_SEE_OTHER);

    }


    #[Route('/{id}/removeU', name: 'app_remove', methods: ['GET'])]
    public function removeU(Series $series, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $user->removeSeries($series);
        $entityManager->flush();
        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/removeSerie', name: 'app_remove_serie', methods: ['GET'])]
    public function removeSerie(Series $series, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $user->removeSeries($series);
        $id = $series->getId();
        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/poster_image/{id}', name: 'app_series_showPoster', methods: ['GET'])]
    public function showPoster(Series $series): Response
    {
        return new Response(
            stream_get_contents($series->getPoster()),
            Response::HTTP_OK,
            ['Content-type' => 'image/jpg']
        );
    }


    #[Route('/new', name: 'app_series_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $series = new Series();
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($series);
            $entityManager->flush();

            return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('series/new.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_series_show', methods: ['POST','GET'])]
    public function show(Series $series, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {


        $query = $entityManager->getRepository(Rating::class)->createQueryBuilder('entity');

        $searchTerm = $request->query->get('search');

        // Add a condition to filter by email if a search term is provided
        $query->andWhere('entity.series = :series')
              ->setParameter('series', $series);


        $reviews = $query->orderBy('entity.date', 'DESC')
            ->getQuery()->getResult();

        $averageScore = 0;



        $reviews = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Current page number
            10 //Number of items per page
        );

        $seasons = $series->getSeasons();
        $id = $series->getId();

        $user = $this->getUser();
        $reviewForm = null;
        $existingReview = $entityManager->getRepository(Rating::class)->findOneBy(['series' => $series, 'user' => $user]);

        // Only a logged in user may leave reviews
        if ($user !== null) {
            if(!$existingReview) {
                $review = new Rating();
                $review->setUser($user)->setSeries($series)->setComment("");
                $form = $this->createForm(RatingType::class, $review);
            } else {
                $form = $this->createForm(RatingType::class, $existingReview);
            }

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!$existingReview) {
                    $entityManager->persist($review);
                } else {
                    $existingReview->setDate(new \DateTime());
                }
                // Include the new review in the calculation
                foreach ($reviews as $r) {
                    $averageScore += $r->getValue();
                }

                if (!$existingReview) {
                    $totalCount = count($reviews) + 1;
                    $averageScore = $totalCount > 0 ? (($averageScore + $review->getValue()) / $totalCount) / 2 : 0;
                } else {
                    $totalCount = count($reviews);
                    $averageScore = $totalCount > 0 ? (($averageScore) / $totalCount) / 2 : 0;
                }
                // Include the new review in the count

                // Calculate the new average score

                $series->setAverageRating($averageScore);

                $entityManager->flush();
                return $this->redirectToRoute('app_series_show', ['id' => $series->getId()]);
            }

            // Convert the form to a FormView for rendering in the template
            $reviewForm = $form->createView();
        }

        $noteDistribution = $this->getNoteDistribution($reviews);

        return $this->render('series/show.html.twig', [
            'series' => $series,
            'seasons' => $seasons,
            'id' => $id,
            'reviews' => $reviews,
            'reviewForm' => $reviewForm,
            'existingReview' => $existingReview,
            'averageScore' => $averageScore,
            'noteDistribution' => $noteDistribution
        ]);
    }

    private function getNoteDistribution($reviews): array
    {
        $noteDistribution = [];

        for ($i = 0; $i <= 5; $i++) {
            $lowerBound = $i;
            $upperBound = $i + 0.5;
            $count = 0;
            foreach($reviews as $r) {
                if(($r->getValue() / 2 >= $lowerBound) && ($r->getValue() / 2 <= $upperBound)) {
                    $count += 1;
                }
            }

            // Store the count in the note distribution array
            $noteDistribution[$i] = $count;
        }

        return $noteDistribution;
    }

    #[Route('/{id}/edit', name: 'app_series_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('series/edit.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_series_delete', methods: ['POST'])]
    public function delete(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$series->getId(), $request->request->get('_token'))) {
            $entityManager->remove($series);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);
    }
}
