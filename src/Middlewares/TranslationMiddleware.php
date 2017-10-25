<?php

namespace BlueMountainTeam\Translation\Middlewares;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use BlueMountainTeam\Translation\Models\Locale;
use BlueMountainTeam\Translation\Facades\TranslationStatic;

class TranslationMiddleware
{
    public function __construct(Application $app, Redirector $redirector, Request $request)
    {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }
    /**
     * Handle an incoming request.
     *
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->ajax() || (PHP_SAPI == 'cli' && strpos($_SERVER['argv'][0], 'phpunit')))
            return $next($request);

        $locale = TranslationStatic::getLocale($request);
        $this->app->setLocale($locale);

        $segment = $this->request->segment(TranslationStatic::getConfigRequestSegment());
        if (in_array($segment, TranslationStatic::getConfigUntranslatableActions()))
            return $next($request);

        if ($request->path() == '/' && !empty(session('locale'))) {
            $request->session()->reflash();
            return $this->redirector->to('/' . $locale);
        }

        if (!in_array($segment, array_keys(TranslationStatic::getConfigAllowedLocales()))) {
            $request->session()->reflash();
            return $this->redirector->to('/' . $locale . '/' . $request->path());
        }

        return $next($request);
    }
}