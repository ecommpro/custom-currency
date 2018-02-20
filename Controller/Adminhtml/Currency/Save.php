<?php
/**
 * Save
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */
namespace EcommPro\CustomCurrency\Controller\Adminhtml\Currency;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use EcommPro\CustomCurrency\Model\CurrencyFactory;

class Save extends Action
{
    /** @var CurrencyFactory $objectFactory */
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
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $storeId = (int)$this->getRequest()->getParam('store_id');
        $data = $this->getRequest()->getParams();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $params = [];
            $objectInstance = $this->objectFactory->create();
            $objectInstance->setStoreId($storeId);
            $params['store'] = $storeId;
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            } else {
                $objectInstance->load($data['entity_id']);
                $params['entity_id'] = $data['entity_id'];
            }
            $this->symbolimagePreprocessing($data);
            $objectInstance->addData($data);

            $this->_eventManager->dispatch(
                'ecommpro_customcurrency_currency_prepare_save',
                ['object' => $this->objectFactory, 'request' => $this->getRequest()]
            );

            try {
                $objectInstance->save();
                $this->messageManager->addSuccessMessage(__('You saved this record.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $params['entity_id'] = $objectInstance->getId();
                    $params['_current'] = true;
                    return $resultRedirect->setPath('*/*/edit', $params);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the record.'));
            }

            $this->_getSession()->setFormData($this->getRequest()->getPostValue());
            return $resultRedirect->setPath('*/*/edit', $params);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Symbolimage data preprocessing
     *
     * @param array $data
     *
     * @return array
     */
    protected function symbolimagePreprocessing(&$data)
    {
        if (empty($data['symbolimage'])) {
            unset($data['symbolimage']);
            $data['symbolimage']['delete'] = true;
        }
    }

}
