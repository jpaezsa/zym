<?php
/**
 * Zym Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category Zym
 * @package Zym_App
 * @subpackage Resource_Mail_Transport
 * @copyright Copyright (c) 2008 Zym. (http://www.assembla.com/wiki/show/zym)
 * @license http://www.assembla.com/wiki/show/zym/License New BSD License
 */

/**
 * @author Geoffrey Tran
 * @license http://www.assembla.com/wiki/show/zym/License New BSD License
 * @category Zym
 * @package Zym_App
 * @subpackage Resource_Mail_Transport
 * @copyright Copyright (c) 2008 Zym. (http://www.assembla.com/wiki/show/zym)
 */
interface Zym_App_Resource_Mail_Transport_Interface
{
    /**
     * Get transport
     *
     * @param Zend_Config $config
     * @return Zend_Mail_Transport_Abstract
     */
    public static function getTransport(Zend_Config $config = null);
}