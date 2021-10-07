<?php
namespace App\Classes\Settings;

use App\Classes\CurrencyConverter;
use App\Classes\DateTimeConverter;
use App\Classes\JSTranslator;
use App\Repositories\BusinessSettingRepository;
use App\Repositories\CompanyInfoRepository;
use App\Repositories\Repository;
use App\Repositories\SystemSettingRepository;
use Exception;
use App\Repositories\BusinessRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;

class SettingManager
{
    /**
     * Laravel App Instance;
     * @var Application app
     */
    private $app;

    /**
     * Contains instance of repo loaded
     * @var array $repos
     */
    private $repos = [];
    /**
     * Available Repository loaded to this class;
     * @var array $repoClasses
     */
    private $repoClasses = [
        'business' => BusinessRepository::class,
        'settings' => SystemSettingRepository::class,
        'company' => CompanyInfoRepository::class,
        'options' => BusinessSettingRepository::class
    ];

    /**
     * Storing option-value
     * @var array $items
     */
    private $items = [];


    protected $translator;
    protected $currencyConverter;
    protected $datetimeConverter;

    const LOCALES = [
        'vietnamese' => 'vi',
        'english' => 'en'
    ];

    public function __construct(Application $app, $businessId = null) {
        $this->app = $app;
        if ($this->_isConsole() && $businessId) {
            return $this->loadSettingInConsole($businessId);
        }
        $domain = request()->getHost();
        $business = $this->findBusinessFromDomain($domain);
        if (!$business) {
            Log::error("Business [$domain] not exists!");
            return abort(404, 'Domain not supported!');
        }
        $this->loadSettings($business);
        return $this;
    }

    public function loadSettingInConsole($businessId) {
        if (!$businessId) {
            throw new Exception("Business isn't registered with system!");
        }
        $business = $this->findBusinessFromId($businessId);
        $this->loadSettings($business);
        return $this;
    }

    public static function setBusinessId($businessId) {
        app()->singleton('hrm', function ($app) use ($businessId) {
            return new SettingManager($app, $businessId);
        });
    }

    public static function makeSetting($businessId) {
        return new SettingManager(app(), $businessId);
    }

    public function get($key, $default = null) {
        return $this->items[$key] ?? $default;
    }

    public function all() {
        return $this->items;
    }

    protected function _isConsole() {
        return $this->app->runningInConsole();
    }

    protected function findBusinessFromDomain($domain) {
        return $this->getRepo('business')->getBusinessFromDomain($domain);
    }

    protected function findBusinessFromId($businessId) {
        return $this->getRepo('business')->getBusinessFromId($businessId);
    }

    protected function loadSettings($business) {
        $this->loadSystemSetting($business->setting_id);
        $this->loadCompanyInfo($business->company_info_id);
        $this->loadSettingOption($business->id);
        $this->pushItem('businessId', $business->id);
    }

    private function loadSystemSetting($setting_id) {
        $setting = $this->getRepo('settings')->getSettingFromId($setting_id);
        $this->pushItems($setting->toArray());
    }

    private function loadCompanyInfo($company_info_id) {
        $company_info = $this->getRepo('company')->getSettingFromId($company_info_id);
        $this->pushItems($company_info->toArray());
    }

    private function loadSettingOption($business_id) {
        $options = $this->getRepo('options')->getBusinessOptions($business_id);
        foreach($options as $option) {
            $this->pushItem($option->option, $option->value);
        }
    }

    public function applySettings() {
        $this->setLocale();
        $this->setTimeZone();
        $this->loadCurrency();
        $this->loadDatetime();
    }

    public function setLocale() {
        $locale = self::LOCALES[$this->get('default_language')] ?? config('app.fallback_locale');
        $this->app->setLocale($locale);
        $this->translator = new JSTranslator($locale);
    }

    public function setTimeZone() {
        $timezone = $this->get('system_timezone');
        $this->app->config->set('app.timezone', $timezone);
    }

    public function loadCurrency() {
        $default_currency = $this->get('default_currency');
        $currency_position = $this->get('currency_position');
        $type = $this->get('show_currency');
        $this->currencyConverter = new CurrencyConverter($default_currency, $currency_position, $type);
    }

    public function getCurrencyConverter() {
        if (!$this->currencyConverter) {
            $this->loadCurrency();
        }
        return $this->currencyConverter;
    }

    public function loadDatetime() {
        $date_format = $this->get('date_format_xi');
        $this->datetimeConverter = new DateTimeConverter($date_format);
    }
    public function getDateTimeConverter() {
        if (!$this->datetimeConverter) {
            $this->loadDatetime();
        }
        return $this->datetimeConverter;
    }

    public function getJSDateFormat() {
        return $this->getDateTimeConverter()->getJSDateFormat();
    }

    /**
     * Get Currencies array for JS
     **/
    public function getCurrencies() {
        return CurrencyConverter::CURRENCIES;
    }

    /**
     * Get JS Translation strings
     **/
    public function getTranslation() {
        return $this->translator->getItems();
    }

    /**
     * Retrieve Repository class registered
     *
     * @param $repository
     * @return Repository
     * @throws Exception
     */
    private function getRepo($repository) {
        $repo = $this->repos[$repository] ?? null;
        if (!$repo) {
            return $this->loadRepo($repository);
        }
    }

    protected function pushItems($key_values = []) {
        $this->items = array_merge($this->items, $key_values);
    }

    protected function pushItem($key, $value) {
        $this->items[$key] = $value;
    }

    /**
     * Load Repo registered
     *
     * @param $repository
     * @return Repository
     * @throws Exception Repository not register
     */
    private function loadRepo($repository) {
        $shortRepoNames = array_keys($this->repoClasses);
        if (!in_array($repository, $shortRepoNames)) {
            throw new Exception("Repository [$repository] not registered!");
        }
        $repo = app()->make($this->repoClasses[$repository]);
        $this->repos[$repository] = $repo;
        return $repo;
    }

    public static function getOption($key, $default = null) {
        return app('hrm')->get($key, $default);
    }

    public static function getOptions() {
        return app('hrm')->all();
    }

    public static function getInstance() {
        return app('hrm');
    }

    public static function getCompanyName() {
        return self::getOption('company_name', env('APP_NAME'));
    }

    public function getCompanyId() {
        return $this->get('company_info_id');
    }

    /* --- Handler functions for system running; */

    /**
     * Is enable SSO
     * @return bool
     */
    public function isSSO() {
        return $this->moduleSSOEnabled();
    }

    /**
     * Demeter module recruitment currently is enable
     * @return bool
     */
    public function moduleRecruitmentEnabled() {
        return $this->get('module_recruitment') === "true";
    }

    public function isEnableCurrentYear() {
        return $this->get('enable_current_year') === "year";
    }

    public function moduleSSOEnabled() {
        return $this->get('module_sso') === "true";
    }
}
