<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->string('type', 32);
            $table->string('status', 32)->default('for_sale');
            $table->string('approval_status', 32)->default('draft');

            $table->decimal('price', 14, 2);
            $table->decimal('area', 12, 2)->nullable();
            $table->string('area_unit', 12)->default('sqft');

            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->unsignedSmallInteger('floors')->nullable();
            $table->unsignedSmallInteger('year_built')->nullable();
            $table->boolean('furnished')->default(false);
            $table->boolean('parking')->default(false);

            $table->string('address');
            $table->string('city', 96)->index();
            $table->string('state', 96)->nullable();
            $table->string('pincode', 16)->nullable();
            $table->string('country', 64)->default('India');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('survey_number', 64)->nullable()->index();
            $table->string('legal_verification_status', 32)->default('not_verified');

            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('view_count')->default(0);

            $table->json('nearby_facilities')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'approval_status']);
            $table->index(['status', 'approval_status']);
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->boolean('is_cover')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['property_id', 'sort_order']);
        });

        Schema::create('property_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        Schema::create('property_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('label');
            $table->string('type', 32)->default('other');
            $table->string('path');
            // Flag retained explicitly so the UI can always show a "demo document"
            // tag — these uploads are not legally authoritative records.
            $table->boolean('is_demo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_documents');
        Schema::dropIfExists('property_videos');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};
