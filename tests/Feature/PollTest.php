<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class PollTest extends TestCase
{
    
    public function newPoll()
    {
        $response = $this->post(route('api.poll.create'), [
            'description' => 'enquete de teste',
            'options' => [
                [
                    'description' => 'option 1',
                ],
                [
                    'description' => 'option 2',
                ],
            ]
        ]);
        $response->assertStatus(200);
        
        $this->assertEquals(DB::table('poll')->count(), 1);   
        
        $json = (object) $response->json();
        
        return $json->poll_id;
    }
    
    public function getPoll()
    {
        $pollId = $this->newPoll();
        
        $response = $this->get(route('api.poll.get', ['id' => $pollId]));
        
        $response->assertStatus(200);
        
        return (object) $response->json();
    }
    
    public function testVote()
    {
        $poll = $this->getPoll();
        $optionId =$poll->options[0]['option_id'];
            
        $response = $this->post(route('api.poll.vote', ['id' => $optionId]));
        $response->assertStatus(200);
        
        $this->assertEquals(1, $this->countVotes($optionId));
        
        $this->post(route('api.poll.vote', ['id' => $optionId]));
        $this->assertEquals(2, $this->countVotes($optionId));
        
    }
    
    protected function countVotes($optionId)
    {
        return DB::table('poll_vote')->where('option_id', $optionId)->count();
    }
}
