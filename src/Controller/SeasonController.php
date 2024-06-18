<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Series;
use App\Entity\User;
use App\Entity\Episode;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SeasonController extends AbstractController
{
    #[Route('/season', name: 'app_season')]
    public function index(): Response
    {
        return $this->render('season/index.html.twig', [
            'controller_name' => 'SeasonController',
        ]);
    }

    protected function getAppUser(): ?User
    {
        return $this->getUser();
    }

    #[Route('/{id}/addE', name: 'app_addEpisode', methods: ['GET'])]
    public function addE(Episode $episodes, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = $this->getAppUser();
        $user->addEpisode($episodes);
<<<<<<< HEAD
        $previousEpisode = $user->notWatchedPreviousEpisodes($episodes);

        if($previousEpisode->count() > 0) {
            $this->addFlash('warning', 'Voulez vous marquer tous les episodes precedents comme vu ?');
            $session->set('last_episode_watched', $episodes->getId());
        }

        $serie = $episodes->getSeasons()->getSerie();

        // Vérifie si le message flash n'a pas déjà été affiché pour cette série
        if ($session->get('series_proposed_' . $serie->getId()) === null and !$user->getSeries()->contains($serie)) {
            $this->addFlash('success', 'Voulez-vous suivre cette série ?');

=======
        $serie = $episodes->getSeasons()->getSerie();

        // Vérifie si le message flash n'a pas déjà été affiché pour cette série
        if ($session->get('series_proposed_' . $serie->getId()) === null) {
            $this->addFlash('success', 'Voulez-vous suivre cette série ?');
            
>>>>>>> adedb8baf (push before merging main into our branch)
            // Marque la série comme étant proposée pour éviter d'afficher le message à nouveau
            $session->set('series_proposed_' . $serie->getId(), true);
        }

        $entityManager->flush();
<<<<<<< HEAD
        return $this->redirectToRoute('app_series_show', ['id' => $episodes->getSeasons()->getSerie()->getId(), 'previousEpisode' => $previousEpisode], Response::HTTP_SEE_OTHER);
=======
        return $this->redirectToRoute('app_series_show', ['id' => $episodes->getSeasons()->getSerie()->getId()], Response::HTTP_SEE_OTHER);
>>>>>>> adedb8baf (push before merging main into our branch)
    }


    #[Route('/{id}/removeE', name: 'app_removeEpisode', methods: ['GET'])]
    public function removeE(Episode $episodes, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $user->removeEpisode($episodes);
        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $episodes->getSeasons()->getSerie()->getId()], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/removeAllEOfOneSeason', name: 'app_removeAllEpisodeOfOneSeason', methods: ['GET'])]
    public function removeAllEOfOneSeason(Season $season, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getAppUser();
        $episodes = $season->getEpisodes();

        foreach($episodes as $e) {
            if ($user->watched($e)) {
                $user->removeEpisode($e);
            }
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $season->getSerie()->getId()], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/addAllEOfOneSeason', name: 'app_addAllEpisodeOfOneSeason', methods: ['GET'])]
    public function addAllEpisodesOfOneSeason(Season $season, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $user = $this->getAppUser();
        $episodes = $season->getEpisodes();
        $serie = $season->getSerie();

        foreach($episodes as $e) {
            if (!$user->watched($e)) {
                $user->addEpisode($e);
            }
<<<<<<< HEAD

            // Vérifie si le message flash n'a pas déjà été affiché pour cette série
            if ($session->get('series_proposed_' . $serie->getId()) === null and !$user->getSeries()->contains($season->getSerie())) {
                $this->addFlash('success', 'Voulez-vous suivre cette série ?');

                // Marque la série comme étant proposée pour éviter d'afficher le message à nouveau
                $session->set('series_proposed_' . $serie->getId(), true);
            }
=======
            // Vérifie si le message flash n'a pas déjà été affiché pour cette série
        if ($session->get('series_proposed_' . $serie->getId()) === null) {
            $this->addFlash('success', 'Voulez-vous suivre cette série ?');
            
            // Marque la série comme étant proposée pour éviter d'afficher le message à nouveau
            $session->set('series_proposed_' . $serie->getId(), true);
        }
>>>>>>> adedb8baf (push before merging main into our branch)
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $season->getSerie()->getId()], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/addAllEOfOneSerie', name: 'app_addAllEpisodeOfOneSerie', methods: ['GET'])]
<<<<<<< HEAD
    public function addAllEpisodesOfOneSerie(Series $serie, EntityManagerInterface $entityManager, SessionInterface $session): Response
=======
    public function addAllEpisodesOfOneSerie(Series $serie, EntityManagerInterface $entityManager,SessionInterface $session): Response
>>>>>>> adedb8baf (push before merging main into our branch)
    {
        $user = $this->getAppUser();
        $season = $serie->getSeasons();

        foreach($season as $s) {
            $episodes = $s->getEpisodes();
            foreach($episodes as $e) {
                if (!$user->watched($e)) {
                    $user->addEpisode($e);
                }
            }

<<<<<<< HEAD
            // Vérifie si le message flash n'a pas déjà été affiché pour cette série
            if ($session->get('series_proposed_' . $serie->getId()) === null and !$user->getSeries()->contains($serie)) {
                $this->addFlash('success', 'Voulez-vous suivre cette série ?');

                // Marque la série comme étant proposée pour éviter d'afficher le message à nouveau
                $session->set('series_proposed_' . $serie->getId(), true);
            }
=======
        // Vérifie si le message flash n'a pas déjà été affiché pour cette série
        if ($session->get('series_proposed_' . $serie->getId()) === null) {
            $this->addFlash('success', 'Voulez-vous suivre cette série ?');
            
            // Marque la série comme étant proposée pour éviter d'afficher le message à nouveau
            $session->set('series_proposed_' . $serie->getId(), true);
        }
>>>>>>> adedb8baf (push before merging main into our branch)
        }


        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $serie->getId()], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/addAllEOfOneSerieIndex', name: 'app_addAllEpisodeOfOneSerieIndex', methods: ['GET'])]
    public function addAllEpisodesOfOneSerieIndex(Series $serie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $season = $serie->getSeasons();

        foreach($season as $s) {
            $episodes = $s->getEpisodes();
            foreach($episodes as $e) {
                if (!$user->watched($e)) {
                    $user->addEpisode($e);
                }
            }
        }


        $entityManager->flush();
        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/removeAllEOfOneSerie', name: 'app_removeAllEpisodeOfOneSerie', methods: ['GET'])]
    public function removeAllEpisodesOfOneSerie(Series $serie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $season = $serie->getSeasons();

        foreach($season as $s) {
            $episodes = $s->getEpisodes();
            foreach($episodes as $e) {
                if ($user->watched($e)) {
                    $user->removeEpisode($e);
                }
            }
        }


        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $serie->getId()], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/removeAllEOfOneSerieIndex', name: 'app_removeAllEpisodeOfOneSerieIndex', methods: ['GET'])]
    public function removeAllEpisodesOfOneSerieIndex(Series $serie, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $season = $serie->getSeasons();

        foreach($season as $s) {
            $episodes = $s->getEpisodes();
            foreach($episodes as $e) {
                if ($user->watched($e)) {
                    $user->removeEpisode($e);
                }
            }
        }


        $entityManager->flush();
        return $this->redirectToRoute('app_series_index', [], Response::HTTP_SEE_OTHER);

    }


    #[Route('/{id}/addAllPreviousEpisode', name: 'app_addAllPreviousEpisode', methods: ['GET'])]
    public function addAllPreviousEpisode(Episode $episode, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getAppUser();
        $episodes = $user->notWatchedPreviousEpisodes($episode);
        foreach ($episodes as $e) {
            $user->addEpisode($e);
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_series_show', ['id' => $episode->getSeasons()->getSerie()->getId()], Response::HTTP_SEE_OTHER);

    }

}
<<<<<<< HEAD
=======


>>>>>>> adedb8baf (push before merging main into our branch)
