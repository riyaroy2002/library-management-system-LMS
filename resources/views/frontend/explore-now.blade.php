@extends('layouts.frontend.app')
@section('title', 'Explore Now')
@section('content')
    <section class="wrapper bg-light py-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="blog-card h-100 shadow-sm">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('assets/img/books/book1.jpg') }}" alt="Book Image">
                        </div>
                        <div class="blog-body p-4">
                            <h5>Computer Fundamentals: Concepts, Systems & Applications - 8th Edition</h5>
                            <p class="text-dark">
                                ASIN : B08W55CRK4 |
                                Language : English
                            </p>

                            <a href="#" class="btn btn-danger w-100">
                                KNOW MORE &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="blog-card h-100 shadow-sm">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('assets/img/books/book2.jpg') }}" alt="Book Image">
                        </div>
                        <div class="blog-body p-4">
                            <h5>Programming in ANSI C || 9th Edition || by Balagurusamy || McGraw Hill</h5>
                            <p class="text-dark">
                                ISBN-10 : 9355326726 |
                                Language : English
                            </p>

                            <a href="#" class="btn btn-danger w-100">
                                KNOW MORE &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="blog-card h-100 shadow-sm">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('assets/img/books/book3.jpg') }}" alt="Book Image">
                        </div>
                        <div class="blog-body p-4">
                            <h5>Computer Fundamentals: Architecture and Organization || by B. Ram || Sanjay Kumar </h5>
                            <p class="text-dark">
                                ISBN-10 : 9388818555 |
                                Language : English
                            </p>


                            <a href="#" class="btn btn-danger w-100">
                                KNOW MORE &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="blog-card h-100 shadow-sm">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('assets/img/books/book4.jpg') }}" alt="Book Image">
                        </div>
                        <div class="blog-body p-4">
                            <h5>C PROGRAMMING LANGUAGE, 2ND EDN || by Brian W. Kernighan || Dennis Ritchie</h5>
                            <p class="text-dark">
                                ISBN-10 : 9332549443 |
                                Language : English
                            </p>

                            <a href="#" class="btn btn-danger w-100">
                                KNOW MORE &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="blog-card h-100 shadow-sm">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('assets/img/books/book5.jpg') }}" alt="Book Image">
                        </div>
                        <div class="blog-body p-4">
                            <h5>Computer Networking| Eight Editon || by James F. Kurose || Pearson</h5>
                            <p class="text-dark">
                                ISBN-10 : 9356061319 |
                                Language : English
                            </p>

                            <a href="#" class="btn btn-danger w-100">
                                KNOW MORE &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                 <div class="col-md-4">
                    <div class="blog-card h-100 shadow-sm">
                        <div class="book-image-wrapper">
                            <img src="{{ asset('assets/img/books/book6.jpg') }}" alt="Book Image">
                        </div>
                        <div class="blog-body p-4">
                            <h5>Microservices Design Patterns with Java: deploying microservices</h5>
                            <p class="text-dark">
                                ISBN-10 : 9355517009 |
                                Language : English
                            </p>

                            <a href="#" class="btn btn-danger w-100">
                                KNOW MORE &nbsp;<i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
