@extends('layouts.website')

@section('content')

<style>
    /* =========================================================
       BLOG DETAILS PAGE
    ========================================================= */

    .static-blog-detail-section {
        padding: 20px 0 60px;
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .static-blog-detail-breadcrumb {
        padding: 35px 0 25px;
    }

    .static-blog-detail-breadcrumb ul {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0;
        margin: 0 0 0 20px;
        list-style: none;
    }

    .static-blog-detail-breadcrumb li {
        font-size: 13px;
        color: #888;
    }

    .static-blog-detail-breadcrumb a {
        color: #555;
        text-decoration: none;
        transition: .3s ease;
    }

    .static-blog-detail-breadcrumb a:hover {
        color: #a92d0f;
    }

    .static-blog-detail-breadcrumb .current {
        color: #a92d0f;
    }

    /* =========================================================
       BLOG HEADER
    ========================================================= */

    .static-blog-detail-header {
        max-width: 950px;
        margin: 0 auto 30px;
        text-align: center;
    }

    .static-blog-detail-tag {
        display: inline-block;

        margin-bottom: 12px;

        color: #a92d0f;

        font-size: 12px;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: 1.5px;

        text-decoration: none;
    }

    .static-blog-detail-title {
        margin: 0 auto 15px;

        color: #222;

        font-size: 42px;
        line-height: 1.25;
        font-weight: 500;

        max-width: 900px;
    }

    .static-blog-detail-date {
        color: #888;
        font-size: 13px;
    }

    /* =========================================================
       FEATURED IMAGE
    ========================================================= */

    .static-blog-detail-image {
        width: 100%;
        max-width: 1400px;
        height: 550px;

        margin: 0 auto 35px;

        overflow: hidden;
        border-radius: 10px;

        background: #f7f3ee;
    }

    .static-blog-detail-image img {
        width: 100%;
        height: 100%;

        display: block;

        object-fit: fill;
        object-position: center;
    }

    /* =========================================================
       BLOG CONTENT
    ========================================================= */

    .static-blog-detail-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .static-blog-detail-content p {
        color: #666;
        font-size: 15px;
        line-height: 1.9;

        margin: 0 0 20px;
    }

    .static-blog-detail-content h2 {
        margin: 35px 0 15px;

        color: #222;

        font-size: 26px;
        line-height: 1.4;
        font-weight: 500;
    }

    .static-blog-detail-content h3 {
        margin: 28px 0 12px;

        color: #333;

        font-size: 21px;
        line-height: 1.4;
        font-weight: 500;
    }

    .static-blog-detail-content ul {
        margin: 0 0 25px;
        padding-left: 22px;
    }

    .static-blog-detail-content li {
        color: #666;
        font-size: 15px;
        line-height: 1.8;
        margin-bottom: 8px;
    }

    /* =========================================================
       HIGHLIGHT BOX
    ========================================================= */

    .static-blog-highlight {
        margin: 30px 0;

        padding: 25px 30px;

        background: #faf6f1;

        border-left: 3px solid #a92d0f;
        border-radius: 5px;
    }

    .static-blog-highlight p {
        margin: 0;

        color: #555;

        font-size: 15px;
        line-height: 1.8;
        font-style: italic;
    }

    /* =========================================================
       SHARE
    ========================================================= */

    .static-blog-share {
        display: flex;
        align-items: center;
        justify-content: space-between;

        max-width: 900px;

        margin: 40px auto 0;

        padding: 20px 0;

        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }

    .static-blog-share-title {
        color: #333;
        font-size: 13px;
        font-weight: 600;
    }

    .static-blog-share-links {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .static-blog-share-links a {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 35px;
        height: 35px;

        border: 1px solid #ddd;
        border-radius: 50%;

        color: #555;

        font-size: 12px;

        text-decoration: none;

        transition: .3s ease;
    }

    .static-blog-share-links a:hover {
        background: #a92d0f;
        border-color: #a92d0f;
        color: #fff;
    }

    /* =========================================================
       BACK TO BLOG
    ========================================================= */

    .static-blog-back {
        display: flex;
        justify-content: center;

        margin-top: 30px;
    }

    .static-blog-back a {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        color: #a92d0f;

        font-size: 12px;
        font-weight: 600;

        letter-spacing: .5px;

        text-decoration: none;

        transition: .3s ease;
    }

    .static-blog-back a:hover {
        color: #222;
    }

    /* =========================================================
       RELATED BLOGS
    ========================================================= */

    .static-related-section {
        margin-top: 60px;
        padding-top: 40px;

        border-top: 1px solid #eee;
    }

    .static-related-heading {
        text-align: center;
        margin-bottom: 30px;
    }

    .static-related-heading h2 {
        margin: 0 0 7px;

        color: #a92d0f;

        font-size: 28px;
        font-weight: 500;
    }

    .static-related-heading p {
        margin: 0;

        color: #777;
        font-size: 13px;
    }

    .static-related-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        margin-left: 20px;
        margin-right: 20px;
    }

    .static-related-card {
        width: 100%;
    }

    .static-related-image {
        display: block;

        width: 100%;
        height: 400px;

        overflow: hidden;

        border-radius: 8px;
    }

    .static-related-image img {
        width: 100%;
        height: 100%;

        display: block;

        object-fit: fill;

        transition: transform .5s ease;
    }

    .static-related-card:hover img {
        transform: scale(1.05);
    }

    .static-related-content {
        padding: 13px 2px 0;
    }

    .static-related-tag {
        display: block;

        margin-bottom: 6px;

        color: #a92d0f;

        font-size: 10px;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: 1px;

        text-decoration: none;
    }

    .static-related-title {
        display: -webkit-box;

        margin: 0;

        color: #222;

        font-size: 17px;
        line-height: 1.4;
        font-weight: 500;

        text-decoration: none;

        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .static-related-title:hover {
        color: #a92d0f;
    }

    /* =========================================================
       TABLET
    ========================================================= */

    @media (max-width: 991px) {

        .static-blog-detail-title {
            font-size: 34px;
        }

        .static-blog-detail-image {
            height: 450px;
        }

        .static-related-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .static-related-image {
            height: 300px;
        }
    }

    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 767px) {

        .static-blog-detail-section {
            padding: 10px 0 40px;
        }

        .static-blog-detail-breadcrumb {
            padding: 20px 0 15px;
        }

        .static-blog-detail-breadcrumb ul {
            margin-left: 5px;
        }

        .static-blog-detail-breadcrumb li {
            font-size: 11px;
        }

        .static-blog-detail-header {
            margin-bottom: 20px;
            padding: 0 5px;
        }

        .static-blog-detail-tag {
            font-size: 9px;
            margin-bottom: 8px;
        }

        .static-blog-detail-title {
            font-size: 24px;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        .static-blog-detail-date {
            font-size: 10px;
        }

        .static-blog-detail-image {
            height: 300px;
            margin-bottom: 25px;
            border-radius: 7px;
        }

        .static-blog-detail-content {
            padding: 0 5px;
        }

        .static-blog-detail-content p {
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .static-blog-detail-content h2 {
            font-size: 21px;
            margin: 25px 0 12px;
        }

        .static-blog-detail-content h3 {
            font-size: 18px;
            margin: 22px 0 10px;
        }

        .static-blog-detail-content li {
            font-size: 12px;
            line-height: 1.7;
        }

        .static-blog-highlight {
            padding: 18px 20px;
            margin: 22px 0;
        }

        .static-blog-highlight p {
            font-size: 12px;
        }

        .static-blog-share {
            margin-top: 30px;
            padding: 15px 5px;
        }

        .static-blog-share-title {
            font-size: 11px;
        }

        .static-blog-share-links a {
            width: 30px;
            height: 30px;
            font-size: 10px;
        }

        .static-related-section {
            margin-top: 40px;
            padding-top: 30px;
        }

        .static-related-heading {
            margin-bottom: 20px;
        }

        .static-related-heading h2 {
            font-size: 23px;
        }

        .static-related-heading p {
            font-size: 11px;
        }

        /*
         * 2 RELATED BLOGS PER ROW
         */
        .static-related-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px 10px;
        }

        .static-related-image {
            height: 210px;
            border-radius: 7px;
        }

        .static-related-content {
            padding-top: 9px;
        }

        .static-related-tag {
            font-size: 8px;
            margin-bottom: 4px;
        }

        .static-related-title {
            font-size: 13px;
            line-height: 1.35;
        }
    }

    /* =========================================================
       SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {

        .static-blog-detail-title {
            font-size: 21px;
        }

        .static-blog-detail-image {
            height: 240px;
        }

        .static-related-image {
            height: 180px;
        }

        .static-related-title {
            font-size: 12px;
        }
    }
</style>


<!-- =========================================================
     BLOG DETAILS
========================================================= -->

<section class="static-blog-detail-section">

    <div class="container">


        <!-- =====================================================
             BREADCRUMB
        ====================================================== -->

        <div class="static-blog-detail-breadcrumb">

            <ul>

                <li>

                    <a href="{{ route('home') }}">
                        Home
                    </a>

                </li>

                <li>
                    /
                </li>

                <li>

                    <a href="{{ route('blog') }}">
                        Blog
                    </a>

                </li>

                <li>
                    /
                </li>

                <li class="current">

                    {{ $blogCategory?->name ?? 'Blog' }}

                </li>

            </ul>

        </div>


        <!-- =====================================================
             BLOG HEADER
        ====================================================== -->

        <div class="static-blog-detail-header">

            <a
                href="{{ route('blog') }}"
                class="static-blog-detail-tag"
            >

                {{ $blogCategory?->name ?? 'Blog' }}

            </a>


            <h1 class="static-blog-detail-title">

                {{ $blog->title }}

            </h1>


            <div class="static-blog-detail-date">

                {{ $blog->created_at->format('d M, Y') }}

                &nbsp; • &nbsp;

                @php

                    $content = strip_tags($blog->description ?? '');

                    $wordCount = str_word_count($content);

                    $readingTime = max(1, ceil($wordCount / 200));

                @endphp

                {{ $readingTime }} Min Read

            </div>

        </div>


        <!-- =====================================================
             FEATURED IMAGE
        ====================================================== -->

        <div class="static-blog-detail-image">

            @if(!empty($blog->banner))

                <img
                    src="{{ asset($blog->banner) }}"
                    alt="{{ $blog->title }}"
                >

            @elseif(!empty($blog->image))

                <img
                    src="{{ asset($blog->image) }}"
                    alt="{{ $blog->title }}"
                >

            @endif

        </div>


        <!-- =====================================================
             BLOG CONTENT
        ====================================================== -->

        <div class="static-blog-detail-content">

            @if(!empty($blog->short_description))

                <p>
                    {{ $blog->short_description }}
                </p>

            @endif


            @if(!empty($blog->description))

                {!! $blog->description !!}

            @else

                <p>
                    Blog content will be available soon.
                </p>

            @endif

        </div>


        <!-- =====================================================
             SHARE
        ====================================================== -->

        <div class="static-blog-share">

            <span class="static-blog-share-title">

                SHARE THIS ARTICLE

            </span>


            <div class="static-blog-share-links">


                <!-- FACEBOOK -->

                @if(!empty($blog->facebook))

                    <a
                        href="{{ $blog->facebook }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Facebook"
                    >
                        f
                    </a>

                @else

                    <a
                        href="#"
                        aria-label="Facebook"
                    >
                        f
                    </a>

                @endif


                <!-- INSTAGRAM -->

                @if(!empty($blog->instagram))

                    <a
                        href="{{ $blog->instagram }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="Instagram"
                    >
                        ◎
                    </a>

                @else

                    <a
                        href="#"
                        aria-label="Instagram"
                    >
                        ◎
                    </a>

                @endif


                <!-- YOUTUBE -->

                @if(!empty($blog->youtube))

                    <a
                        href="{{ $blog->youtube }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="YouTube"
                    >
                        Y
                    </a>

                @else

                    <a
                        href="#"
                        aria-label="YouTube"
                    >
                        Y
                    </a>

                @endif


                <!-- WHATSAPP -->

                <a
                    href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . url()->current()) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="WhatsApp"
                >
                    W
                </a>


            </div>

        </div>


        <!-- =====================================================
             BACK TO BLOG
        ====================================================== -->

        <div class="static-blog-back">

            <a href="{{ route('blog') }}">

                <i class="icon icon-ArrowLeft"></i>

                BACK TO BLOG

            </a>

        </div>


        <!-- =====================================================
             RELATED BLOGS
        ====================================================== -->

        <div class="static-related-section">


            <div class="static-related-heading">

                <h2>

                    You May Also Like

                </h2>

                <p>

                    Explore more saree stories and fashion inspiration

                </p>

            </div>


            <div class="static-related-grid">


                @forelse($relatedBlogs as $relatedBlog)


                    <article class="static-related-card">


                        <!-- RELATED IMAGE -->

                        <a
                            href="{{ route('blogdetails', $relatedBlog->slug) }}"
                            class="static-related-image"
                        >

                            @if(!empty($relatedBlog->image))

                                <img
                                    loading="lazy"
                                    src="{{ asset($relatedBlog->image) }}"
                                    alt="{{ $relatedBlog->title }}"
                                >

                            @endif

                        </a>


                        <div class="static-related-content">


                            <!-- RELATED CATEGORY -->

                            <a
                                href="{{ route('blogdetails', $relatedBlog->slug) }}"
                                class="static-related-tag"
                            >

                                {{ $relatedCategories->get($relatedBlog->category_id)?->name ?? 'Blog' }}

                            </a>


                            <!-- RELATED TITLE -->

                            <a
                                href="{{ route('blogdetails', $relatedBlog->slug) }}"
                                class="static-related-title"
                            >

                                {{ $relatedBlog->title }}

                            </a>


                        </div>

                    </article>


                @empty

                    <p>
                        No related blogs available.
                    </p>

                @endforelse


            </div>

        </div>


    </div>

</section>

@endsection