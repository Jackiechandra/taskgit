<?php
namespace Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button;

use Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class SaveAsDraft
 * @package Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button
 */
class SaveAsDraft extends Button implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save as Draft'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'saveAsDraft'
                    ],
                ],
            ],
            'sort_order' => 30,
        ];
    }
}
