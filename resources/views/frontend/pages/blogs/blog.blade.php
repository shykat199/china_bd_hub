@extends('frontend.layouts.front')

@section('title', 'Page Title')

@section('content')

<div class="maan-blog-section maan-section py-5">
        <div class="container">
            <div class="maan-blog-wraper">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="maan-card-area">
                            <div class="row">
                                @foreach($blogs as $blog)
                                <div class="col-md-6">
                                    <div class="blog-card wow fadeInUp">
                                        <a href="{{route('frontend.blog.details',$blog->slug)}}" class="card-thumb">
                                            <img src="{{ asset('/uploads/blogs/'.$blog->image) }}" alt="">
                                            <span class="maan-category-btn maan-btn">{{ $blog->category->name }}</span>
                                        </a>
                                        <div class="card-description">
                                            <div class="author-date">
                                                <a href="/blog-details" class="date"><span><img src="/public/frontend/img/icons/calendar.svg" alt=""></span> {{ $blog->created_at->translatedFormat(' F j, Y') }}</a>
                                                <a href="#" class="author"><span><img src="{{ asset('uploads/users/'.$blog->user->avatar) }}" alt=""></span>{{__('by ') }}{{ $blog->user->name }}</a>
                                            </div>
                                            <a href="{{route('frontend.blog.details',$blog->slug)}}" class="post-title">{{$blog->title}}</a>
                                            <a href="{{route('frontend.blog.details',$blog->slug)}}" class="link"><i class="fal fa-long-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <nav class="maan-pagination wow fadeInUp">
                                <ul class="pagination">
                                  {{ $blogs->links() }}

                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="maan-wedgets-area">
                            <div class="maan-wedgets wow fadeInUp" data-wow-delay="0.1s">
                                <h2 class="wedgets-title">{{__('Search')}}</h2>

                                    <div class="maan-input-group">
                                        <input type="text" placeholder="Search keywords">
                                        <button class="maan-btn"><i class="fal fa-search"></i></button>
                                    </div>

                            </div>
                            <div class="maan-wedgets wow fadeInUp" data-wow-delay="0.4s">
                                <h2 class="wedgets-title">{{ __('Categories') }} </h2>
                                <ul class="categories">
                                    @foreach($categories as $category)
                                    <li><a href="/blog-details">{{ $category->name }}<span>{{ $category->blogs->count() }}</span></a></li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="maan-wedgets wow fadeInUp" data-wow-delay="0.7s">
                                <h2 class="wedgets-title">{{ __('Recent Post') }}</h2>
                                @foreach($blogs as $recentpost)
                                    @if ($loop->iteration<6)

                                <div class="blog-post-categories">
                                    <a href="{{route('frontend.blog.details',$recentpost->slug)}}" class="post-thumb"><img src="{{ asset('/uploads/blogs/'.$recentpost->image) }}" alt=""></a>
                                    <div class="post-content">
                                        <a href="{{route('frontend.blog.details',$recentpost->slug)}}" class="post-title">{{ $recentpost->title }}</a>
                                        <a href="#" class="post-date">{{ $recentpost->created_at->translatedFormat(' F j, Y') }}</a>
                                    </div>
                                </div>
                                    @endif
                                @endforeach

                            </div>
                            <div class="maan-wedgets wow fadeInUp" data-wow-delay="1s">
                                <h2 class="wedgets-title">Instagram Post</h2>
                                <div class="instagram-post">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/04.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/05.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/06.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/07.png" alt="">
                                                <a href="" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/08.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/09.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/10.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/11.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="instagram-thumb">
                                                <img src="/public/frontend/img/blog/12.png" alt="">
                                                <a href="/blog-details" class="link"><i class="fab fa-instagram"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="maan-wedgets wow fadeInUp mb-0" data-wow-delay="1.3s">
                                <h2 class="wedgets-title">Populer Tags</h2>
                                <ul class="maan-popular-tags">
                                    <li><a href="/blog-details" class="maan-btn">web design</a></li>
                                    <li><a href="/blog-details" class="maan-btn">ui/ux design</a></li>
                                    <li><a href="/blog-details" class="maan-btn">graphics</a></li>
                                    <li><a href="/blog-details" class="maan-btn">design</a></li>
                                    <li><a href="/blog-details" class="maan-btn">icon</a></li>
                                    <li><a href="/blog-details" class="maan-btn">graphics design</a></li>
                                    <li><a href="/blog-details" class="maan-btn">branding</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
