@extends('layouts.website')

@section('content')

    <style>
        /* =========================================================
               BLOG PAGE
            ========================================================= */

        .static-blog-section {
            padding: 20px 0 50px;
        }

        /* =========================================================
               BREADCRUMB
            ========================================================= */

        .static-blog-breadcrumb {
            padding: 35px 0 20px;
        }

        .static-blog-breadcrumb ul {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0;
            margin-left: 20px;
            list-style: none;
        }

        .static-blog-breadcrumb li {
            font-size: 13px;
            color: #888;
        }

        .static-blog-breadcrumb a {
            color: #555;
            text-decoration: none;
        }

        .static-blog-breadcrumb .current {
            color: #a92d0f;
        }

        /* =========================================================
               HEADING
            ========================================================= */

        .static-blog-heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .static-blog-heading h2 {
            margin: 0 0 7px;
            color: #a92d0f;
            font-size: 30px;
            font-weight: 500;
            letter-spacing: -0.5px;
        }

        .static-blog-heading p {
            margin: 0;
            color: #777;
            font-size: 14px;
        }

        /* =========================================================
               BLOG GRID
            ========================================================= */

        .static-blog-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 35px 20px;
            margin: 20px;
        }

        /* =========================================================
               BLOG CARD
            ========================================================= */

        .static-blog-card {
            width: 100%;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        /* =========================================================
               BLOG IMAGE
            ========================================================= */

        .static-blog-image {
            position: relative;
            display: block;
            width: 100%;
            height: 420px;
            overflow: hidden;
            border-radius: 8px;
            background: #f7f3ee;
        }

        .static-blog-image img {
            width: 100%;
            height: 100%;
            display: block;

            /*
                 * Keeps all images the same size
                 */
            object-fit: fill;
            object-position: center;

            transition: transform .5s ease;
        }

        .static-blog-card:hover .static-blog-image img {
            transform: scale(1.05);
        }

        /* =========================================================
               DATE
            ========================================================= */

        .static-blog-date {
            position: absolute;
            left: 15px;
            bottom: 15px;

            padding: 7px 12px;

            background: #fff;
            color: #555;

            border-radius: 5px;

            font-size: 11px;
            font-weight: 500;
            line-height: 1;
        }

        /* =========================================================
               BLOG CONTENT
            ========================================================= */

        .static-blog-content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;

            padding: 16px 3px 0;
        }

        /* =========================================================
               BLOG TAG
            ========================================================= */

        .static-blog-tag {
            display: block;

            margin-bottom: 8px;

            color: #a92d0f;

            font-size: 11px;
            font-weight: 600;

            text-transform: uppercase;
            letter-spacing: 1px;

            text-decoration: none;

            line-height: 1.3;
        }

        /* =========================================================
               BLOG TITLE
            ========================================================= */

        .static-blog-title {
            display: -webkit-box;

            width: 100%;
            min-height: 54px;

            margin-bottom: 10px;

            color: #222;

            font-size: 20px;
            line-height: 1.35;
            font-weight: 500;

            text-decoration: none;

            /*
                 * All titles get same height
                 */
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;

            transition: .3s ease;
        }

        .static-blog-title:hover {
            color: #a92d0f;
        }

        /* =========================================================
               DESCRIPTION
            ========================================================= */

        .static-blog-description {
            display: -webkit-box;

            width: 100%;
            min-height: 44px;

            margin: 0 0 15px;

            color: #777;

            font-size: 13px;
            line-height: 1.7;

            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* =========================================================
               READ ARTICLE
            ========================================================= */

        .static-blog-read {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            color: #333;

            font-size: 12px;
            font-weight: 600;

            letter-spacing: .5px;

            text-decoration: none;

            margin-top: auto;

            transition: .3s ease;
        }

        .static-blog-read i {
            font-size: 13px;
            transition: transform .3s ease;
        }

        .static-blog-read:hover {
            color: #a92d0f;
        }

        .static-blog-read:hover i {
            transform: translateX(4px);
        }

        /* =========================================================
               TABLET
            ========================================================= */

        @media (max-width: 991px) {

            .static-blog-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 30px 16px;
            }

            .static-blog-image {
                height: 320px;
            }

            .static-blog-title {
                font-size: 18px;
                min-height: 49px;
            }
        }

        /* =========================================================
               MOBILE
            ========================================================= */

        @media (max-width: 767px) {

            .static-blog-section {
                padding: 10px 0 35px;
            }

            .static-blog-breadcrumb {
                padding: 20px 0 15px;
            }

            .static-blog-heading {
                margin-bottom: 22px;
            }

            .static-blog-heading h2 {
                font-size: 24px;
            }

            .static-blog-heading p {
                font-size: 12px;
                line-height: 1.5;
            }

            /*
                 * 2 BLOGS PER ROW
                 */
            .static-blog-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 25px 10px;
            }

            .static-blog-image {
                width: 100%;
                height: 240px;
                border-radius: 7px;
            }

            .static-blog-date {
                left: 7px;
                bottom: 7px;

                padding: 5px 7px;

                font-size: 8px;
            }

            .static-blog-content {
                padding: 10px 2px 0;
            }

            .static-blog-tag {
                font-size: 8px;
                margin-bottom: 5px;
            }

            .static-blog-title {
                font-size: 14px;
                line-height: 1.35;

                min-height: 38px;
                max-height: 38px;

                margin-bottom: 6px;

                -webkit-line-clamp: 2;
            }

            .static-blog-description {
                font-size: 10px;
                line-height: 1.5;

                min-height: 30px;
                max-height: 30px;

                margin-bottom: 8px;

                -webkit-line-clamp: 2;
            }

            .static-blog-read {
                font-size: 9px;
                gap: 4px;
            }

            .static-blog-read i {
                font-size: 10px;
            }
        }

        /* =========================================================
               SMALL MOBILE
            ========================================================= */

        @media (max-width: 480px) {

            .static-blog-grid {
                gap: 22px 8px;
            }

            .static-blog-image {
                height: 210px;
            }

            .static-blog-title {
                font-size: 13px;
                min-height: 35px;
                max-height: 35px;
            }

            .static-blog-description {
                font-size: 9.5px;
                min-height: 29px;
                max-height: 29px;
            }
        }
    </style>


    <!-- =========================================================
             BLOG PAGE
        ========================================================= -->

<section class="static-blog-section">

    <div class="container">

        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

        <div class="static-blog-breadcrumb">

            <ul>

                <li>
                    <a href="{{ route('home') }}">
                        Home
                    </a>
                </li>

                <li>
                    /
                </li>

                <li class="current">
                    Blog
                </li>

            </ul>

        </div>


        {{-- =====================================================
             HEADING
        ====================================================== --}}

        <div class="static-blog-heading">

            <h2>
                BAKASURAN Food Stories
            </h2>

            <p>
                Discover delicious recipes, food trends, cooking tips,
                restaurant favourites and inspiration for every craving.
            </p>

        </div>


        {{-- =====================================================
             BLOG GRID
        ====================================================== --}}

        <div class="static-blog-grid">

            @forelse($blogs as $blog)

                <article class="static-blog-card">

                    <a
                        href="{{ route('blogdetails', $blog->slug) }}"
                        class="static-blog-image"
                    >

                        <img
                            loading="lazy"
                            src="{{ asset($blog->image) }}"
                            alt="{{ $blog->title }}"
                        >

                        <span class="static-blog-date">
                            {{ $blog->created_at->format('d M, Y') }}
                        </span>

                    </a>


                    <div class="static-blog-content">

                        <a
                            href="{{ route('blogdetails', $blog->slug) }}"
                            class="static-blog-tag"
                        >

                            {{ $blogCategories[$blog->category_id]->name ?? 'Food Blog' }}

                        </a>


                        <a
                            href="{{ route('blogdetails', $blog->slug) }}"
                            class="static-blog-title"
                        >

                            {{ $blog->title }}

                        </a>


                        <p class="static-blog-description">

                            {{ $blog->short_description }}

                        </p>


                        <a
                            href="{{ route('blogdetails', $blog->slug) }}"
                            class="static-blog-read"
                        >

                            READ ARTICLE

                            <i class="icon icon-ArrowRight"></i>

                        </a>

                    </div>

                </article>

            @empty

                <div class="static-blog-empty">

                    <p>
                        No food stories available at the moment.
                        Please check back soon.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection