WEBSITE
DR
91
AR
1.1K
RP
22.1M
RD
125.4K
ST
38.7K
KW
137.1K
PAGE
UR
0
RP
0
RD
0
ST
0
KW
0

Web vitals
<?php

namespace Spatie\PartialCache;

use Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Str;
class PartialCacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/config/partialcache.php' => config_path('partialcache.php'),
        ], 'config');

        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
            $directive = config('partialcache.directive');
            
            
            $bladeCompiler->directive($directive, function ($expression) {
            if (Str::startsWith($expression, '(')) {
                $expression = substr($expression, 1, -1);
            }

            return "<?php echo app()->make('partialcache')
                ->cache(Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']), {$expression}); ?>";
        });

             
        });
        
        
       
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../resources/config/partialcache.php', 'partialcache');
         

        
        $this->app->singleton(PartialCache::class, PartialCache::class);
        $this->app->alias(PartialCache::class, 'partialcache');
    }
}
