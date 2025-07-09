<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que l'admin peut accéder à l'index des services.
     */
    public function test_admin_can_view_services_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('services.index'));

        $response->assertStatus(200);
        $response->assertViewIs('services.index');
    }

    /**
     * Test que l'admin peut voir le formulaire de création.
     */
    public function test_admin_can_view_create_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('services.create'));

        $response->assertStatus(200);
        $response->assertViewIs('services.create');
    }

    /**
     * Test que l'admin peut créer un service.
     */
    public function test_admin_can_create_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $serviceData = [
            'name' => 'Test Service',
            'description' => 'Description du service de test',
            'duration_minutes' => 60,
            'is_active' => true,
        ];

        $response = $this->actingAs($admin)->post(route('services.store'), $serviceData);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', $serviceData);
    }

    /**
     * Test que l'admin peut voir un service spécifique.
     */
    public function test_admin_can_view_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->get(route('services.show', $service));

        $response->assertStatus(200);
        $response->assertViewIs('services.show');
    }

    /**
     * Test que l'admin peut voir le formulaire de modification.
     */
    public function test_admin_can_view_edit_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->get(route('services.edit', $service));

        $response->assertStatus(200);
        $response->assertViewIs('services.edit');
    }

    /**
     * Test que l'admin peut modifier un service.
     */
    public function test_admin_can_update_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();

        $updatedData = [
            'name' => 'Service Modifié',
            'description' => 'Description modifiée',
            'duration_minutes' => 90,
            'is_active' => false,
        ];

        $response = $this->actingAs($admin)->put(route('services.update', $service), $updatedData);

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', $updatedData);
    }

    /**
     * Test que l'admin peut supprimer un service.
     */
    public function test_admin_can_delete_service(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::factory()->create();

        $response = $this->actingAs($admin)->delete(route('services.destroy', $service));

        $response->assertRedirect(route('services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    /**
     * Test que l'utilisateur non-admin reçoit 403 pour l'index.
     */
    public function test_non_admin_gets_403_for_index(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($user)->get(route('services.index'));

        $response->assertStatus(403);
    }

    /**
     * Test que l'utilisateur non-admin reçoit 403 pour la création.
     */
    public function test_non_admin_gets_403_for_create(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($user)->get(route('services.create'));

        $response->assertStatus(403);
    }

    /**
     * Test que l'utilisateur non-admin reçoit 403 pour le store.
     */
    public function test_non_admin_gets_403_for_store(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);

        $response = $this->actingAs($user)->post(route('services.store'), []);

        $response->assertStatus(403);
    }

    /**
     * Test que l'utilisateur non-admin reçoit 403 pour la modification.
     */
    public function test_non_admin_gets_403_for_update(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);
        $service = Service::factory()->create();

        $response = $this->actingAs($user)->put(route('services.update', $service), []);

        $response->assertStatus(403);
    }

    /**
     * Test que l'utilisateur non-admin reçoit 403 pour la suppression.
     */
    public function test_non_admin_gets_403_for_delete(): void
    {
        $user = User::factory()->create(['role' => 'etudiant']);
        $service = Service::factory()->create();

        $response = $this->actingAs($user)->delete(route('services.destroy', $service));

        $response->assertStatus(403);
    }

    /**
     * Test la validation du nom unique.
     */
    public function test_service_name_must_be_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $existingService = Service::factory()->create(['name' => 'Service Existant']);

        $response = $this->actingAs($admin)->post(route('services.store'), [
            'name' => 'Service Existant',
            'duration_minutes' => 60,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test la validation de la durée.
     */
    public function test_duration_must_be_between_5_and_240(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('services.store'), [
            'name' => 'Test Service',
            'duration_minutes' => 3, // Trop court
        ]);

        $response->assertSessionHasErrors('duration_minutes');
    }
}
