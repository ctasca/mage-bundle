<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Question\Prompt;

use Symfony\Component\Console\Question\Question;

class Validator
{
    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validate(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        $question->setValidator(function ($answer) use ($exceptionMessage) {
            if (empty($answer)) {
                throw new \RuntimeException($exceptionMessage);
            }
            return $answer;
        });

        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });

        $question->setMaxAttempts($maxAttempts);
    }
}