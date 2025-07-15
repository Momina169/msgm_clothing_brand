@extends('layouts._layout')
@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card p-5">
                    <h1 class="mb-4">Get in Touch with MSGM</h1>
                    <p class="lead text-center">We're always delighted to hear from you. Whether you have a question about our latest collections, need assistance with an order, or simply want to share your thoughts, the MSGM team is here to help.</p>

                    <hr class="my-5">

                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h2>Send Us a Message</h2>
                            <form action="#" method="POST"> 
                                @csrf 
                                <div class="mb-3">
                                    <label for="name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Your Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h2>Contact Information</h2>
                            <div class="contact-info">
                                <h3 class="h5">Customer Care</h3>
                                <ul class="list-unstyled">
                                    <li><strong>Email:</strong> <a href="mailto:customer-care@shop-msgm.com">customer-care@shop-msgm.com</a></li>
                                    <li><strong>Telephone:</strong> +92 02 9475 5343<br>
                                        <small class="text-muted"><em>(Mon-Fri, 9:00 a.m. to 1:00 p.m. & 2:00 p.m. to 6:00 p.m. GMT+1)</em></small>
                                    </li>
                                </ul>

                                <h3 class="h5 mt-4">Press & Media Inquiries</h3>
                                <ul class="list-unstyled">
                                    <li><strong>Email:</strong> <a href="mailto:press@msgm.it">press@msgm.it</a></li>
                                </ul>


                                <h3 class="h5 mt-4">Visit Our Store</h3>
                                <address>
                                    <strong>MSGM Store</strong><br>
                                        Shadbhag<br>
                                    20121 Lahore - Pakistan<br>
                                    Phone: +92 02 72095554<br>
                                    Mobile: +92 335 6465915<br>
                                </address>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <h2 class="text-center">Connect  Us on Social Media</h2>
                    <div class="social-icons text-center mb-3">
                        <a href="https://www.instagram.com/msgm/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.facebook.com/MSGM.Official/" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/msgm" target="_blank" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a> {{-- Using fa-x-twitter for the new X icon --}}
                        <a href="https://www.tiktok.com/@msgm.official" target="_blank" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                    <p class="text-center text-muted"><small>Stay updated with our latest collections, campaigns, and news by following us on our social channels.</small></p>
                </div>
            </div>
        </div>
    </div>



@endsection