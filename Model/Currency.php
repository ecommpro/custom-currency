<?php

/**
 * Currency.php
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */

namespace EcommPro\CustomCurrency\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Registry;
use EcommPro\CustomCurrency\Model\Currency\Attribute\Backend\SymbolimageFactory;

class Currency extends AbstractModel implements IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'ecommpro_customcurrency_currency';

    /**
     * @var string
     */
    protected $_cacheTag = 'ecommpro_customcurrency_currency';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ecommpro_customcurrency_currency';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('EcommPro\CustomCurrency\Model\ResourceModel\Currency');
    }

    /**
     * Reference constructor.
     * @param SymbolimageFactory $symbolimageFactory
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        SymbolimageFactory $symbolimageFactory,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->symbolimageFactory = $symbolimageFactory;
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Save from collection data
     *
     * @param array $data
     * @return $this|bool
     */
    public function saveCollection(array $data)
    {
        if (isset($data[$this->getId()])) {
            $this->addData($data[$this->getId()]);
            $this->getResource()->save($this);
        }
        return $this;
    }

    /**
     * Get Symbolimage in right format to edit in admin form
     *
     * @return array
     */
    public function getSymbolimageValueForForm()
    {
        $symbolimage = $this->symbolimageFactory->create();
        return $symbolimage->getFileValueForForm($this);
    }

    /**
     * Get Symbolimage Src to display in frontend
     *
     * @return mixed
     */
    public function getSymbolimageSrc()
    {
        $symbolimage = $this->symbolimageFactory->create();
        return $symbolimage->getFileInfo($this)->getUrl();
    }

}
