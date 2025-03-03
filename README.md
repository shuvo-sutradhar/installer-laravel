
## Requirements

* [Laravel 11+]

## Installation

- Add in `composer.json`
```json
"require": {
	"Codeshaper/project-installer": "dev-main",
},
```
- Add vcs url for this package to your `composer.json` file:

```json
"repositories": {
        {
            "type": "vcs",
            "url": "git@github.com:shuvo-sutradhar/installer-laravel"
        }
    },
```
- Run `composer update Codeshaper/project-installe` to install the package.

- Run `php artisan vendor:publish --tag=projectinstaller` to publish the assets.

  
```
'env_path' => [
  'env_token' => 'your envato token here',
  'env_url_path' => 'https://api.envato.com/v1/market/private/user/verify-purchase:'
  ]
```

- 'checkPurchaseCode' => true, 

- Wrap all your routes in `['is_verified', 'need_to_install']` middleware. For example:

```php
public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            return $next($request);
        }

        return redirect(url('install'));
    }
```

- Run `php artisan optimize:clear`.

## Screenshots

###### Installer
![Laravel web installer | Step 1](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/1-welcome.jpg)
![Laravel web installer | Step 2](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/2-requirements.jpg)
![Laravel web installer | Step 3](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/3-permissions.jpg)
![Laravel web installer | Step 4 Menu](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/4-environment.jpg)
![Laravel web installer | Step 4 Classic](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/4a-environment-classic.jpg)
![Laravel web installer | Step 4 Wizard 1](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/4b-environment-wizard-1.jpg)
![Laravel web installer | Step 4 Wizard 2](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/4b-environment-wizard-2.jpg)
![Laravel web installer | Step 4 Wizard 3](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/4b-environment-wizard-3.jpg)
![Laravel web installer | Step 5](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/install/5-final.jpg)

###### Updater
![Laravel web updater | Step 1](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/update/1-welcome.jpg)
![Laravel web updater | Step 2](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/update/2-updates.jpg)
![Laravel web updater | Step 3](https://s3-us-west-2.amazonaws.com/github-project-images/laravel-installer/update/3-finished.jpg)

## License

Laravel Web Installer is licensed under the MIT license. Enjoy!


