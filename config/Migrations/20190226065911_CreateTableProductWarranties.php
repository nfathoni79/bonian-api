<?php
use Migrations\AbstractMigration;

class CreateTableProductWarranties extends AbstractMigration
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
        $table = $this->table('product_warranties');
        $table->addColumn('name', 'string', [
            'default' => null,
            'null' => true
        ]);
        $table->create();
    }
}
