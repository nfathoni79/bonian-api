<?php
use Migrations\AbstractMigration;

class AlterTableProductImages extends AbstractMigration
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
        $table = $this->table('product_images');

        $table->changeColumn('product_id', 'integer', [
            'default' => null,
            'null' => true
        ]);

        $table->update();
    }
}
