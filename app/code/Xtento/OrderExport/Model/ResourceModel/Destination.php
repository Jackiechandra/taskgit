<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            bY/Ft2U8dyxRjeo/M3VIOTeBSPY04gzxxlhY9eC916A=
 * Last Modified: 2015-10-11T13:28:37+00:00
 * File:          app/code/Xtento/OrderExport/Model/ResourceModel/Destination.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Model\ResourceModel;

class Destination extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('xtento_orderexport_destination', 'destination_id');
    }
}
