<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('timeElapsed', [$this, 'timeElapsed']),
        ];
    }
    public function timeElapsed(DateTime $dateCreation)
    {
        $now = new DateTime();

        $interval = $now->diff($dateCreation);
        $format = 'Il y a ';

        if ($interval->y > 0) {
            $format = '%y ans';
        } elseif ($interval->m > 0) {
            $format = '%m mois';
        } elseif ($interval->d > 0) {
            $format = '%d jours';
        } elseif ($interval->h > 0) {
            $format = '%h heures';
        } elseif ($interval->i > 0) {
            $format = '%i minutes';
        } else {
            $format = '%s secondes';
        }

        return $interval->format($format);
    }
}
