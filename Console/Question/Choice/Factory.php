<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Question\Choice;

use Symfony\Component\Console\Question\ChoiceQuestion;

class Factory
{
    /**
     * @param string $question
     * @param array<int,string> $choices
     * @return \Symfony\Component\Console\Question\ChoiceQuestion
     */
    public function create(string $question, array $choices): ChoiceQuestion
    {
        return new ChoiceQuestion($question, $choices);
    }
}
