<?php
use Migrations\AbstractMigration;

class CreateTableProductTags extends AbstractMigration
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
        $table = $this->table('product_tags');
        $table->addColumn('product_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('tag_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addIndex('product_id');
        $table->addForeignKey('product_id', 'products', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('tag_id');
        $table->addForeignKey('tag_id', 'tags', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
