<?php

namespace SdTech\ProjectInstaller\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $envPath;

    /**
     * @var string
     */
    private $envExamplePath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
        $this->envExamplePath = base_path('.env.example');
    }

    /**
     * Get the content of the .env file.
     *
     * @return string
     */
    public function getEnvContent()
    {
        if (!file_exists($this->envPath)) {
            if (file_exists($this->envExamplePath)) {
                copy($this->envExamplePath, $this->envPath);
            } else {
                touch($this->envPath);
            }
        }

        return file_get_contents($this->envPath);
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath()
    {
        return $this->envPath;
    }

    /**
     * Get the the .env.example file path.
     *
     * @return string
     */
    public function getEnvExamplePath()
    {
        return $this->envExamplePath;
    }

    /**
     * Save the edited content to the .env file.
     *
     * @param Request $input
     * @return string
     */
    public function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents($this->envPath, $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }

    /**
     * Save the form content to the .env file.
     *
     * @param Request $request
     * @return string
     */
    public function saveFileWizard(Request $request)
    {
        $results = trans('installer_messages.environment.success');

        $envFileData =
            'APP_NAME="' . ($request->app_name ?? 'OpmationMart') . "\"\n" .
            'APP_ENV=' . ($request->environment ?? 'local') . "\n" .
            'APP_KEY=' . 'base64:' . base64_encode(Str::random(32)) . "\n" .
            'APP_DEBUG=' . ($request->app_debug ?? 'true') . "\n" .
            'APP_TIMEZONE=' . ($request->app_timezone ?? 'UTC') . "\n" .
            'APP_URL=' . ($request->app_url ?? 'https://opmation-mart.test') . "\n\n" .

            'APP_LOCALE=' . ($request->app_locale ?? 'en') . "\n" .
            'APP_FALLBACK_LOCALE=' . ($request->app_fallback_locale ?? 'en') . "\n" .
            'APP_FAKER_LOCALE=' . ($request->app_faker_locale ?? 'en_US') . "\n\n" .

            'APP_MAINTENANCE_DRIVER=' . ($request->app_maintenance_driver ?? 'file') . "\n" .
            'APP_MAINTENANCE_STORE=' . ($request->app_maintenance_store ?? 'database') . "\n\n" .

            'BCRYPT_ROUNDS=' . ($request->bcrypt_rounds ?? '12') . "\n\n" .

            'LOG_CHANNEL=' . ($request->log_channel ?? 'stack') . "\n" .
            'LOG_STACK=' . ($request->log_stack ?? 'single') . "\n" .
            'LOG_DEPRECATIONS_CHANNEL=' . ($request->deprecations_channel ?? 'null') . "\n" .
            'LOG_LEVEL=' . ($request->log_level ?? 'debug') . "\n\n" .

            'DB_CONNECTION=' . ($request->database_connection ?? 'mysql') . "\n" .
            'DB_HOST=' . ($request->database_hostname ?? '127.0.0.1') . "\n" .
            'DB_PORT=' . ($request->database_port ?? '3306') . "\n" .
            'DB_DATABASE=' . ($request->database_name ?? 'opmation-mart') . "\n" .
            'DB_USERNAME=' . ($request->database_username ?? 'root') . "\n" .
            'DB_PASSWORD=' . ($request->database_password ?? '') . "\n\n" .

            'SESSION_DRIVER=' . ($request->session_driver ?? 'file') . "\n" .
            'SESSION_LIFETIME=' . ($request->session_lifetime ?? '120') . "\n" .
            'SESSION_ENCRYPT=' . ($request->session_encrypt ?? 'false') . "\n" .
            'SESSION_PATH=' . ($request->session_path ?? '/') . "\n" .
            'SESSION_DOMAIN=' . ($request->session_domain ?? 'null') . "\n\n" .

            'BROADCAST_CONNECTION=' . ($request->broadcast_driver ?? 'log') . "\n" .
            'FILESYSTEM_DISK=' . ($request->filesystem_disk ?? 'local') . "\n" .
            'QUEUE_CONNECTION=' . ($request->queue_driver ?? 'database') . "\n\n" .

            'CACHE_STORE=' . ($request->cache_driver ?? 'database') . "\n" .
            'CACHE_PREFIX=' . ($request->cache_prefix ?? '') . "\n\n" .

            'MEMCACHED_HOST=' . ($request->memcached_host ?? '127.0.0.1') . "\n\n" .

            'REDIS_CLIENT=' . ($request->redis_client ?? 'phpredis') . "\n" .
            'REDIS_HOST=' . ($request->redis_host ?? '127.0.0.1') . "\n" .
            'REDIS_PASSWORD=' . ($request->redis_password ?? 'null') . "\n" .
            'REDIS_PORT=' . ($request->redis_port ?? '6379') . "\n\n" .

            'MAIL_MAILER=' . ($request->mail_driver ?? 'smtp') . "\n" .
            'MAIL_HOST=' . ($request->mail_host ?? 'sandbox.smtp.mailtrap.io') . "\n" .
            'MAIL_PORT=' . ($request->mail_port ?? '2525') . "\n" .
            'MAIL_USERNAME=' . ($request->mail_username ?? '') . "\n" .
            'MAIL_PASSWORD=' . ($request->mail_password ?? '') . "\n" .
            'MAIL_ENCRYPTION=' . ($request->mail_encryption ?? 'tls') . "\n" .
            'MAIL_FROM_ADDRESS="' . ($request->mail_from_address ?? 'hello@example.com') . "\"\n" .
            'MAIL_FROM_NAME="${APP_NAME}"' . "\n\n" .

            'AWS_ACCESS_KEY_ID=' . ($request->aws_access_key_id ?? '') . "\n" .
            'AWS_SECRET_ACCESS_KEY=' . ($request->aws_secret_access_key ?? '') . "\n" .
            'AWS_DEFAULT_REGION=' . ($request->aws_default_region ?? 'us-east-1') . "\n" .
            'AWS_BUCKET=' . ($request->aws_bucket ?? '') . "\n" .
            'AWS_USE_PATH_STYLE_ENDPOINT=' . ($request->aws_use_path_style_endpoint ?? 'false') . "\n\n" .

            'VITE_APP_NAME="${APP_NAME}"' . "\n\n" .

            'OPENAI_API_KEY=' . ($request->openai_api_key ?? '') . "\n" .
            'OPENAI_ORGANIZATION=' . ($request->openai_organization ?? '') . "\n\n" .

            'SMS_PROVIDER=' . ($request->sms_provider ?? '') . "\n\n" .

            'TWILIO_SID=' . ($request->twilio_sid ?? '') . "\n" .
            'TWILIO_TOKEN=' . ($request->twilio_token ?? '') . "\n" .
            'TWILIO_FROM=' . ($request->twilio_from ?? '') . "\n\n" .

            'VONAGE_API_KEY=' . ($request->vonage_api_key ?? '') . "\n" .
            'VONAGE_API_SECRET=' . ($request->vonage_api_secret ?? '') . "\n\n" .

            'SSLC_STORE_ID=' . ($request->sslc_store_id ?? '') . "\n" .
            'SSLC_STORE_PASSWORD=' . ($request->sslc_store_password ?? '') . "\n" .
            'SSLC_STORE_CURRENCY=' . ($request->sslc_store_currency ?? 'BDT') . "\n" .
            'SSLC_ROUTE_SUCCESS=' . ($request->sslc_route_success ?? 'sslcommerz.payment.success') . "\n" .
            'SSLC_ROUTE_FAILURE=' . ($request->sslc_route_failure ?? 'sslcommerz.payment.failure') . "\n" .
            'SSLC_ROUTE_CANCEL=' . ($request->sslc_route_cancel ?? 'sslcommerz.payment.cancel') . "\n" .
            'SSLC_ROUTE_IPN=' . ($request->sslc_route_ipn ?? 'sslcommerz.payment.ipn') . "\n" .
            'SSLC_ALLOW_LOCALHOST=' . ($request->sslc_allow_localhost ?? 'true') . "\n\n" .

            'TELESCOPE_ENABLED=' . ($request->telescope_enabled ?? 'false') . "\n";

        try {
            file_put_contents($this->envPath, $envFileData);
            if(config('installer.checkPurchaseCode') == true) {
                file_put_contents(storage_path('.envapplicationKeyforverifywhichcomesfromenv'), json_encode(['license' => Cookie::get('addenvparkey')]));
            }
        } catch (Exception $e) {
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
