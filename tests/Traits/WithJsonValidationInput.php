<?php

namespace Vesaka\Games\Tests\Traits;
use Illuminate\Testing\TestResponse;
/**
 *
 * @author vesak
 */
trait WithJsonValidationInput {
    
    protected function postInvalidData(string $routeName, array $data, array|string|null $attributes = null): void {
        $response = $this->json('post', route($routeName), $data);
        $response->assertStatus(422);
        
        if (!is_array($attributes)) {
            $attributes = [$attributes ?: array_keys($data)[0]];
        }
        
        foreach($attributes as $attribute) {
            $response->assertJsonValidationErrorFor($attribute);
        }
        
    }
    
    
    protected function postValidData(string $routeName, array $data): TestResponse {
        $response = $this->json('post', route($routeName), $data);
        $response->assertStatus(200);
        return $response;
    }
}
