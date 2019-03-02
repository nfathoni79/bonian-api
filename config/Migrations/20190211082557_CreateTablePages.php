<?php
use Migrations\AbstractMigration;

class CreateTablePages extends AbstractMigration
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
        $table = $this->table('pages');
        $table->addColumn('title', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('slug', 'string', [
            'default' => null,
            'limit' => 255
        ]);
        $table->addColumn('content', 'text', [
            'default' => null
        ]);
        $table->addColumn('enable', 'integer', [
            'default' => null,
            'limit' => 1
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null
        ]);
        $table->create();
    }
}
