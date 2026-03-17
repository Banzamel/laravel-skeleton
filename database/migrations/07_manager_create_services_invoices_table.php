<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Services table
        Schema::create('mgr_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('sec_companies')->onDelete('cascade');
            $table->string('name');
            $table->integer('limit')->default(0);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Invoices table
        Schema::create('mgr_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('sec_companies')->onDelete('cascade');
            $table->boolean('is_proforma')->default(true);
            $table->string('proforma_number')->unique();
            $table->string('invoice_number')->nullable()->unique();
            $table->date('due_date');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('pending');
            $table->string('billing_name');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('PL');
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot table for invoice_services
        Schema::create('mgr_invoice_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('mgr_invoices')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('mgr_services')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });

        // Billings table
        Schema::create('mgr_billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('sec_companies')->onDelete('cascade');
            $table->string('tax_id')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_country')->default('PL');
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('proforma_format')->default('FP/{NNN}/{MM}/{YYYY}');
            $table->string('invoice_format')->default('FV/{NNN}/{MM}/{YYYY}');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mgr_billings');
        Schema::dropIfExists('mgr_invoice_services');
        Schema::dropIfExists('mgr_invoices');
        Schema::dropIfExists('mgr_services');
    }
};
