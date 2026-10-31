<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WithdrawalControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_ゲストユーザーが退会()
    {
        $response = $this->post(route('withdrawal'), ['confirmText' => '退会する']);
        $response->assertRedirect(route('index'));
    }

    public function test_フォームバリデーションで失敗()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('withdrawal'), ['confirmText' => '']);
        $response->assertSessionHasErrors(['confirmText']);

        $response = $this->post(route('withdrawal'), ['confirmText' => 'test']);
        $response->assertSessionHasErrors(['confirmText']);
    }

    public function test_正常系()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('withdrawal'), ['confirmText' => '退会する']);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('withdrawal.after'));
        $this->assertGuest();
    }
}
