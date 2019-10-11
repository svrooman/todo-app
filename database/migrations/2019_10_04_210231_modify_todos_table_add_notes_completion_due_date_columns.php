<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTodosTableAddNotesCompletionDueDateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('title');
            $table->boolean('completion_status')
                ->nullable()
                ->default(0)
                ->after('notes');
            $table->datetime('due_date')->nullable()->after('completion_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'notes',
                    'completion_status',
                    'due_date'
                ]
            );
        });
    }
}
