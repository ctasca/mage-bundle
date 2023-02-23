<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface QuestionPromptValidatorInterface
{
    /**
     * Module name validation pattern
     * Validates module at command question
     */
    const MODULE_NAME_VALIDATION_PATTERN = '/[A-Z]+[A-Za-z0-9]{1,}_[A-Z]+[A-Z0-9a-z]{1,}/';

    /**
     * First uppercase letter validation pattern.
     * Makes sure input given starts with an uppercase letter
     */
    const UC_FIRST_VALIDATION_PATTERN = '/^[A-Z]{1}/';

    /**
     * Validates path like:
     *  - ClassName or Classname
     *  - Dir/AnotherDir/ClassName
     *
     * Invalid paths:
     *  - /Dir/AnotherDir/ClassName
     *  - Dir/AnotherDir/ClassName/
     */
    const PATH_VALIDATION_PATTERN = '/^[A-Z]([\w]{0,}[\/]{0,})+([A-Z]|[\w]{0,})+[^\/]$/';

    /**
     * Javascript file name path validation pattern
     *
     * Validates paths to a JS file. Filename must not end with . or /
     * and only lowercase letters, underscores and dashes are allowed.
     */
    const JS_FILE_PATH_VALIDATION_PATTERN = '/^[a-z]([a-z-_]{0,}[\/]{0,})+([a-z]{0,})+[^\/\.]$/';

    /**
     * Javascript mixin file name path validation pattern
     *
     * Validates paths to a JS mixin file. Filename must end with -mixin
     * and only lowercase letters, underscores and dashes are allowed.
     */
    const JS_MIXIN_FILE_PATH_VALIDATION_PATTERN = '/^[a-z]([a-z-_]{0,}[\/]{0,})+([a-z]{0,})+[^\/\.]-mixin$/';

    /**
     * Validates JQuery widget name
     *
     * Valid widget names:
     * - widget.test
     * - widget.anotherTest
     *
     * Invalid widget names:
     * - Widget.Test
     * _ widget.Test
     */
    const JQUERY_WIDGET_NAME_VALIDATION_PATTERN = '/^[a-z]+\.[a-z][\w]+$/';

    /**
     * Logger Handler filename validation pattern
     *
     * Validates a Logger Handler filename. Filename must end with .log file extension
     * and only lowercase letters, underscores and dashes are allowed.
     *
     * Must not start with directory separator.
     *
     */
    const LOGGER_FILENAME_VALIDATION_PATTERN = '/^[a-z]([a-z-_]{0,}[\/]{0,})+([a-z]{0,})+[^\/\.]\.log$/';

    /**
     * Ui component namespace validation pattern
     *
     */
    const UI_NAMESPACE_VALIDATION_PATTERN = '/^([a-z-_])+[a-z]{1}$/';
}
