<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn(
        $installer->getTable('procommerz_russify/result'),
        'count',
        array(
            'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
            'comment'   => 'Count of requests'
        )
    );

$installer->endSetup();