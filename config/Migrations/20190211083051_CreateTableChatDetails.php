<?php
use Migrations\AbstractMigration;

class CreateTableChatDetails extends AbstractMigration
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
        $table = $this->table('chat_details');
        $table->addColumn('chat_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('customer_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11
        ]);
        $table->addColumn('message', 'text', [
            'default' => null,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
        ]);
        $table->addIndex('chat_id');
        $table->addForeignKey('chat_id', 'chats', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('customer_id');
        $table->addForeignKey('customer_id', 'customers', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->addIndex('user_id');
        $table->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'CASCADE']);
        $table->create();
    }
}
