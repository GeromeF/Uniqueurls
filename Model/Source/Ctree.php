<?php

namespace Netgasoline\Uniqueurls\Model\Source;

class Ctree extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource

//Magento\Catalog\Model\ResourceModel\Product\Attribute
{
	
	    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Tree
     */
    protected $categoryTree;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    protected $categoryCollection;

    /**
     * @var \Magento\Catalog\Api\Data\CategoryTreeInterfaceFactory
     */
    protected $treeFactory;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection
     * @param \Magento\Catalog\Api\Data\CategoryTreeInterfaceFactory $treeFactory
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\Tree $categoryTree,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection,
        \Magento\Catalog\Api\Data\CategoryTreeInterfaceFactory $treeFactory,
		\Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
		\Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
		\Magento\Catalog\Helper\Category $categoryHelper,
		\Magento\Catalog\Model\Category $CatObj,
		\Magento\Framework\ObjectManagerInterface $objectManager
		
    ) {
        $this->categoryTree = $categoryTree;
        $this->storeManager = $storeManager;
        $this->categoryCollection = $categoryCollection;
        $this->treeFactory = $treeFactory;
		$this->dataObjectHelper = $dataObjectHelper;
		$this->dataObjectProcessor = $dataObjectProcessor;
		$this->_categoryHelper = $categoryHelper;
		$this->CatObj = $CatObj;
		$this->_objectManager = $objectManager;
    }


	
      public function getAllOptions()
    {
		$categoriesTree = $this->_categoryHelper->getStoreCategories();
		$categoryObj = $this->_objectManager->create('\Magento\Catalog\Model\Category');
        if (!$this->_options) {
		     $this->_options = [['value' => '0', 'label' => 'ROOT']];
			        foreach ($categoriesTree as $SubCategory) {
							   $categoryUU = $categoryObj->load($SubCategory->getId());
							   $category_name = $categoryUU->getName();
							   $this->_CatAdd = [['value' => $SubCategory->getId(), 'label' => $categoryUU->getName()]];
							   $this->_options = array_merge($this->_options,$this->_CatAdd);
							   $this->ChildRecursive($categoryUU,$category_name);
        			};

        }
        return $this->_options;
    }
	
	    public function ChildRecursive($cur_category,$category_path)
    {
				$children_categories = $cur_category->getChildrenCategories();
				if(!empty($children_categories))
				{
					foreach($children_categories as $k => $v)
					{
						$all_data = $v->getData();
						$this->_CatAdd = [['value' => $all_data['entity_id'], 'label' => $category_path." > ".$all_data['name']]];
						$this->_options = array_merge($this->_options,$this->_CatAdd);
						$this->ChildRecursive($v,$category_path." > ".$all_data['name']);
					}
				}

						
						
    }

}
