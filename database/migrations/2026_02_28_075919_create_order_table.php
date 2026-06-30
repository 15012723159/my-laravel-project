<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $years = [];
    private $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    public function up(): void
    {
        $this->createOrderTables();
        $this->createOrderItemTables();
    }
    private function createOrderTables()
    {
        foreach ($this->getYears() as $year) {
            foreach ($this->months as $month) {
                $tableName = "orders_{$year}{$month}";
                Schema::create($tableName, function (Blueprint $table) {
                    $table->ulid('order_id')->primary();
                    $table->unsignedBigInteger('member_id');
                    $table->decimal('amount', 10, 2);
                    $table->timestamps();
                    $table->index('member_id');
                    $table->index('created_at');
                });
            }
        }
    }
    private function createOrderItemTables()
    {
        foreach ($this->getYears() as $year) {
            foreach ($this->months as $month) {
                $tableName = "order_items_{$year}{$month}";
                Schema::create($tableName, function (Blueprint $table) {
                    $table->ulid('order_item_id')->primary();
                    $table->ulid('order_id');
                    $table->unsignedBigInteger('member_id');
                    $table->string('product_name', 255);
                    $table->integer('quantity');
                    $table->decimal('amount', 10, 2);
                    $table->timestamps();
                    $table->index('order_id');
                    $table->index('member_id');
                    $table->index('created_at');
                });
            }
        }
    }
    public function down(): void
    {
        $this->dropOrderTables();
        $this->dropOrderItemTables();
    }
    private function dropOrderTables()
    {
        foreach ($this->getYears() as $year) {
            foreach ($this->months as $month) {
                $tableName = "orders_{$year}{$month}";
                Schema::dropIfExists($tableName);
            }
        }
    }
    private function dropOrderItemTables()
    {
        foreach ($this->getYears() as $year) {
            foreach ($this->months as $month) {
                $tableName = "order_items_{$year}{$month}";
                Schema::dropIfExists($tableName);
            }
        }
    }

    private function getYears(): array{
        for ( $year = date('Y');$year <= 2028;$year++) {
            array_push($this->years, $year);
        }
        return $this->years;
    }
};
