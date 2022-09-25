<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Question\Prompt;

use Symfony\Component\Console\Question\Question;

class Validator
{
    /**
     * Module name validation pattern
     */
    const MODULE_NAME_VALIDATION_PATTERN = '/[A-Z]+[A-Za-z0-9]{1,}_[A-Z]+[A-Z0-9a-z]{1,}/';

    /**
     * Controller name validation pattern.
     * Makes sure Controller starts with an uppercase letter
     */
    const CONTROLLER_NAME_VALIDATION_PATTERN = '/^[A-Z]{1}/';

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

        self::setNormalizerAndMaxAttempts($question, $maxAttempts);
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateModuleName(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        $question->setValidator(function ($answer) use ($exceptionMessage) {
            if (empty($answer) || !preg_match(self::MODULE_NAME_VALIDATION_PATTERN, $answer)) {
                throw new \RuntimeException($exceptionMessage);
            }
            return $answer;
        });

        self::setNormalizerAndMaxAttempts($question, $maxAttempts);
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateControllerName(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        $question->setValidator(function ($answer) use ($exceptionMessage) {
            if (empty($answer) || !preg_match(self::CONTROLLER_NAME_VALIDATION_PATTERN, $answer)) {
                throw new \RuntimeException($exceptionMessage);
            }
            return $answer;
        });

        self::setNormalizerAndMaxAttempts($question, $maxAttempts);
    }

    /**
     * Set normalizer and max-attempts on a question
     *
     * @param Question $question
     * @param int $maxAttempts
     * @return void
     */
    private static function setNormalizerAndMaxAttempts(Question $question, int $maxAttempts): void
    {
        $question->setNormalizer(function ($value) {
            return $value ? trim($value) : '';
        });

        $question->setMaxAttempts($maxAttempts);
    }
}