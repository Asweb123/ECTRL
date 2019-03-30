<?php

namespace App\Service;




use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;

class ThemeManager
{
    private $em;
    private $requirementRepository;
    private $themeRepository;

    public function __construct(EntityManagerInterface $em, ThemeRepository $themeRepository)
    {
        $this->em = $em;
        $this->themeRepository = $themeRepository;
    }

    public function Ranker($theme, $model)
    {
        $rank = count($model->getThemes()) + 1;

        $theme->setRankCertification($rank);

        return $theme;
    }


    public function colorSetter($theme, $rank)
    {
        $colors = ['#9AD9DB', '#247474', '#7786FB', '#99F0B8', '#B87AFF', '#5BC1C2', '#FAC392', '#F28181', '#75AAA9', '#D4B0FF'];

        $rank = $rank % 10;

        $theme = $theme->setColor($colors[$rank]);

        return $theme;
    }

    public function deleteThemeManager($theme)
    {
        $model = $theme->getCertification();
        $modelThemesNb = count($model->getThemes());

        if ($modelThemesNb !== 1 || $modelThemesNb !== $theme->getRankCertification()){
            $nextThemes = $this->themeRepository->findNextThemes($model, $theme->getRankCertification());
            foreach($nextThemes as $nextTheme){
                $nextTheme->setRankCertification($nextTheme->getrankcertification() - 1);
                $this->em->persist($nextTheme);
            }
        }
        $this->em->remove($theme);
        $this->em->flush();

    }
}
