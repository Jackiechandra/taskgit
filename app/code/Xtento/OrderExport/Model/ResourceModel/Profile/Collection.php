<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            bY/Ft2U8dyxRjeo/M3VIOTeBSPY04gzxxlhY9eC916A=
 * Last Modified: 2015-11-26T12:57:04+00:00
 * File:          app/code/Xtento/OrderExport/Model/ResourceModel/Profile/Collection.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Model\ResourceModel\Profile;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Xtento\OrderExport\Model\Profile', 'Xtento\OrderExport\Model\ResourceModel\Profile');
    }
}