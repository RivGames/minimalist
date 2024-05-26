<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('users')
            ->addColumn('first_name', 'string', ['limit' => 255])
            ->addColumn('last_name', 'string', ['limit' => 255, 'default' => null])
            ->addColumn('user_name', 'string', ['limit' => 255, 'default' => null])
            ->addColumn('is_premium', 'boolean', ['signed' => false])
            ->addColumn('language_code', 'string', ['limit' => 10])
            ->addColumn('chat_id', 'biginteger')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->save();
    }

    public function down(): void
    {
        $this->table('users')->drop()->save();
    }
}
