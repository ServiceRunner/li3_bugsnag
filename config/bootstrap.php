<?php

use lithium\core\Libraries;
use lithium\core\Environment;
use lithium\security\Auth;

$_config = Libraries::get('li3_bugsnag');

if (isset($_config['apiKey']) && !empty($_config['apiKey'])) {
    \Bugsnag::register($_config['apiKey']);
    \Bugsnag::setReleaseStage(Environment::get());
    \Bugsnag::setProjectRoot(LITHIUM_APP_PATH);

    set_error_handler('Bugsnag::errorHandler');

    /**
     * This placeholder is used only to retrieve last defined handler
     * @var callable
     */
    $placeholder = set_exception_handler(null);

    set_exception_handler(function($exception) use ($placeholder) {
        if ($exception->getCode() !== 404) {
            \Bugsnag::exceptionHandler($exception);
        }

        if ($placeholder) {
            call_user_func($placeholder, $exception);
        }
    });
}

unset($_config);
