@extends('layout')
@section('content')
  <h1>@lang('project.index.title')</h1>

  <div class="ui items">
    <div class="item">
      <a
        href="{{ route('blog') }}"
        class="image"
        target="_blank"
      >
        <img src="{{ asset('img/project/blog.jpg') }}">
      </a>
      <div class="content">
        <a
          href="{{ route('blog') }}"
          class="header"
          target="_blank"
        >
          @lang('project.index.blog.title')
        </a>
        <div class="meta">
          <span>
            @lang('project.index.blog.description')
          </span>
        </div>
        <div class="description">
          <p>
            @lang('project.index.blog.conclusion')
          </p>
        </div>
        <div class="extra">
          @lang('project.index.blog.extra')
        </div>
      </div>
    </div>

    <div class="item">
      <a
        href="{{ asset('img/project/tm.jpg') }}"
        class="image"
        target="_blank"
      >
        <img src="{{ asset('img/project/tm.jpg') }}">
      </a>
      <div class="content">
        <a
          href="{{ asset('piemeram.lv/TM2/TM2.application') }}"
          class="header"
          target="_blank"
        >
          @lang('project.index.tm.title')
        </a>
        <div class="meta">
          <span>
            @lang('project.index.tm.description')
          </span>
        </div>
        <div class="description">
          <p>
            @lang('project.index.tm.conclusion')
          </p>
        </div>
        <div class="extra">
          @lang('project.index.tm.extra')
        </div>
      </div>
    </div>
  </div>
@endsection
