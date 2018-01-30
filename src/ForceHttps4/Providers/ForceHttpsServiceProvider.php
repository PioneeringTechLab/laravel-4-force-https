<?php

namespace CSUNMetaLab\ForceHttps4\Providers;

use Illuminate\Support\ServiceProvider;

class ForceHttpsServiceProvider extends ServiceProvider
{
	public function register() {
		
	}

	public function boot() {
        $this->package('csun-metalab/laravel-4-force-https', 'forcehttps');
	}
}