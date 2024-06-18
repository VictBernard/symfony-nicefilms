<?php

namespace App\Controller;

use App\Entity\Series;
use App\Form\SeriesType;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $seriesRepository = $entityManager->getRepository(Series::class);

        $queryBuilder = $seriesRepository->createQueryBuilder('s')
            ->select('s.id')
            ->getQuery();

        $seriesIds = $queryBuilder->getResult();

        if (empty($seriesIds)) {
            return $this->render('home/index.html.twig', [
                'series' => [],
            ]);
        }

        $highestId = max(array_column($seriesIds, 'id'));

        $seriesTab = [];

        for ($i = 0; $i < 5; $i++) {
            $id = $this->getRandId($highestId);

            if (in_array($id, array_column($seriesIds, 'id'))) {
                $serie = $seriesRepository->find($id);

                if ($serie !== null && !in_array($serie, $seriesTab)) {
                    $seriesTab[] = $serie;
                } else {
                    $i--;
                }
            } else {
                $i--;
            }
        }

        return $this->render('home/index.html.twig', [
            'series' => $seriesTab,
        ]);
    }

    /* delete everything about database when found highest id*/
    public function getRandId($maxId)
    {
        $randId = strval(random_int(1, $maxId));
        return $randId;
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

}
