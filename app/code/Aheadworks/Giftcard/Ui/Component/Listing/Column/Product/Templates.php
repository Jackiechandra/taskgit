<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Ui\Component\Listing\Column\Product;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Email\Model\Template\Config as EmailTemplateConfig;

/**
 * Class Templates
 *
 * @package Aheadworks\Giftcard\Ui\Component\Listing\Column\Product
 */
class Templates extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var FactoryInterface
     */
    private $emailTemplateFactory;

    /**
     * @var EmailTemplateConfig
     */
    private $emailTemplateConfig;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param FactoryInterface $emailTemplateFactory
     * @param EmailTemplateConfig $emailTemplateConfig
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        FactoryInterface $emailTemplateFactory,
        EmailTemplateConfig $emailTemplateConfig,
        array $components = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
        $this->emailTemplateFactory = $emailTemplateFactory;
        $this->emailTemplateConfig = $emailTemplateConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        foreach ($dataSource['data']['items'] as & $item) {
            $templateNames = [];
            $templates = $item[$fieldName];
            if (is_array($templates)) {
                foreach ($templates as $templateId) {
                    $templateNames[] = $this->getTemplateName($templateId);
                }
            }
            $item[$fieldName . '_names'] = $templateNames;
        }
        return $dataSource;
    }

    /**
     * Retrieves email template name using $templateId
     *
     * @param int|string $templateId
     * @return string
     */
    private function getTemplateName($templateId)
    {
        /** @var \Magento\Email\Model\Template $emailTemplate */
        $emailTemplate = $this->emailTemplateFactory->get($templateId);
        if (is_numeric($templateId)) {
            $emailTemplate->load($templateId);
        }
        return is_numeric($templateId) ?
            $emailTemplate->getTemplateCode() :
            $this->emailTemplateConfig->getTemplateLabel($templateId)->getText();
    }
}
