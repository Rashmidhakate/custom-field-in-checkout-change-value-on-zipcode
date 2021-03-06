<?php
namespace Brainvire\Fps\Ui\Component\Listing\Column;
 
use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Framework\Api\SearchCriteriaBuilder;
 
use Magento\Framework\Pricing\PriceCurrencyInterface;
 
class Assemblerid extends Column
{
	protected $_orderRepository;
	protected $_searchCriteria;
 
	public function __construct(
    	PriceCurrencyInterface $priceCurrency,
    	ContextInterface $context,
    	UiComponentFactory $uiComponentFactory,
    	OrderRepositoryInterface $orderRepository,
    	SearchCriteriaBuilder $criteria,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
    	array $components = [],
    	array $data = [])
	{
    	$this->_orderRepository = $orderRepository;
    	$this->_searchCriteria  = $criteria;
    	$this->priceCurrency = $priceCurrency;
        $this->resourceConnection = $resourceConnection;
    	parent::__construct($context, $uiComponentFactory, $components, $data);
	}
	public function prepareDataSource(array $dataSource)
	{
    	if (isset($dataSource['data']['items'])) {
        	foreach ($dataSource['data']['items'] as & $item) {
            	$order  = $this->_orderRepository->get($item["entity_id"]);
                if($order->getData("assembler_order_id")){
                    $table = $this->resourceConnection->getTableName('rpassembler_assembler');
                    $this->_connection = $this->resourceConnection->getConnection();
                    $sql = 'SELECT title FROM  '. $table .' where assembler_id = '.$order->getData("assembler_order_id");
                    $collection = $this->_connection->fetchOne($sql);
                    $item[$this->getData('name')] = $collection;
               }
            	
        	}
    	}
    	return $dataSource;
	}
}