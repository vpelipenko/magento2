<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Product attribute add/edit form main tab
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Catalog\Model\Entity\Attribute;

class Front extends Generic
{
    /**
     * @var Yesno
     */
    protected $_yesNo;

    /**
     * @var array
     */
    private $disableSearchable;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Yesno $yesNo
     * @param array $data
     * @param array $disableSearchable
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Yesno $yesNo,
        array $data = [],
        array $disableSearchable = []
    ) {
        $this->_yesNo = $yesNo;
        $this->disableSearchable = $disableSearchable;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var Attribute $attributeObject */
        $attributeObject = $this->_coreRegistry->registry('entity_attribute');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $yesnoSource = $this->_yesNo->toOptionArray();

        $fieldset = $form->addFieldset(
            'front_fieldset',
            ['legend' => __('Frontend Properties'), 'collapsable' => $this->getRequest()->has('popup')]
        );

        $attrCode = $attributeObject->getAttributeCode();
        $fieldset->addField(
            'is_searchable',
            'select',
            [
                'name'     => 'is_searchable',
                'label'    => __('Use in Search'),
                'title'    => __('Use in Search'),
                'values'   => $yesnoSource,
                'disabled' => isset($this->disableSearchable[$attrCode]) && $this->disableSearchable[$attrCode] ? 1 : 0
            ]
        );

        $fieldset->addField(
            'is_visible_in_advanced_search',
            'select',
            [
                'name' => 'is_visible_in_advanced_search',
                'label' => __('Visible in Advanced Search'),
                'title' => __('Visible in Advanced Search'),
                'values' => $yesnoSource,
            ]
        );

        $fieldset->addField(
            'is_comparable',
            'select',
            [
                'name' => 'is_comparable',
                'label' => __('Comparable on Frontend'),
                'title' => __('Comparable on Frontend'),
                'values' => $yesnoSource,
            ]
        );

        $this->_eventManager->dispatch('product_attribute_form_build_front_tab', ['form' => $form]);

        $fieldset->addField(
            'is_used_for_promo_rules',
            'select',
            [
                'name' => 'is_used_for_promo_rules',
                'label' => __('Use for Promo Rule Conditions'),
                'title' => __('Use for Promo Rule Conditions'),
                'values' => $yesnoSource,
            ]
        );

        $fieldset->addField(
            'is_wysiwyg_enabled',
            'select',
            [
                'name' => 'is_wysiwyg_enabled',
                'label' => __('Enable WYSIWYG'),
                'title' => __('Enable WYSIWYG'),
                'values' => $yesnoSource,
            ]
        );

        $fieldset->addField(
            'is_html_allowed_on_front',
            'select',
            [
                'name' => 'is_html_allowed_on_front',
                'label' => __('Allow HTML Tags on Frontend'),
                'title' => __('Allow HTML Tags on Frontend'),
                'values' => $yesnoSource,
            ]
        );
        if (!$attributeObject->getId() || $attributeObject->getIsWysiwygEnabled()) {
            $attributeObject->setIsHtmlAllowedOnFront(1);
        }

        $fieldset->addField(
            'is_visible_on_front',
            'select',
            [
                'name' => 'is_visible_on_front',
                'label' => __('Visible on Catalog Pages on Frontend'),
                'title' => __('Visible on Catalog Pages on Frontend'),
                'values' => $yesnoSource
            ]
        );

        $fieldset->addField(
            'used_in_product_listing',
            'select',
            [
                'name' => 'used_in_product_listing',
                'label' => __('Used in Product Listing'),
                'title' => __('Used in Product Listing'),
                'note' => __('Depends on design theme'),
                'values' => $yesnoSource
            ]
        );

        $fieldset->addField(
            'used_for_sort_by',
            'select',
            [
                'name' => 'used_for_sort_by',
                'label' => __('Used for Sorting in Product Listing'),
                'title' => __('Used for Sorting in Product Listing'),
                'note' => __('Depends on design theme'),
                'values' => $yesnoSource
            ]
        );

        $this->_eventManager->dispatch(
            'adminhtml_catalog_product_attribute_edit_frontend_prepare_form',
            ['form' => $form, 'attribute' => $attributeObject]
        );

        // define field dependencies
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Element\Dependence'
            )->addFieldMap(
                "is_wysiwyg_enabled",
                'wysiwyg_enabled'
            )->addFieldMap(
                "is_html_allowed_on_front",
                'html_allowed_on_front'
            )->addFieldMap(
                "frontend_input",
                'frontend_input_type'
            )->addFieldDependence(
                'wysiwyg_enabled',
                'frontend_input_type',
                'textarea'
            )->addFieldDependence(
                'html_allowed_on_front',
                'wysiwyg_enabled',
                '0'
            )
            ->addFieldMap(
                "is_searchable",
                'searchable'
            )
            ->addFieldMap(
                "is_visible_in_advanced_search",
                'advanced_search'
            )
            ->addFieldDependence(
                'advanced_search',
                'searchable',
                '1'
            )
        );

        $form->setValues($attributeObject->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
