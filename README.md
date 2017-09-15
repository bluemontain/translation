# Translation

Package for static and dynamic translation within Laravel projects

## Installation

Require the translation package 

    composer require BlueMountainTeam/Translation/translation

Add the service provider to your `config/app.php` config file

    'BlueMountainTeam\Translation\TranslationServiceProvider',
    
Add the facade to your aliases in your `config/app.php` config file

    'translationlib' => 'BlueMountainTeam\Translation\Facades\Translation',
    
Publish the migrations

    php artisan vendor:publish --provider="BlueMountainTeam\Translation\TranslationServiceProvider"
    
Run the migrations

    php artisan migrate

## Usage

Anywhere in your application, either use the the shorthand function 

    _t('Translate me!')
    

## Routes

Include inside your `app/Http/Kernel.php` file, insert
the translation middleware:

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        
        // Insert Locale Middleware
        'locale' => \BlueMountainTeam\Translation\Middlewares\TranslationMiddleware::class
    ];

Now, in your `app/Http/routes.php` file, insert the middleware and the following Translation method in the route
group prefix like so:

    Route::group(['prefix' => Translation::getRoutePrefix(), 'middleware' => ['locale']], function()
    {
        Route::get('home', function ()
        {
            return view('home');
        });
    });

You should now be able to access routes such as:

    http://localhost/home
    http://localhost/en/home
    http://localhost/fr/home