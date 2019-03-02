<?php
use Migrations\AbstractMigration;

class AlterTableProductAttributes extends AbstractMigration
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
        $table = $this->table('product_attributes');
        $table->removeColumn('value');
        $table->addColumn('description', 'text', [
            'default' => null,
        ]);
        $table->update();
    }
}
