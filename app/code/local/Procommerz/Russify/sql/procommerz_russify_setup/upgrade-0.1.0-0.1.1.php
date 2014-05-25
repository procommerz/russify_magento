<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'procommerz_russify/result'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('procommerz_russify/result'))
    ->addColumn('result_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Result ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
    ), 'Order Id')
    ->addColumn('result', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Result')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
    ), 'Update Time')
    ->addForeignKey(
        $installer->getFkName(
            'procommerz_russify/result',
            'order_id',
            'sales/order',
            'entity_id'
        ),
        'order_id', $installer->getTable('sales/order'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Russify Validation Result Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();