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
    $poll->name = $request->name ?? 'Без названия';
    $poll->email = $request->user()->email ?? '';
    $poll->ip = $request->fingerprint;
    $poll->variants = json_encode($request->variants) ?? '';
    $poll->hash = sha1(uniqid($poll->id, true));
    $poll->type_variants = 'Несколько из списка';
    $poll->save();
    return new PollResource(Poll::findOrFail($poll->id));
  }

  public function savePoll(Request $request) {
    $poll = Poll::findOrFail($request->pollId);
    if(!$request->user() && $request->fingerprint != $poll->ip) {
      return response("It's Not Your", 401);
    }
    $poll->name = $request->pollName;
    $poll->description = $request->pollDescription;
    $poll->video_link = $request->videoLink;
    $poll->variants = json_encode($request->variants);
    $poll->type_variants = $request->typeVariants;
    $poll->save();
  }

  public function deletePoll(Request $request) {
    $email = $request->user() ? $request->user()->email : '';
    $poll = Poll::findOrFail($request->id);
    if($poll->email != $email && $poll->ip != $request->fingerprint) {
      return response("It's Not Your", 401);
    }
    $mediaItems = $poll->getMedia('pollImage');
    if(count($mediaItems)) {
      $mediaItems[0]->delete();
    }

    $poll->delete();
    PollAnswer::where('poll_id', $request->id)->delete();
  }

  public function getPoll(Request $request) {
    $poll = Poll::where('hash', $request->hash)->first();
    if($poll == null) return response('Not Found', 400);
    if(($request->user() && $request->user()->email == $poll->email) || $request->fingerprint == $poll->ip)
      return new PollResource($poll);
    else
      return response('Its Not Your', 401);
  }

  public function getPollByHash($hash) {
    return new PollResource(Poll::where('hash', $hash)->first());
  }

  public function getPollAll(Request $request) {
    $polls = Poll::where('email', $request->user()->email)->orWhere('ip', $request->fingerprint)->orderBy('created_at')->get();

    return PollResource::collection($polls);
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

  /* public function checkIp(Request $request) {
    $poll = Poll::find($request->poll_id);

    if($poll != null && $poll->ip === $request->ip()) return true;
    else return false;
  } */
}
