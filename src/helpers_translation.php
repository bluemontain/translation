<?php

use BlueMountainTeam\Translation\Facades\TranslationStatic;

use Illuminate\Database\Eloquent\Model;

if (!function_exists('_t')) {
    /**
     * Shorthand function for translating text.
     *
     * @param string $text
     * @param array  $replacements
     * @param string $toLocale
     *
     * @return string
     */
    function _t($text, $toLocale = null, $parameters = null)
    {
        return TranslationStatic::translate($text, $toLocale, $parameters);
    }
}

if (!function_exists('d')) {
    /**
     * Shorthand function for translating text.
     *
     * @param string $text
     * @param array  $replacements
     * @param string $toLocale
     *
     * @return string
     */
    function d($var)
    {
        dump($var);
    }
}

if (!function_exists('previousUrlChangedLocale')) {
    /**
     * Shorthand function for getting previous url with another locale
     * for locale change redirections
     * for instance if previous url is http://www.google.fr/en/hello/create
     * previousUrlChangedLocale('fr') will returns /fr/hello/create
     * @param string $locale
     *
     * @return string
     */
    function previousUrlChangedLocale($locale)
    {
        $previous = parse_url(URL::previous());
        $segments = explode('/', $previous['path']);
        $segments_withoutlocale = array_splice($segments, 2);
        return '/'.$locale.'/'.implode('/', $segments_withoutlocale);
    }
}