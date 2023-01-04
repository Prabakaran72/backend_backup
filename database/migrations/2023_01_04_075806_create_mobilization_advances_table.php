<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobilization_advances', function (Blueprint $table) {
            $table->id();
            $table -> string('mobAdvance')->default(''); 
            $table -> string('bankName')->default(''); 
            $table -> string('bankBranch')->default(''); 
            $table -> string('mobAdvMode')->default(''); 
            $table -> date('dateMobAdv')->nullable(); 
            $table -> date('validUpto')->nullable(); 
            $table -> integer('createdby_userid');
            $table -> integer('updatedby_userid')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobilization_advances');
    }
};
