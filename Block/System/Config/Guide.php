<?php
/**
 * Copyright © 2016 MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace jframeworks\shippingtracking\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Guide extends Field
{
    /**
     * @var string
     */
    private $template = 'jframeworks_shippingtracking::system/config/guide.phtml';
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        $this->_template = $this->template;
        parent::__construct($context, $data);
    }
    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        if ($element) {
            $element->getElementHtml();
        }
        return $this->_toHtml();
    }
    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
                'id' => 'user_guide',
                'label' => __('Important, do these steps, if sharing embedded shipping tracking info in emails.'),
            ]
        );
        return $button->toHtml();
    }
}
