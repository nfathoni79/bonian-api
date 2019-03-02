<?php
use Migrations\AbstractMigration;

class CreateTableEmailQueue extends AbstractMigration
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
        $table = $this->table('email_queue');

        $table->addColumn('email', 'string', [
            'default' => null,
            'limit' => 129,
            'null' => false,
        ]);
        $table->addColumn('from_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('from_email', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->addColumn('subject', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('template', 'string', [
            'default' => 'default',
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('layout', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => false,
        ]);
        $table->addColumn('theme', 'string', [
            'default' => null,
            'limit' => 50,
            'null' => true,
        ]);
        $table->addColumn('format', 'string', [
            'default' => 'html',
            'limit' => 5,
            'null' => false,
        ]);
        $table->addColumn('text', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('html', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('headers', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('sent', 'integer', [
            'default' => 0,
            'limit' => 1,
            'null' => false,
        ]);
        $table->addColumn('locked', 'integer', [
            'default' => 0,
            'limit' => 1,
            'null' => false,
        ]);
        $table->addColumn('attachments', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('send_tries', 'integer', [
            'default' => 0,
            'limit' => 2,
            'null' => false,
        ]);
        $table->addColumn('error', 'text', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('send_at', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
