<?php
use Migrations\AbstractMigration;

class AlterProductImages2 extends AbstractMigration
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

        $table->addColumn('dir', 'string', [
            'default' => null,
            'limit' => 255,
            'after' => 'primary',
            'null' => true
        ]);

        $table->addColumn('size', 'integer', [
            'default' => null,
            'limit' => 11,
            'after' => 'dir',
            'null' => true
        ]);

        $table->addColumn('type', 'string', [
            'default' => null,
            'limit' => 150,
            'after' => 'size',
            'null' => true
        ]);

        $table->addIndex('name');

        $table->update();
    }
}
