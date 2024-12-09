<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 * @author    RozetkaPay <ecomsupport@rozetkapay.com>
 * @copyright 2020-2024 RozetkaPay
 * @license   Valid for 1 website (or project) for each purchase of license
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once __DIR__ . '/classes/RozetkapayApiSdkClass.php';
require_once __DIR__ . '/classes/RozetkapayApiSdkCustomerClass.php';
require_once __DIR__ . '/classes/RozetkapayApiSdkCheckoutCreatRequestClass.php';
require_once __DIR__ . '/classes/RozetkapayApiSdkProductClass.php';
class Rozetkapay extends PaymentModule
{
    public $path;
    public $lpath;

    const REGISTER_HOOKS = [
        'displayBackOfficeHeader',
        'paymentOptions',
        'DisplayContentWrapperTop',
    ];

    const ROZETKAPAY_SUPPORTED_CURRENCIES = [
        'EUR',
        'USD',
        'UAH',
    ];

    public function __construct()
    {
        $this->name = 'rozetkapay';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'RozetkaPay';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Rozetka Pay');
        $this->description = $this->l('RozetkaPay Integration for your PrestaShop');

        $this->limited_countries = ['FR'];
        $this->limited_currencies = ['EUR'];

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->path = $this->_path;
        $this->lpath = $this->local_path;
    }

    /**
     * Create order state
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function installOrderState()
    {
        if (!Configuration::get('PS_OS_ROZETKAPAY_WAITING')
            || !Validate::isLoadedObject(new OrderState(Configuration::get('PS_OS_ROZETKAPAY_WAITING')))) {
            $order_state = new OrderState();
            $order_state->name = [];
            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'uk') {
                    $order_state->name[$language['id_lang']] = 'Очікується Оплата RozetkaPay';
                } else {
                    $order_state->name[$language['id_lang']] = 'Awaiting for RozetkaPay payment';
                }
            }
            $order_state->send_email = false;
            $order_state->color = '#4169E1';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            $order_state->module_name = $this->name;
            if ($order_state->add()) {
                $source = _PS_MODULE_DIR_ . $this->name . '/views/img/os_rozetkapay.gif';
                $destination = _PS_ROOT_DIR_ . '/img/os/' . (int) $order_state->id . '.gif';
                copy($source, $destination);
            }

            if (Shop::isFeatureActive()) {
                $shops = Shop::getShops();
                foreach ($shops as $shop) {
                    Configuration::updateValue('PS_OS_ROZETKAPAY_WAITING', (int) $order_state->id, false, null, (int) $shop['id_shop']);
                }
            } else {
                Configuration::updateValue('PS_OS_ROZETKAPAY_WAITING', (int) $order_state->id);
            }
        }

        return true;
    }

    public function install()
    {
        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');

            return false;
        }

        $conf = $this->getConfigFormValues();
        foreach ($conf as $key => $value) {
            Configuration::updateValue($key, $value);
        }
        $this->installOrderState();

        return parent::install()
            && $this->registerHook(self::REGISTER_HOOKS);
    }

    public function uninstall()
    {
        $conf = array_keys($this->getConfigFormValues());
        foreach ($conf as $key) {
            Configuration::deleteByName($key);
        }

        return parent::uninstall();
    }

    public function getContent()
    {
        $confirmations = '';
        if (((bool) Tools::isSubmit('submitRozetkapayModule')) == true) {
            $this->postProcess();
            $confirmations = $this->displayConfirmation($this->l('Successful update.'));
        }
        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $confirmations . $this->renderForm() . $output;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitRozetkapayModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('TEST MODE'),
                        'name' => 'ROZETKAPAY_API_TEST_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Sandbox mode. Enable this mode to test the module`s work before accepting real payments.'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'ROZETKAPAY_API_LOGIN',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'label' => $this->l('Login RozetkaPay'),
                        'desc' => $this->l('RozetkaPay credential login'),
                        'form_group_class' => 'rozetka-credentials',
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'name' => 'ROZETKAPAY_API_PASSWORD',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'label' => $this->l('Password RozetkaPay'),
                        'desc' => $this->l('RozetkaPay credential password'),
                        'form_group_class' => 'rozetka-credentials',
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Log'),
                        'name' => 'ROZETKAPAY_LOG',
                        'is_bool' => true,
                        'desc' => $this->l('Enable to log module work to Prestashop log and file in the folder /log/module.log'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return [
            'ROZETKAPAY_API_TEST_MODE' => Configuration::get('ROZETKAPAY_API_TEST_MODE', null, null, null, ''),
            'ROZETKAPAY_API_LOGIN' => Configuration::get('ROZETKAPAY_API_LOGIN', null, null, null, ''),
            'ROZETKAPAY_API_PASSWORD' => Configuration::get('ROZETKAPAY_API_PASSWORD', null, null, null, ''),
            'ROZETKAPAY_LOG' => Configuration::get('ROZETKAPAY_LOG', null, null, null, false),
        ];
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Return payment options available for PS 1.7+
     *
     * @param array Hook parameters
     *
     * @return array|null
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
        $option = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
        $option->setCallToActionText($this->l('RozetkaPay pay by card'))
            ->setAction($this->context->link->getModuleLink($this->name, 'redirect', [], true))
            ->setInputs([
                'action' => [
                    'name' => 'action',
                    'type' => 'hidden',
                    'value' => 'checkout',
                ],
            ])
            ->setAdditionalInformation($this->l('Pay order on RozetkaPay site using Debit/Credit card'))
            ->setLogo($this->_path . 'views/img/rozetkapay_long.png');

        return [
            $option,
        ];
    }

    public function hookDisplayContentWrapperTop($params)
    {
        return '';
    }

    /**
     * @param Cart $cart
     *
     * @return bool
     */
    private function checkCurrency(Cart $cart)
    {
        if (in_array(Currency::getIsoCodeById($cart->id_currency), self::ROZETKAPAY_SUPPORTED_CURRENCIES)) {
            return true;
        }

        return false;
    }

    public function logAdd($message, $class_name, $severity = 1)
    {
        if ($message) {
            if (is_string($message)) {
                $message = [$message];
            }
            if (Configuration::get('ROZETKAPAY_LOG')) {
                $filename = $this->local_path . 'log/module.log';
                $date = new DateTime();
                foreach ($message as $msg) {
                    $log = $date->format('d-m-Y H:i:s') . ' | ' . $severity . ' | ' . sprintf('%-12s', $class_name) . ' | ' . $msg . "\n";
                    file_put_contents($filename, $log, FILE_APPEND);
                }
                foreach ($message as $msg) {
                    PrestaShopLogger::addLog($msg, $severity, 0, str_replace(' ', '', $class_name), 0);
                }
            }
        }

        return false;
    }
}
