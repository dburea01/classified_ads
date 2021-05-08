<?php

declare(strict_types=1);

use App\Models\DefaultCategory;
use App\Models\DefaultCategoryGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultCategoryGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('default_category_groups', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->tinyInteger('position');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('default_categories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('default_category_group_id');
            $table->tinyInteger('position');
            $table->string('name');
            $table->timestamps();

            $table->foreign('default_category_group_id')->references('id')->on('default_category_groups')->nullOnDelete();
        });

        $this->initCategoryGroups();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_categories');
        Schema::dropIfExists('default_category_groups');
    }

    public function initCategoryGroups()
    {
        $defaultCategoryGroups = [
            'Véhicules' => ['Voitures', 'Motos', 'Caravaning', 'Utilitaires', 'Camions', 'Equipement véhicules'],
            'Immobilier' => ['Vente', 'Location', 'Colocation'],
            'Mode' => ['Vêtements', 'Chaussures', 'Bagagerie', 'Montres & Bijoux', 'Vêtements bébé'],
            'Maison' => ['Ameublement', 'Electroménager', 'Art de la table', 'Décoration', 'Linge de maison', 'Bricolage', 'Jardinage'],
            'Multimédia' => ['Informatique', 'Consoles & Jeux vidéo', 'Image & Son', 'Téléphonie'],
            'Loisirs' => ['DVD - Films', 'CD - Musique', 'Livres', 'Sports & Hobbies', 'Instruments de musique', 'Collection', 'Jeux & Jouets', 'Vins & Gastronomie'],
            'Animaux' => ['Animaux'],
            'Services' => ['Prestations de service', ' Billeterie', 'Evenements', 'Cours particuliers', 'Co-voiturage'],
            'Divers' => ['Divers']
        ];

        $positionGroup = 0;
        foreach ($defaultCategoryGroups as $group => $categories) {
            $defaultCategoryGroup = new DefaultCategoryGroup();
            $defaultCategoryGroup->name = $group;
            $defaultCategoryGroup->position = $positionGroup;
            $defaultCategoryGroup->save();

            $positionGroup = $positionGroup + 1;

            $this->initCategories($defaultCategoryGroup, $categories);
        }
    }

    public function initCategories(DefaultCategoryGroup $defaultCategoryGroup, array $categories)
    {
        $positionCategory = 0;

        foreach ($categories as $category) {
            $defaultCategory = new DefaultCategory();
            $defaultCategory->name = $category;
            $defaultCategory->position = $positionCategory;
            $defaultCategory->default_category_group_id = $defaultCategoryGroup->id;
            $defaultCategory->save();

            $positionCategory = $positionCategory + 1;
        }
    }
}
