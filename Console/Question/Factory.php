<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Question;

use Symfony\Component\Console\Question\Question;

class Factory
{
    /**
     * @param string $question
     * @return Question
     */
    public function create(string $question): Question
    {
        return new Question($question);
    }
}
