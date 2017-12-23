<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PollController extends Controller 
{
    public function create(Request $request)
    {
        $pollId = DB::transaction(function() use ($request) {
            $pollId = DB::table('poll')
                ->insertGetId($request->only('description'));
           
            foreach ($request->get('options') as $option) {
               DB::table('poll_option')->insert($option + [
                   'poll_id' => $pollId
               ]);
            }
            
            return $pollId;
        });
        
        return response()->json([
            'poll_id' => $pollId
        ]);
    }
    
    
    
    public function get($pollId, Request $request)
    {
        $withVotes = $request->get('withVotes', false);
        
        $query = DB::table('poll')
            ->join('poll_option', 'poll_option.poll_id', '=', 'poll.id')
            ->where('poll.id', $pollId)
            ->select('poll.*', 'poll_option.id as option_id', 'poll_option.description as option_description');
            
        if ($withVotes) {
            $query->join('poll_vote', 'poll_vote.option_id', '=', 'poll_option.id');
        }
        
        $result = $query->get();
        $poll = $result->first();
        
        return response()->json([
            'poll_id' => $poll->id,
            'poll_description' => $poll->description,
            'options' => $result->map(function($obj) {
                return (object) [
                    'option_id' => $obj->option_id,
                    'option_description' => $obj->option_description
                ];
            })
        ]);
    }
    
    public function vote(Request $request) 
    {
        $optionId = $request->get('id');
        
        DB::table('poll_vote')
            ->insert(['option_id' => $optionId]);
        
        return response()->make();
    }
}
