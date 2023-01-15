<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeartbeatTest extends TestCase
{
	public function test_that_the_api_heartbeat_returns_a_successful_response()
	{
		$response = $this->get('/api/heartbeat');

		$response->assertStatus(200);
		$response->assertJson(['success' => true]);
	}
}
