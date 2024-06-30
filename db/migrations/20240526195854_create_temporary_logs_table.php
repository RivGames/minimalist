<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTemporaryLogsTable extends AbstractMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->table('temporary_logs')
            ->addColumn('chat_id', 'biginteger')
            ->addColumn('data', 'json')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addForeignKey('chat_id', 'users', 'chat_id', ['delete' => 'cascade'])
            ->save();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        $this->table('temporary_logs')->drop()->save();
    }
}
