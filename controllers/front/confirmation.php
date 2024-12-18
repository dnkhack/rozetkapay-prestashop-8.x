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
class RozetkapayConfirmationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        if (Tools::getIsset('order_id')) {
            $order_id = (int) Tools::getValue('order_id');
            if (Order::getCartIdStatic($order_id)) {
                sleep(2);
                $order = new Order((int) $order_id);
                $customer = new Customer((int) $order->id_customer);
                $module_id = $this->module->id;
                if ($order->getCurrentState() == (int) Configuration::get('PS_OS_ROZETKAPAY_WAITING')) {
                    Tools::redirect('index.php?controller=order-detail&id_order=' . $order->id);
                }
                Tools::redirect('index.php?controller=order-confirmation&id_cart=' . $order->id_cart . '&id_module=' . $module_id . '&id_order=' . $order->id . '&key=' . $customer->secure_key);
            }
        }

        $this->errors[] = $this->module->l('An error occurred. Please contact with our managers to get support');
        $this->context->smarty->assign([
            'error' => $this->l('Payment issue'),
            'error_link' => $this->context->link->getPageLink('order', null, null, 'step=3'),
            'contact_link' => $this->context->link->getPageLink('contact', null, null, null),
        ]);

        return $this->setTemplate('module:idnkprivatliqpaypro/views/templates/front/module_error.tpl');
    }
}
