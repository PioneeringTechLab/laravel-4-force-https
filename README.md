# Laravel Force HTTPS Package
A small Composer package for Laravel 4.2 to force HTTPS in the URL via a route filter.

## Table of Contents

* [Installation](#installation)
    * [Composer, Environment, and Service Provider](#composer-environment-and-service-provider)
        * [Composer](#composer)
        * [Environment](#environment)
        * [Service Provider](#service-provider)
    * [Route Filter Installation](#route-filter-installation)
    * [Publish Everything](#publish-everything)
* [Required Environment Variables](#required-environment-variables)
* [Route Filter](#route-filter)
    * [Force HTTPS Filter](#force-https-filter)
* [Resources](#route-filters)

## Installation

### Composer, Environment, and Service Provider

#### Composer

To install from Composer, use the following command:

```
composer require csun-metalab/laravel-4-force-https
```

#### Environment

Now, add the following line(s) to your `.env.php` file:

```
'FORCE_HTTPS' => true,
```

This will enable the forcing functionality.

#### Service Provider

Add the service provider to your `providers` array in `app/config/app.php` in Laravel as follows:

```
'providers' => [
   //...

   'CSUNMetaLab\ForceHttps4\Providers\ForceHttpsServiceProvider',

   //...
],
```

### Route Filter Installation

Add the following code to the `App::after()` clause in `app/filters.php` to apply it to all requests the application receives:

```
App::after(function($request, $response)
{
  //...

  // only attempt to force HTTPS if the package has been configured
  // to do so
  if(Config::get('forcehttps::force_https')) {
      // check how the absolute URL in the request looks
      $url = strtolower(url($request->server("REQUEST_URI")));
      $https = $request->server("HTTPS");
      if(empty($https) || strtolower($https) == "off") {
          // take SSL termination behind a proxy into account
          if(!starts_with($url, 'https:')) {
              // replace the protocol and then return a redirect
              return Redirect::to(str_replace("http:", "https:", $url));
          }
      }
  }

  //...
});
```

### Publish Everything

Finally, run the following Artisan command to publish everything:

```
php artisan config:publish csun-metalab/laravel-4-force-https
```

The following assets are published:

* Configuration (tagged as `config`) - these go into your `config` directory

## Required Environment Variables

You added an environment variable to your `.env.php` file that controls the protocol the application traffic uses.

### FORCE_HTTPS

Whether to force HTTPS on all URLs or not. Default is `false` to prevent any unexpected issues from forcing HTTPS directly upon installation.

## Route Filter

### Force HTTPS Filter

The route filter you added in `app/filters.php` performs the following steps:

1. Checks to see if the application configuration requests traffic to be forced over HTTPS
2. If so, it performs the following steps:
    1. Resolves the request URI as an absolute URL so it can also see the protocol
    2. Checks to see if the `HTTPS` server variable is a non-empty value or set as `off`
    2. If the protocol isn't already `https:` then it replaces it with `https:` and returns a redirect
3. If not, it passes the request instance to the next configured middleware in the pipeline

## Resources

### Route Filters

* [Route Filters in Laravel 4.2](https://laravel.com/docs/4.2/routing#route-filters)