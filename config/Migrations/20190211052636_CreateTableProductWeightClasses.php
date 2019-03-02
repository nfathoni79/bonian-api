<?php
use Migrations\AbstractMigration;

class CreateTableProductWeightClasses extends AbstractMigration
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
        $table = $this->table('product_weight_classes');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 20
        ]);
        $table->addColumn('unit', 'string', [
            'default' => null,
            'limit' => 2
        ]);
        $table->create();
    }
}
