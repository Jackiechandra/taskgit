<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            bY/Ft2U8dyxRjeo/M3VIOTeBSPY04gzxxlhY9eC916A=
 * Last Modified: 2015-09-10T15:24:06+00:00
 * File:          app/code/Xtento/OrderExport/Block/Adminhtml/Log.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Block\Adminhtml;

class Log extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->removeButton('add');
    }
}
