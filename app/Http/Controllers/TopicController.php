<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Post;
use App\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::LatestFirst()->paginate(3);
        $topicsCollections = $topics->getCollection();
        return fractal()
            ->collection($topicsCollections)
            ->parseIncludes(['user'])
            ->transformWith(new TopicTransformer)
            ->paginateWith(new IlluminatePaginatorAdapter($topics))
            ->toArray();
    }
    public function store(StoreTopicRequest $request)
    {
        $topics = new Topic;
        $topics->title = $request->title;
        $topics->user()->associate($request->user());

        $posts = new Post;
        $posts->body = $request->body;
        $posts->user()->associate($request->user());


        $topics->save();
        $topics->posts()->save($posts);


        return fractal()
            ->item($topics)
            ->parseIncludes(['user','posts','posts.user'])
            ->transformWith(new TopicTransformer)
            ->toArray();
    }

    public function show(Topic $topic)
    {
        return fractal()
            ->item($topic)
            ->parseIncludes(['user','posts','posts.user','posts.likes'])
            ->transformWith(new TopicTransformer)
            ->toArray();
    }

    public function update(UpdateTopicRequest $request,Topic $topic)
    {
        $this->authorize('update',$topic);

        $topic->title = $request->get('title',$topic->title);
        $topic->save();

        return fractal()
            ->item($topic)
            ->parseIncludes(['user'])
            ->transformWith(new TopicTransformer)
            ->toArray();
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy',$topic);
        $topic->delete();

        return response(null,204);
    }

}
