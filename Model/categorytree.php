<?php
    namespace Netgasoline\Uniqueurls\Model;
     
    use Magento\Framework\Model\AbstractModel;
     
    class Categorytree extends AbstractModel
    {
        /**
         * Define resource model
         */
        protected function _construct()
        {
            $this->_init('Netgasoline\Uniqueurls\Model\Resource\Categorytree');
        }
    }