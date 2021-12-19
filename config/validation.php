<?php

return [
    /**
     * Collection of default error message by validation rule.
     * NOTE: Has lowest priority!
     *
     * @var array
     */
    'default' => [
        "confirm"                   => "Der Inhalt dieses Felds muss mit seinem Bestätigungs-Feld übereinstimmen!",
        "email"                     => "Bitte geben Sie eine valide E-Mail-Adresse ein!",
        "int"                       => "Dieses Feld erlaubt nur ganzzahlige Zahlen!",
        "max"                       => "Dieses Feld darf maximal :param Zeichen lang sein!",
        "min"                       => "Dieses Feld muss mindestens :param Zeichen lang sein!",
        "numeric"                   => "Dieses Feld erlaubt nur Zahlen!",
        "required"                  => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredIf"                => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredUnless"            => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredWith"              => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredWithAll"           => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredWithout"           => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredWithoutAll"        => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredIfMultiple"        => "Dieses Feld ist ein Pflicht-Feld!",
        "requiredUnlessMultiple"    => "Dieses Feld ist ein Pflicht-Feld!",
        "date"                      => "Dieses Feld muss ein gültiges Datum enthalten!",
        "alphaNum"                  => "Dieses Feld erlaubt nur Buchstaben und Zahlen!",
        "alpha"                     => "Dieses Feld erlaubt nur Buchstaben!",
        "alphaSpaces"               => "Dieses Feld darf nur Buchstaben und Leerzeichen enthalten!",
        "before"                    => "Das Datum dieses Feldes muss vor dem angegebenen Datum liegen!",
        "after"                     => "Das Datum dieses Feldes muss nach dem angegebenen Datum liegen!",
        "regex"                     => "Dieses Feld entspricht nicht dem vorgegebenen Format!",
        "notRegex"                  => "Dieses Feld darf NICHT dem angegeben Format entsprechen!",
        "same"                      => "Dieses Feld muss desselben Inhalt haben!",
        "different"                 => "Dieses Feld muss sich unterscheiden!",
    ],


    /**
     * Collection of error messages by validation rule and input field.
     * NOTE: Has medium priority!
     *
     * @var array
     */
    'field_rule' => [
        // 'rule' => [
        //     'input_key' => 'message',
        // ]
    ],


    /**
     * Collection of error messages by input field.
     * NOTE: Has highest priority!
     *
     * @var array
     */
    'field' => [
        // 'input_key' => 'message',
    ]
];
