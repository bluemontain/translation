<?php

namespace BlueMountainTeam\Translation\Traits;

use BlueMountainTeam\Translation\Models\Locale;
/**
 * Trait LocaleHandler
 * Must be used by translations services which have to deal with locales selection from Url, config, cookie, session etc
 * @package BlueMountainTeam\Translation\Traits
 */
trait LocaleHandler
{

    protected   $locale = '';
    protected   $localeSource = ''; // indicates how the locale was chosen
    protected   $config;
    protected   $request;
    private     $cacheTime = 20;

    /**
     * Retrieves the locale from many sources
     * Already set > session > prefix url > browser header > default conf
     * @return string
     */
    public function getLocale($request = null)
    {
        // if already defined returns it
        if (!empty($this->locale)) {
            return $this->locale;
        }

        // first we look on the session
        if(!empty(session('locale'))) {
            $this->locale = session('locale');
            $this->localeSource = 'session';
            return $this->locale;
        }

        // if not found we look on the prefix
        $routePrefix = $this->getRoutePrefix();
        if(!empty($routePrefix)) {
            // we check if route prefix is in allowed locales
            if (array_key_exists($routePrefix, $this->getConfigAllowedLocales())) {
                $this->locale = $routePrefix;
                $this->localeSource = 'prefix';
                return $this->locale;
            }
        }

        // if not found we look on browser request headers
        if($request != null) {
            $http_header = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (!empty($http_header)) {
                // we check if route prefix is in allowed locales
                if (array_key_exists($http_header, $this->getConfigAllowedLocales())) {
                    $this->locale = $http_header;
                    $this->localeSource = 'header';
                    return $this->locale;
                }
            }
        }

        // if not found we look on default config
        $this->locale = $this->getConfigDefaultLocale();
        $this->localeSource = 'default';
        return $this->locale;
    }

    public function setLocale($code = '')
    {
        $this->locale = $code;
    }

    public function getRoutePrefix()
    {
        $locale = $this->request->segment($this->getConfigRequestSegment());

        $locales = $this->getConfigLocales();

        if (is_array($locales) && in_array($locale, array_keys($locales)))
            return $locale;
        return '';
    }

    /**
     * Returns the array of configuration locales.
     *
     * @return array
     */
    public function getConfigLocales()
    {
        return $this->config->get('translation.locales');
    }

    /**
     * Returns the locale model from the configuration.
     *
     * @return string
     */
    public function getConfigLocaleModel()
    {
        return $this->config->get('translation.models.locale', Locale::class);
    }

    /**
     * Returns the array of configuration allowed locales.
     *
     * @return array
     */
    public function getConfigAllowedLocales()
    {
        return $this->config->get('translation.allowed_locales');
    }

    /**
     * Returns the array of allowed locales in config which also exist in current database
     */
    public function getExistingAllowedLocales()
    {
        $existing_allowed = [];
        foreach($this->config->get('translation.allowed_locales') as $key => $allowed_locale) {
            $idLocale = $this->getLocaleIdByCode($key);
            if($idLocale != null)
                $existing_allowed[$key] = $allowed_locale;
        }
        return $existing_allowed;
    }
    /**
     * Returns a the english name of the locale code entered from the config file.
     *
     * @param string $code
     *
     * @return string
     */
    public function getConfigLocaleByCode($code)
    {
        $locales = $this->getConfigLocales();

        if (is_array($locales) && array_key_exists($code, $locales)) {
            return $locales[$code];
        }
        return $code;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getLocaleIdByCode($code)
    {
        $locale = $this->localeModel->where([
            'code' => $code,
            'activ' => 1
        ])->first();
        if(!$locale)
            return null;
        else
            return $locale->id;
    }

    /**
     * Returns the default locale from the configuration.
     *
     * @return string
     */
    public function getConfigDefaultLocale()
    {
        return $this->config->get('translation.default_locale', 'fr');
    }

    /**
     * Returns the default locale id from the configuration and database
     *
     * @return string
     */
    public function getConfigDefaultLocaleId()
    {
        return $this->getLocaleIdByCode($this->getConfigDefaultLocale());
    }

    /**
     * Retrieves or creates a locale from the specified code.
     *
     * @param string $code
     *
     * @return Model
     */
    public function firstOrCreateLocale($code)
    {
        $name = $this->getConfigLocaleByCode($code);
        $locale = $this->localeModel->firstOrCreate([
            'code' => $code,
            'name' => $name,
            'activ' => 1
        ]);
        return $locale;
    }

    /**
     * Returns the request segment to retrieve the locale from.
     *
     * @return int
     */
    public function getConfigRequestSegment()
    {
        return $this->config->get('translation.request_segment', 1);
    }

    public function getAppLocale()
    {
        return $this->config->get('app.locale');
    }

    public function getLocaleSource() {
        return $this->localeSource;
    }

}