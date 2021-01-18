<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PollResource;
use App\Models\Poll;
use App\Models\PollAnswer;

class PollController extends Controller
{
  public function createPoll(Request $request) {
    $poll = new Poll;
    $poll->name = $request->name;
    $poll->email = $request->user()->email;
    $poll->variants = json_encode($request->variants);
    $poll->type_variants = 'Несколько из списка';
    $poll->save();
    return new PollResource(Poll::findOrFail($poll->id));
  }

  public function savePoll(Request $request) {
    $poll = Poll::findOrFail($request->pollId);
    $poll->name = $request->pollName;
    $poll->description = $request->pollDescription;
    $poll->variants = json_encode($request->variants);
    $poll->hash = sha1(uniqid($poll->id, true));
    $poll->type_variants = $request->typeVariants;
    $poll->save();
  }

  public function deletePoll(Request $request, $id) {
    $poll = Poll::findOrFail($id);

    $mediaItems = $poll->getMedia('pollImage');
    $mediaItems[0]->delete();

    Poll::where('id', $id)->where('email', $request->user()->email)->delete();
    PollAnswer::where('poll_id', $id)->delete();
  }

  public function getPoll($id) {
    return new PollResource(Poll::findOrFail($id));
  }

  public function getPollByHash($hash) {
    return new PollResource(Poll::where('hash', $hash)->first());
  }

  public function getPollAll(Request $request) {
    $polls = Poll::where('email', $request->user()->email)->get();
    $pollsArray = [];
    foreach($polls as $poll) {
      $pollsArray[] = new PollResource($poll);
    }

    return $pollsArray;
  }

  public function uploadImage(Request $request) {
    $poll = Poll::findOrFail($request->id);

    if ($poll->addMediaFromRequest('pollImage')->toMediaCollection('pollImage')) {
      $image = $poll->getMedia('pollImage')->first()->getFullUrl();
    } else {
      $image = null;
    }
    return response()->json(compact('image'));
  }

  public function deleteImage(Request $request) {
    $poll = Poll::find($request->id);

    $mediaItems = $poll->getMedia('pollImage');
    $mediaItems[0]->delete();
  }
}
