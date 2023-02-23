<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Question\Prompt;

use Ctasca\MageBundle\Api\QuestionPromptValidatorInterface;
use Symfony\Component\Console\Question\Question;

class Validator implements QuestionPromptValidatorInterface
{
    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateRequired(Question $question, string $exceptionMessage, int $maxAttempts): void
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
    public static function validateUcFirst(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        self::validateQuestionByPattern(
            $question,
            self::UC_FIRST_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validatePath(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        self::validateQuestionByPattern(
            $question,
            self::PATH_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateJsFilenamePath(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        self::validateQuestionByPattern(
            $question,
            self::JS_FILE_PATH_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateJsMixinFilenamePath(
        Question $question,
        string $exceptionMessage,
        int $maxAttempts
    ): void {
        self::validateQuestionByPattern(
            $question,
            self::JS_MIXIN_FILE_PATH_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateJQueryWidgetName(
        Question $question,
        string $exceptionMessage,
        int $maxAttempts
    ): void {
        self::validateQuestionByPattern(
            $question,
            self::JQUERY_WIDGET_NAME_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateLoggerFilename(
        Question $question,
        string $exceptionMessage,
        int $maxAttempts
    ): void {
        self::validateQuestionByPattern(
            $question,
            self::LOGGER_FILENAME_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateModuleName(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        self::validateQuestionByPattern(
            $question,
            self::MODULE_NAME_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    public static function validateControllerName(Question $question, string $exceptionMessage, int $maxAttempts): void
    {
        self::validateQuestionByPattern(
            $question,
            self::UC_FIRST_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );
    }

    /**
     * @param Question $question
     * @param string $pattern
     * @param string $exceptionMessage
     * @param int $maxAttempts
     * @return void
     */
    private static function validateQuestionByPattern(
        Question $question,
        string $pattern,
        string $exceptionMessage,
        int $maxAttempts
    ): void {
        $question->setValidator(function ($answer) use ($pattern, $exceptionMessage) {
            if (empty($answer) || !preg_match($pattern, $answer)) {
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

    public static function validateUiComponentNamespace(
        Question $question,
        string $exceptionMessage,
        int $maxAttempts
    )
    {
        self::validateQuestionByPattern(
            $question,
            self::UI_NAMESPACE_VALIDATION_PATTERN,
            $exceptionMessage,
            $maxAttempts
        );

    }
}
