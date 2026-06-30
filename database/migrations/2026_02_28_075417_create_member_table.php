<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SHARD_COUNT = 20;
    private function getShardTableNames(): array
    {
        return array_map(
            fn($i) => "members_{$i}",
            range(0, self::SHARD_COUNT - 1)
        );
    }
    public function up(): void
    {
        foreach (range(0, self::SHARD_COUNT - 1) as $index) {
            Schema::create("members_{$index}", function (Blueprint $table) {
                $table->unsignedBigInteger('member_id')->primary();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
        Schema::create('member_email_index', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->unsignedBigInteger('member_id');
            $table->tinyInteger('shard_index');
        });
    }
    public function down(): void
    {
        foreach ($this->getShardTableNames() as $tableName) {
            Schema::dropIfExists($tableName);
        }
        Schema::dropIfExists("member_email_index");
    }
};
