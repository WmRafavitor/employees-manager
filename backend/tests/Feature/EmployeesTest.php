<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if when the token is not present, the action returns error (401).
     *
     * @return void
     */
    public function testListIfReturnErrorWhenTokenNotExists()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->GET('/api/v1/employees');

        $response->assertStatus(401);
    }

    /**
     * Test if when the token is present and if there are employees registered the action return a list.
     *
     * @return void
     */
    public function testListIfReturnListWhenTokenExistsAndHasData()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $data = factory(\App\Employee::class, 3)->create();

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->GET('/api/v1/employees');

        $response->assertStatus(200)
            ->assertExactJson($data->toArray());
    }

    /**
     * Test if when the token is not present, the action returns error (401).
     *
     * @return void
     */
    public function testCreateIfReturnErrorWhenTokenNotExists()
    {
        $data = factory(\App\Employee::class)->make();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/employees', $data->toArray());

        $response->assertStatus(401);
    }

    /**
     * Test if when the token is present the action creates an employee.
     *
     * @return void
     */
    public function testCreateIfCreatesEmployee()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $data = factory(\App\Employee::class)->make();

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/v1/employees', $data->toArray());

        $response->assertStatus(201)->assertJson($data->toArray());
    }

    /**
     * Test if when the token is not present, the action returns error (401).
     *
     * @return void
     */
    public function testUpdateIfReturnErrorWhenTokenNotExists()
    {
        $data = factory(\App\Employee::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('PUT', "/api/v1/employees/{$data->id}", $data->toArray());

        $response->assertStatus(401);
    }

    /**
     * Test if when the token is present and the user exists, the action updates the user.
     *
     * @return void
     */
    public function testUpdateIfUpdatesTheUser()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $data = factory(\App\Employee::class)->create();

        $toUpdate = array_merge($data->toArray(), ['name' => 'teste teste']);

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('PUT', "/api/v1/employees/{$data->id}", $toUpdate);

        $response->assertStatus(200)->assertJson($toUpdate);
    }

    /**
     * Test if when not exists if the action returns 404.
     *
     * @return void
     */
    public function testUpdateIfReturnNotFoundWhenEmployeeNotExists()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $data = factory(\App\Employee::class)->make();

        $toUpdate = array_merge($data->toArray(), ['name' => 'teste teste']);

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('PUT', "/api/v1/employees/10", $toUpdate);

        $response->assertStatus(404);
    }

    /**
     * Test if when the token is not present, the action returns error (401).
     *
     * @return void
     */
    public function testDeleteIfReturnErrorWhenTokenNotExists()
    {
        $data = factory(\App\Employee::class)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('DELETE', "/api/v1/employees/{$data->id}", $data->toArray());

        $response->assertStatus(401);
    }

    /**
     * Test if when the employee not exists, if the action returns Not Found.
     *
     * @return void
     */
    public function testDeleteIfRemovesEmployee()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('DELETE', "/api/v1/employees/15");

        $response->assertStatus(404);
    }

    /**
     * Test if when the token is present and the employee exists, the action remove the employee.
     *
     * @return void
     */
    public function testDeleteIfReturnNotFoundWhenEmployeeNotExists()
    {
        $user = factory(\App\User::class)->create([
            'email' => 'teste@teste.com.br'
        ]);

        $data = factory(\App\Employee::class)->create();

        $response = $this->actingAs($user, 'api')->withHeaders([
            'Accept' => 'application/json',
        ])->json('DELETE', "/api/v1/employees/{$data->id}");

        $response->assertStatus(200);

        $this->assertDeleted('employees', ['id' => $data->id]);
    }
}
