<?php

/**
 * Product:       Xtento_TrackingImport (2.3.6)
 * ID:            udfo4pHNxuS90BZUogqDpS6w1nZogQNAsyJKdEZfzKQ=
 * Packaged:      2018-02-26T09:10:55+00:00
 * Last Modified: 2016-04-11T12:58:55+00:00
 * File:          app/code/Xtento/TrackingImport/Block/Adminhtml/Profile/Edit/Tab/Mapping/Mapper.php
 * Copyright:     Copyright (c) 2017 XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\TrackingImport\Block\Adminhtml\Profile\Edit\Tab\Mapping;

class Mapper extends AbstractMapping
{
    public $mappingId = 'mapping';
    public $mappingModel = 'Xtento\TrackingImport\Model\Processor\Mapping\Fields';
    public $fieldLabel = 'Magento Field';
    public $valueFieldLabel = 'File Field Name / Index';
    public $hasDefaultValueColumn = true;
    public $hasValueColumn = true;
    public $defaultValueFieldLabel = 'Default Value';
    public $addFieldLabel = 'Add field to mapping';
    public $addAllFieldLabel = 'Add all fields';
    public $selectLabel = '--- Select field ---';
}
