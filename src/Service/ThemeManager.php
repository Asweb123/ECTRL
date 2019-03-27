<?php

namespace App\Service;


class ThemeManager
{
    public function Ranker($theme, $model)
    {
        $rank = count($model->getThemes()) + 1;

        $theme->setRankCertification($rank);

        return $theme;
    }

    public function colorSetter($theme, $rank)
    {
        $colors = ['#43E870', '#56B5FF', '#FFF85A', '#A143E8', '#FF6A5A', '#ACFF38', '#3BFFEF', '#D04BE8', '#D16D39', '#F5DD4F'];

        $rank = $rank % 10;

        $theme = $theme->setColor($colors[$rank]);

        return $theme;
    }

}