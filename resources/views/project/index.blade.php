@extends('layout')
@section('content')
  <h1>@lang('project.index.title')</h1>

  <div class="ui cards">
    <div class="card">
      <a
        class="image"
        href="https://chat.piemeram.lv"
        target="_blank"
      >
        <img src="{{ asset('img/project/chat.jpg') }}">
      </a>

      <div class="content">
        <div class="header">
          <a
            class="image"
            href="https://chat.piemeram.lv"
            target="_blank"
          >
            @lang('project.index.chat.title')
          </a>
        </div>

        <div class="meta">
          @lang('project.index.chat.description')
        </div>

        <div class="description">
          <a
            href="https://github.com/narkoze/chat"
            target="_blank"
          >
            <i class="grey large fitted github square icon"></i>
          </a>
        </div>
      </div>

      <div class="extra content">
        <span>
          @lang('project.index.chat.extra')
        </span>
        <span class="right floated">
          In progress
        </span>
      </div>
    </div>

    <div class="card">
      <a
        class="image"
        href="{{ route('movie.index') }}"
        target="_blank"
      >
        <img src="{{ asset('img/project/movie.jpg') }}">
      </a>

      <div class="content">
        <div class="header">
          <a
            class="image"
            href="{{ route('movie.index') }}"
            target="_blank"
          >
            @lang('project.index.movie.title')
          </a>
        </div>

        <div class="meta">
          @lang('project.index.movie.description')
        </div>
      </div>

      <div class="extra content">
        <span>
          @lang('project.index.movie.extra')
        </span>
        <span class="right floated">
          2018-12-21
        </span>
      </div>
    </div>

    <div class="card">
      <a
        class="image"
        href="https://blog.piemeram.lv"
        target="_blank"
      >
        <img src="{{ asset('img/project/blog.jpg') }}">
      </a>

      <div class="content">
        <div class="header">
          <a
            class="image"
            href="https://blog.piemeram.lv"
            target="_blank"
          >
            @lang('project.index.blog.title')
          </a>
        </div>

        <div class="meta">
          @lang('project.index.blog.description')
        </div>

        <div class="description">
          <a
            href="https://github.com/narkoze/blog"
            target="_blank"
          >
            <i class="grey large fitted github square icon"></i>
          </a>
        </div>
      </div>

      <div class="extra content">
        <span>
          @lang('project.index.blog.extra')
        </span>
        <span class="right floated">
          2018-12-20
        </span>
      </div>
    </div>
  </div>
@endsection
