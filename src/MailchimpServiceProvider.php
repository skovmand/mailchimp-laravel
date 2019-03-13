<?php namespace Skovmand\Mailchimp;

use Illuminate\Support\ServiceProvider;
use Mailchimp;

class MailchimpServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Set up the publishing of configuration
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/mailchimp.php';
        $app = $this->app;
        
        if (class_exists('Illuminate\Foundation\Application') && $app instanceof LaravelApplication && $app->runningInConsole()) {
            $this->publishes([$configPath => config_path('mailchimp.php')]);
            $this->mergeConfigFrom($configPath, 'mailchimp');
        } else if ( class_exists('Laravel\Lumen\Application', false) ) {
            $app->configure('mailchimp');
        }
    }

    /**
     * Register the Mailchimp Instance to be set up with the API-key.
     * Then, the IoC-container can be used to get a Mailchimp instance ready for use.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Mailchimp', function($app) {
            $config = $app['config']['mailchimp'];
            return new Mailchimp($config['apikey']);
        });
    }
}