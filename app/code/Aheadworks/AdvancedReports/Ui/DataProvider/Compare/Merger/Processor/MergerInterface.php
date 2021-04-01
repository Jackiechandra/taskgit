<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://ecommerce.aheadworks.com/end-user-license-agreement/
 *
 * @package    AdvancedReports
 * @version    2.8.5
 * @copyright  Copyright (c) 2020 Aheadworks Inc. (http://www.aheadworks.com)
 * @license    https://ecommerce.aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\AdvancedReports\Ui\DataProvider\Compare\Merger\Processor;

/**
 * Interface MergerInterface
 *
 * @package Aheadworks\AdvancedReports\Ui\DataProvider\Compare\Merger\Processor
 */
interface MergerInterface
{
    /**
     * Merge data
     *
     * @param array $rows
     * @param array $compareRows
     * @param array $dataSourceData
     * @return array
     */
    public function merge($rows, $compareRows, $dataSourceData);
}
