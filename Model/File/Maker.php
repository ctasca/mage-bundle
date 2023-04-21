<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


declare(strict_types=1);

namespace Ctasca\MageBundle\Model\File;

use Ctasca\MageBundle\Api\FileMakerInterface;
use Ctasca\MageBundle\Model\Template\DataProvider;

class Maker implements FileMakerInterface
{
    private DataProvider $dataProvider;
    private string $template;

    /**
     * @param \Ctasca\MageBundle\Model\Template\DataProvider $dataProvider
     * @param string $template
     */
    public function __construct(
        DataProvider $dataProvider,
        string $template
    ) {
        $this->dataProvider = $dataProvider;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function make(): string
    {
        preg_match_all(self::DATA_PLACEHOLDER_PATTERN, $this->template, $matches);
        $matchesIterator = new \ArrayIterator($matches[1]);
        while ($matchesIterator->valid()) {
            $match = $matchesIterator->current();
            $dataProviderMethod = 'get' . ucfirst($match);
            $data = $this->dataProvider->{$dataProviderMethod}();

            if (is_array($data)) {
                $data = implode(PHP_EOL, $data);
            }

            $this->template = str_replace(
                '{{' . $match . '}}',
                $data,
                $this->template
            );
            $matchesIterator->next();
        }

        return $this->template;
    }
}
