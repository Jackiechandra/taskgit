<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            bY/Ft2U8dyxRjeo/M3VIOTeBSPY04gzxxlhY9eC916A=
 * Last Modified: 2020-11-14T11:07:25+00:00
 * File:          app/code/Xtento/OrderExport/Helper/GracefulDie.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Helper;

use Xtento\OrderExport\Model\Log;

class GracefulDie
{
    protected static $isInitialized = false;
    protected static $isEnabled = false;

    public static function enable()
    {
        self::$isEnabled = true;
        if (!self::$isInitialized) {
            register_shutdown_function(['\Xtento\OrderExport\Helper\GracefulDie', 'beforeDieFromShutdown']); // Fatal error or similar
            self::$isInitialized = true;
        }
    }

    public static function disable()
    {
        self::$isEnabled = false;
    }

    /**
     * @param null $message
     * @param bool $exit
     */
    public static function beforeDie($message = null, $exit = false)
    {
        if (self::$isEnabled) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $registry = $objectManager->get('\Magento\Framework\Registry');
            $logEntry = $registry->registry('orderexport_log');
            if ($logEntry && $logEntry->getId()) {
                if (strstr($message, 'should always be of the type int since Symfony') !== false) {
                    return; // Ignore
                }
                $logEntry->setResult(Log::RESULT_FAILED);
                $logEntry->addResultMessage($message);
                $logEntry->setResultMessage($logEntry->getResultMessages());
                $logEntry->save();
                if (strlen($message) > 16) {
                    // No empty error message
                    $objectManager->get('\Xtento\OrderExport\Model\Export')->setLogEntry($logEntry)->errorEmailNotification();
                }
            }
        }
    }

    public static function beforeDieFromShutdown()
    {
        $message = 'Shutdown/Crash: ' . print_r(error_get_last(), true);
        //'Stack Trace: ' . PHP_EOL . (new \Exception())->__toString();

        self::beforeDie($message, false);
    }
}