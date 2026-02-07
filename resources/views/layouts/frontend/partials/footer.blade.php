    <footer class="bg-dark">
        <div class="container py-13 py-md-15">


            <div class="row gy-6 gy-lg-0">
                <div class="col-md-4 col-lg-3">
                    <div class="widget">

                        <p class="mb-4">
                            © {{ date('Y') }} LMS.
                            <br class="d-none d-lg-block" />
                            All rights reserved.
                        </p>
                        <nav class="nav social">
                            <a href="#"><i class="uil uil-twitter text-white"></i></a>
                            <a href="#"><i class="uil uil-facebook-f text-white"></i></a>
                            <a href="#"><i class="uil uil-dribbble text-white"></i></a>
                            <a href="#"><i class="uil uil-instagram text-white"></i></a>
                            <a href="#"><i class="uil uil-youtube text-white"></i></a>
                        </nav>

                    </div>

                </div>

                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title ls-sm mb-3">Get in Touch</h4>
                        <address class="pe-xl-15 pe-xxl-17">7th Floor, Unit No- 7E, Bengal Eco Intelligent Park, Plot
                            No. 3, EM Block, Sector V, Bidhannagar, Kolkata, West Bengal 700091</address>
                        <a href="mailto:#" class="link-body">lms@yopmail.com</a><br />9999999999
                    </div>
                </div>


                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title ls-sm mb-3">Quick Links</h4>
                        <ul class="list-unstyled text-reset mb-0">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('about-us') }}">About Us</a></li>
                            <li><a href="{{ route('contact-us') }}">Contact Us</a></li>
                            <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-12 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title ls-sm mb-3">Our Newsletter</h4>
                        <p class="mb-5">Subscribe to our newsletter to get our news & deals delivered to you.</p>
                        <div class="newsletter-wrapper">
                            <div id="mc_embed_signup2">
                                <form action="{{ route('news-letter.store') }}" method="POST"
                                    id="mc-embedded-subscribe-form2" name="mc-embedded-subscribe-form" class="validate">
                                    @csrf
                                    <div id="mc_embed_signup_scroll2">
                                        <div class="mc-field-group input-group form-floating">
                                            <input type="email" value="" name="email"
                                                class="required email form-control" placeholder="" id="mce-EMAIL2">
                                            @error('email')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <label for="mce-EMAIL2">Email Address</label>
                                            <input type="submit" value="Join" name="subscribe"
                                                id="mc-embedded-subscribe2" class="btn btn-primary ">
                                        </div>
                                        <div id="mce-responses2" class="clear">
                                            <div class="response" id="mce-error-response2" style="display:none"></div>
                                            <div class="response" id="mce-success-response2" style="display:none"></div>
                                        </div>

                                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input
                                                type="text" name="b_ddc180777a163e0f9f66ee014_4b1bcfa0bc"
                                                tabindex="-1" value=""></div>
                                        <div class="clear"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </footer>
