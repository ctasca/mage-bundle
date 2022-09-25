<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\File;

use Ctasca\MageBundle\Api\FileMakerInterface;
use Ctasca\MageBundle\Model\Template\DataProvider;

class MakerFactory
{
    /**
     * @param DataProvider $dataProvider
     * @param string $template
     * @return FileMakerInterface
     */
    public function create(DataProvider $dataProvider, string $template): FileMakerInterface
    {
        return new Maker($dataProvider, $template);
    }
}