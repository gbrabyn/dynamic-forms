<?php
use Laminas\I18n\Translator\Resources;

$laminasValidatorTranslations = include(Resources::getBasePath().'en/Laminas_Validate.php');

return array_merge($laminasValidatorTranslations, [
    'formErrors' => 'Please fix the errors in the form below.',
    'inputNotAllowed' => 'Not an approved value',
    'inputNotAllowedValues' => 'Contains a value not approved',
    'inputRequired' => 'Required',
    'inputNotInteger' => 'Must be an integer',
    'inputNotPositiveInteger' => 'Must be a positive integer',
    'inputNotNumber' => 'Must be a number',
    'inputDuplicated' => 'Duplicated',
    'inputCsrfFail' => 'Failed security checks! Possibly due to timeout. Try reloading this page and submitting the form again.',
    'inputReCaptchaFail' => 'Failed to prove you are human',
    'inputNotScalar' => 'Must be a string or number',
    'inputNotSequentialArray' => 'Not an array with sequential keys',
    'inputInvalidHexColor' => 'Color must be in hexidecimal format "#ffffff"',
    'inputInvalidTime' => 'Not a valid time in format HH:mm, e.g. "23:59"',
    'inputCorrupted' => 'Input is corrupted',
    'numItemsNotBetween' => 'Number of items must be between ${min} and ${max}',
    'numItemsLessThan' => 'Must be ${min} or more items',
    'numItemsGreaterThan' => 'Maximum of ${max} items',
]);
