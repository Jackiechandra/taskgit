<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Fpc
 */


declare(strict_types=1);

namespace Amasty\Fpc\Model\Queue\Combination\Context;

use Amasty\Fpc\Helper\Http as HttpHelper;
use Amasty\Fpc\Model\Config;
use GuzzleHttp\RequestOptions;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Group;
use Magento\Framework\App\Http\Context;

class CustomerGroupCombination implements CombinationSourceInterface
{
    /**
     * @var Config
     */
    private $configProvider;

    public function __construct(Config $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function getVariations(): array
    {
        $groups = $this->configProvider->getCustomerGroups();

        if (empty($groups)) {
            $groups[] = Group::NOT_LOGGED_IN_ID;
        }

        return $groups;
    }

    public function getCombinationKey(): string
    {
        return 'crawler_customer_group';
    }

    public function modifyRequest(array $combination, array &$requestParams, Context $context)
    {
        if ($customerGroup = $combination[$this->getCombinationKey()] ?? null) {
            $requestParams[RequestOptions::HEADERS][HttpHelper::CUSTOMER_GROUP_HEADER] = $customerGroup;
            $context->setValue(
                CustomerContext::CONTEXT_GROUP,
                $customerGroup,
                Group::NOT_LOGGED_IN_ID
            );
            $context->setValue(
                CustomerContext::CONTEXT_AUTH,
                (bool)$customerGroup,
                false
            );
        }
    }

    public function prepareLog(array $crawlerLogData, array $combination): array
    {
        if ($customerGroup = $combination[$this->getCombinationKey()] ?? null) {
            $crawlerLogData['customer_group'] = $customerGroup;
        }

        return $crawlerLogData;
    }
}
