<?php
use Migrations\AbstractMigration;

class CreateTableProductMetaTags extends AbstractMigration
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
        $table = $this->table('product_meta_tags');
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('keyword', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('description', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
