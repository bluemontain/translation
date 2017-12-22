# Translation

Package for static and dynamic translation within Laravel projects

## Installation

Require the translation package 

    composer require bluemountainteam/translation

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
    
## Translatable Models
    
If you want your models to handle multiple dynamic translations use the Translatable trait
    
 
    use BlueMountainTeam\Translation\Traits\TranslatableModel;
    
    use TranslatableModel;
    
    MyModel::pluckTrad();

## Routes

Include inside your `app/Http/Kernel.php` file, insert
the translation middleware:


    protected $middlewareGroups = [
            'web' => [
                \App\Http\Middleware\EncryptCookies::class,
                ...
                \BlueMountainTeam\Translation\Middlewares\TranslationMiddleware::class,

Now, in your `app/Http/routes.php` use prefix

    Route::group(['prefix' => Translation::getRoutePrefix()], function()
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