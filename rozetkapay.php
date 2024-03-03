<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2015 PrestaShop SA
 * @license http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *           International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
    require_once __DIR__ . '/classes/php_sdk_simple.php';
    require_once __DIR__ . '/classes/phpqrcode.php';
}

use Prestashop\ModuleLibMboInstaller\DependencyBuilder;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;
use PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException;
use PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer;

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class Rozetkapay extends PaymentModule
{
    protected $config_form = false;

    private $container;

    private $settingList = [
        'ROZETKAPAY_LOGIN' => '',
        'ROZETKAPAY_PASSWORD' => '',
        'ROZETKAPAY_QRCODE' => 0,
        'ROZETKAPAY_SEND_DATA_CUSTOMER' => 0,
        'ROZETKAPAY_SEND_DATA_PRODUCT' => 0,
        'ROZETKAPAY_ORDER_STATUS_SUCCESS' => 12,
        'ROZETKAPAY_ORDER_STATUS_FAILURE' => 8,
        'ROZETKAPAY_VIEW_NAME_DEFAULT' => 1,
        'ROZETKAPAY_VIEW_NAME' => [],
        'ROZETKAPAY_VIEW_ICON' => 1,
        'ROZETKAPAY_LIVE_MODE' => 1,
        'ROZETKAPAY_LOG' => 0,
    ];

    public $logo = '/modules/rozetkapay/logo.png';

    public $bootstrap = true;

    private $validModule = false;

    public function __construct()
    {
        $this->name = 'rozetkapay';
        $this->tab = 'payments_gateways';
        $this->version = '3.0.1';
        $this->author = 'RozetkaPay';
        $this->need_instance = 0;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('RozetkaPay');

        $this->description = $this->l('RozetkaPay Integration for your PrestaShop');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        $this->ps_versions_compliancy = ['min' => '8.x', 'max' => _PS_VERSION_];
        $this->php_versions_compliancy = ['min' => '7.2'];

        if ($this->container === null) {
            $this->container = new ServiceContainer(
                $this->name,
                $this->getLocalPath()
            );
        }
        
    }

    public function registerHooks()
    {
        $this->registerHook('displayPaymentReturn');
        $this->registerHook('paymentOptions');
        $this->registerHook('displayAdminOrderContentOrder');
    }

    public function install()
    {
        $instance = ModuleManagerBuilder::getInstance();

        if ($instance == null) {
            throw new ErrorException('No ModuleManagerBuilder instance');
        }

        $moduleManager = $instance->build();

        if (!$moduleManager->isInstalled('ps_eventbus')) {
            $moduleManager->install('ps_eventbus');
        } elseif (!$moduleManager->isEnabled('ps_eventbus')) {
            $moduleManager->enable('ps_eventbus');
        }

        $moduleManager->upgrade('ps_eventbus');

        return parent::install() &&
                $this->registerHook('displayPaymentReturn') &&
                $this->registerHook('paymentOptions') &&
                $this->registerHook('displayAdminOrderContentOrder');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function getContent()
    {

        $action = Tools::getValue('action');
        $extFun = Tools::getValue('extfun');

        if (!empty($extFun)) {
            $this->{$extFun}();
        }

        if (!empty($extFun)) {
            $this->{$extFun}();
        }

        if ($action == "payIonfo") {
            $this->payIonfo();
            return;
        }

        if ($action == "payRefund") {
            $this->payRefund();
            return;
        }

        if (Tools::isSubmit('submitSetting')) {

            foreach ($this->settingList as $key => $default) {
                $value = Tools::getValue($key);
                if ($key == "ROZETKAPAY_VIEW_NAME") {
                    if (is_array($value)) {
                        foreach ($value as $code => $value_) {
                            Configuration::updateValue($key . strtoupper("_" . $code), $value_);
                        }

                        continue;
                    }
                }

                if ($value === false) {
                    Configuration::updateValue($key, $default);
                } else {
                    if ($key == "ROZETKAPAY_LOGIN" || $key == "ROZETKAPAY_PASSWORD") {
                        $value = trim($value);
                    }
                    Configuration::updateValue($key, $value);
                }
            }

        }

        $mboInstaller = new DependencyBuilder($this);

        if(!$mboInstaller->areDependenciesMet()) {
            $dependencies = $mboInstaller->handleDependencies();
            $this->smarty->assign('dependencies', $dependencies);
            return $this->display(__FILE__, 'views/templates/admin/dependency_builder.tpl');
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $moduleManager = ModuleManagerBuilder::getInstance()->build();
        $accountsService = null;

        try {
            $accountsFacade = $this->getService('rozetkapay.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        } catch (InstallerException $e) {
            $accountsInstaller = $this->getService('rozetkapay.ps_accounts_installer');
            $accountsInstaller->install();
            $accountsFacade = $this->getService('rozetkapay.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        }

        try {
            Media::addJsDef([
                'contextPsAccounts' => $accountsFacade->getPsAccountsPresenter()
                    ->present($this->name),
            ]);
            $this->context->smarty->assign('urlAccountsCdn', $accountsService->getAccountsCdn());
        } catch (Exception $e) {
            $this->context->controller->errors[] = $e->getMessage();
            return '';
        }

        if ($moduleManager->isInstalled("ps_eventbus")) {
            $eventbusModule =  \Module::getInstanceByName("ps_eventbus");
            if (version_compare($eventbusModule->version, '1.9.0', '>=')) {
                $eventbusPresenterService = $eventbusModule->getService('PrestaShop\Module\PsEventbus\Service\PresenterService');
                $this->context->smarty->assign('urlCloudsync', "https://assets.prestashop3.com/ext/cloudsync-merchant-sync-consent/latest/cloudsync-cdc.js");
                Media::addJsDef([
                    'contextPsEventbus' => $eventbusPresenterService->expose($this, ['info', 'modules', 'themes'])
                ]);
            }
        }

        $billingFacade = $this->getService('rozetkapay.ps_billings_facade');
        $partnerLogo = $this->getLocalPath() . 'logo.png';

        Media::addJsDef($billingFacade->present([
            'logo' => $partnerLogo,
            'tosLink' => 'https://www.prestashop.com/en/prestashop-account-terms-conditions',
            'privacyLink' => 'https://www.prestashop.com/en/privacy-policy',
            'emailSupport' => 'ecomsupport@rozetkapay.com',
        ]));

        $this->context->smarty->assign('urlBilling', "https://unpkg.com/@prestashopcorp/billing-cdc/dist/bundle.js");
        $listLanguages = Language::getLanguages();

        foreach ($this->settingList as $key => $default) {
            if($key == "ROZETKAPAY_VIEW_NAME") {
                $languages_vals = [];
                foreach ($listLanguages as $language) {
                    $languages_vals[$language['iso_code']] =
                            Configuration::get($key ."_" . strtoupper($language['iso_code']));
                }
                $this->context->smarty->assign($key, $languages_vals);

                continue;
            }
            $this->context->smarty->assign($key, Configuration::get($key));
        }

        $this->context->smarty->assign('urlLogClear', $this->getAdminLink('logClear'));
        $this->context->smarty->assign('urlLogDownload', $this->getAdminLink('logDownload'));
        $this->context->smarty->assign('urlLogRefresh', $this->getAdminLink('logRefresh'));
        $this->context->smarty->assign('order_statuses', OrderState::getOrderStates((int) Configuration::get('PS_LANG_DEFAULT')));
        $this->context->smarty->assign('moduleVersion', $this->version);
        $this->context->smarty->assign('SDKVersion', (string) (\RozetkaPaySDK::versionSDK));
        $this->context->smarty->assign('languages', $listLanguages);
        $this->context->smarty->assign('error_login', false);
        $this->context->smarty->assign('error_password', false);
        $this->context->smarty->assign('urlBilling', "https://unpkg.com/@prestashopcorp/billing-cdc/dist/bundle.js");
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output;
    }

    public function getService($serviceName)
    {
        return $this->container->getService($serviceName);
    }

    protected function getAdminLink($action = '', $params = [])
    {
        $param_string = '&' . $this->name;
        if (!empty($action)) {
            $param_string .= '&action=' . $action;
        }

        foreach ($params as $pK => $pV) {
            $param_string .= '&' . $pK . '=' . $pV;
        }

        return Context::getContext()->link->getAdminLink('AdminModules', true) . $param_string;
    }
    
    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hookDisplayAdminOrderContentOrder($params)
    {

        if ($params['order']->payment !== "RozetkaPay") {
            return '';
        }
        
        $id_cart = (int) $params['order']->id_cart;

        $id_order = Tools::getValue('id_order');

        if ($id_order != $id_cart) {
            $id_order = $id_cart;
        }

        $this->context->smarty->assign('id_order', $id_order);
        $this->context->smarty->assign('urlRayInfo', $this->getAdminLinkA1('payIonfo'));
        $this->context->smarty->assign('urlPayRefund', $this->getAdminLinkA1('payRefund'));

        $this->context->smarty->force_compile = true;

        $order->addInfo('custom_info', 'Це додаткова інформація, додана модулем.');
        return $this->display('rozetkapay', 'rozetkapay_order.tpl');
    }

    public function hookPaymentReturn($params)
    {
        if (!$this->isPrestaShop16()) {
            return;
        }

        if (!$this->validModule) {
            return;
        }

        if ($this->active == false) {
            return;
        }

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR')) {
            $this->smarty->assign('status', 'ok');
        }

        $this->smarty->assign([
            'id_order' => $order->id,
            'reference' => $order->reference,
            'params' => $params,
            'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ]);

        return $this->display('rozetkapay', 'rozetkapay.tpl');
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $this->smarty->assign($this->getTemplateVars());

        $newOption = new PaymentOption();
        $newOption->setModuleName($this->name)
            ->setCallToActionText($this->getTitle())
            ->setAction(Context::getContext()->link->getModuleLink('rozetkapay', 'validation'));

        if(Configuration::get('ROZETKAPAY_VIEW_ICON') == "1") {
            $newOption->setLogo($this->logo);
        }

        return [$newOption];
    }


    public function hookPayment($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->_checkCurrency($params['cart'])) {
            return;
        }

        if (!$this->validModule) {
            return;
        }

        $this->context->smarty->force_compile = true;

        $urlPayCreat = Context::getContext()->link->getModuleLink('rozetkapay', 'rozetkapay');

        $this->context->smarty->assign('urlPayCreat', $urlPayCreat);
        $this->context->smarty->assign('urlCancel', $urlPayCreat);

        $this->context->smarty->assign('showIcon', Configuration::get('ROZETKAPAY_VIEW_ICON_STATUS') == "1");

        $title = $this->getTitle();

        $this->context->smarty->assign('text_title', $title);
        $this->context->smarty->assign($this->languages);

        return $this->display('rozetkapay', 'rozetkapay.tpl');
    }

    public function getTitle()
    {

        $title = $this->l('Pay via Visa|Mastercard|GooglePay|ApplePay (RozetkaPay)');

        if (Configuration::get('ROZETKAPAY_VIEW_NAME_DEFAULT') == "0") {
            $titleNew = Configuration::get('ROZETKAPAY_VIEW_NAME_' . strtoupper(Context::getContext()->language->iso_code));
            if ($titleNew !== null || empty($titleNew)) {
                $title = $titleNew;
            }
        }


        if (Configuration::get('ROZETKAPAY_LIVE_MODE') === "1") {
            $title .= '(Test)';
        }

        return $title;
    }

    public function getTemplateVars()
    {
        $cart = $this->context->cart;
        $total = $this->context->getCurrentLocale()->formatPrice(
            $cart->getOrderTotal(true, Cart::BOTH),
            (new Currency($cart->id_currency))->iso_code
        );

        $taxLabel = '';

        if ($this->context->country->display_tax_label) {
            $taxLabel = $this->trans('(tax incl.)', [], 'Modules.Pozetkapay.Admin');
        }

        $checkOrder = Configuration::get('CHEQUE_NAME');
        if (!$checkOrder) {
            $checkOrder = '___________';
        }

        $checkAddress = Tools::nl2br(Configuration::get('CHEQUE_ADDRESS'));

        if (!$checkAddress) {
            $checkAddress = '___________';
        }

        return [
            'checkTotal' => $total,
            'checkTaxLabel' => $taxLabel,
            'checkOrder' => $checkOrder,
            'checkAddress' => $checkAddress,
        ];
    }
}
