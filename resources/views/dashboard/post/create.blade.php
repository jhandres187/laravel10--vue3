@extends('dashboard')
@section('content')
    <article>
        <h1>create</h1>
        @include('layouts.fragment._errors-form')
        <form action="{{  route('post.store')  }}" method="post">
            @include('dashboard.post._form')
        </form>
    </article>
@endsection