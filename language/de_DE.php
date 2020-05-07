<?php
use Laminas\I18n\Translator\Resources;

$laminasValidatorTranslations = include(Resources::getBasePath().'de/Laminas_Validate.php');

return array_merge($laminasValidatorTranslations, [
    'formErrors' => 'Bitte korrigieren Sie Ihre Eingabe an den rot markierten Stellen.',
    'inputNotAllowed' => 'Keine genehmigte Eingabe',
    'inputNotAllowedValues' => 'Enthält eine nicht genehmigte Eingabe',
    'inputRequired' => 'Erforderlich',
    'inputNotInteger' => 'Muss eine ganze Zahl sein',
    'inputNotPositiveInteger' => 'Muss eine positive ganze Zahl sein',
    'inputNotNumber' => 'Muss eine Zahl sein',
    'inputDuplicated' => 'Dubliziert',
    'inputCsrfFail' => 'Das gesendete Formular stammt nicht von der erwarteten Webseite',
    'inputReCaptchaFail' => 'Konnte nicht mit hinreichender Sicherheit als Mensch identifiziert werden',
    'inputNotScalar' => 'Muss eine Zeichenfolge oder Zahl sein',
    'inputNotSequentialArray' => 'Kein Array mit sequentiellen Schlüsseln',
    'inputInvalidHexColor' => 'Die Farbe muss im Hexadezimalformat sein "#ffffff"',
    'inputInvalidTime' => 'Keine gültige Zeiteingabe im Format HH:mm, z.B. "23:59"',
    'inputCorrupted' => 'Eingabe ist beschädigt',
    'numItemsNotBetween' => 'Anzahl der Artikel muss zwischen ${min} und ${max} liegen',
    'numItemsLessThan' => 'Muss ${min} oder mehr Artikel sein',
    'numItemsGreaterThan' => 'Maximum von ${max} Artikeln',
]);
