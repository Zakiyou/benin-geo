<?php

namespace Zakiyou\BeninGeo;

use Illuminate\Support\ServiceProvider;

class BeninGeoServiceProvider extends ServiceProvider
{
    /**
     * Enregistre le service dans le container Laravel
     */
    public function register()
    {
        $this->app->singleton('benin-geo', function () {
            return new BeninGeo();
        });

      
    }

    /**
     * Méthode appelée après tous les services sont enregistrés
     */
    public function boot()
    {
        
    }
}
