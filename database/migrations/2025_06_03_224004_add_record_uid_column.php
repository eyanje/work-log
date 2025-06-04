<?php

use App\Models\Record;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->addColumn('text', 'uid')->nullable();
            $table->unique(['book_id', 'uid']);
        });

        foreach (Record::all() as $record) {
            $record->uid = Str::uuid7($record->created_at);
            $record->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropUnique(['book_id', 'uid']);
            $table->dropColumn('uid');
        });
    }
};
