<?php
/**
 * Delete
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */
namespace EcommPro\CustomCurrency\Controller\Adminhtml\Currency;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EcommPro\CustomCurrency\Model\CurrencyFactory;

class Delete extends Action
{
    /** @var currencyFactory $objectFactory */
    protected $objectFactory;

    /**
     * @param Context $context
     * @param CurrencyFactory $objectFactory
     */
    public function __construct(
    Context $context,
    CurrencyFactory $objectFactory
    ) {
        $this->objectFactory = $objectFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('EcommPro_CustomCurrency::currency');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id', null);

        try {
            $objectInstance = $this->objectFactory->create()->load($id);
            if ($objectInstance->getId()) {
                $objectInstance->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the record.'));
            } else {
                $this->messageManager->addErrorMessage(__('Record does not exist.'));
            }
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        
        return $resultRedirect->setPath('*/*');
    }
}
