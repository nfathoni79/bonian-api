<?php
use Migrations\AbstractMigration;

class CreateTableCustomerAddreses extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('customer_addreses');
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('province_id', 'integer', [
            'default' => null,
            'limit' => 4,
            'null' => true
        ]);
        $table->addColumn('city_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('subdistrict_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true
        ]);
        $table->addColumn('address', 'string', [
            'default' => null,
            'limit' => 255,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('province_id');
        $table->addForeignKey('province_id', 'provinces', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('city_id');
        $table->addForeignKey('city_id', 'cities', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('subdistrict_id');
        $table->addForeignKey('subdistrict_id', 'subdistricts', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
