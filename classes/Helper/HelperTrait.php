<?php
/**
 * 2020-2024 RozetkaPay
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
 * @author RozetkaPay <ecomsupport@rozetkapay.com>
 * @copyright 2020-2024 RozetkaPay
 * @license http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *           International Registered Trademark & Property of PrestaShop SA
 */
 if (!defined('_PS_VERSION_')) { exit; }
trait HelperTrait {

    /**
     * @return bool
     */
    public static function isPrestaShop16Static() {
        return (version_compare(_PS_VERSION_, '1.7.0', '<') || Tools::substr(_PS_VERSION_, 0, 3) == '1.6');
    }

    /**
     * @return bool
     */
    public static function isPrestaShop176Static() {
        return version_compare(_PS_VERSION_, '1.7.6', '>=');
    }

    /**
     * @return bool
     */
    public static function isPrestaShop177OrHigherStatic() {
        return version_compare(_PS_VERSION_, '1.7.7', '>=');
    }

    /**
     * @return bool
     */
    public function isPrestaShop16() {
        return self::isPrestaShop16Static();
    }

    /**
     * @return bool
     */
    public function isPrestaShop176() {
        return self::isPrestaShop176Static();
    }

    /**
     * @return bool
     */
    public function isPrestaShop177OrHigher() {
        return self::isPrestaShop177OrHigherStatic();
    }
    
    public function log($var) {
        if ($this->extlog !== false) {
            $this->extlog->write($var);
        }
    }
    

}
