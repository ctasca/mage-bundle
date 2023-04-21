<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Question\ConfirmationQuestion;

use Symfony\Component\Console\Question\ConfirmationQuestion;

class Factory
{
    /**
     * @param string $question
     * @return \Symfony\Component\Console\Question\ConfirmationQuestion
     */
    public function create(string $question): ConfirmationQuestion
    {
        return new ConfirmationQuestion($question, false);
    }
}
