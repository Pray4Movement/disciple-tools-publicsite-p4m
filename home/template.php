<?php

?>

<!-- Fixed navbar -->
<nav class="navbar navbar-default probootstrap-navbar">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo dt_custom_login_url('home' ) ?>" title="uiCookies:Frame">Location Grid</a>
    </div>

    <div id="navbar-collapse" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="active"><a href="#" data-nav-section="home">Home</a></li>
        <li><a href="#" data-nav-section="features">Overview</a></li>
        <li><a href="#" data-nav-section="pricing">Github</a></li>
        <li><a href="#" data-nav-section="reviews">Download</a></li>
        <li><a href="#" data-nav-section="contact">Contact</a></li>
      </ul>
    </div>

  </div>
</nav>

<section class="probootstrap-hero prohttp://localhost/probootstrap/frame/#featuresbootstrap-slant" style="background-image: url(<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/1.jpg);" data-section="home" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row intro-text">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h1 class="probootstrap-heading probootstrap-animate">Location Grid Project</h1>
          <p> is a geographic location and polygon set for DMM saturation efforts.</p>
        <div class="probootstrap-subheading center">
          <p class="probootstrap-animate">
              <a href="<?php echo site_url() ?>/login"  role="button" class="btn btn-primary">Log In</a>
              <a href="<?php echo site_url() ?>/login/?action=register"  role="button" class="btn btn-primary">Register</a>
              <a href="#features" class="btn btn-default smoothscroll" role="button">See Overview</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="probootstrap-section">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="probootstrap-service-item probootstrap-animate" data-animate-effect="fadeIn">
          <span class="icon icon-phone3"></span>
          <h2>Mobile Optimize</h2>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="probootstrap-link">Learn More <i class="icon-chevron-right"></i></a></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="probootstrap-service-item probootstrap-animate" data-animate-effect="fadeIn">
          <span class="icon icon-wallet2"></span>
          <h2>Increase Revenue</h2>
          <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
          <p><a href="#" class="probootstrap-link">Learn More <i class="icon-chevron-right"></i></a></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="probootstrap-service-item probootstrap-animate" data-animate-effect="fadeIn">
          <span class="icon icon-lightbulb"></span>
          <h2>Smart Idea</h2>
          <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
          <p><a href="#" class="probootstrap-link">Learn More <i class="icon-chevron-right"></i></a></p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="probootstrap-section probootstrap-bg-light" id="features" data-section="features">
  <div class="container">
    <div class="row text-center mb100">
      <div class="col-md-8 col-md-offset-2 probootstrap-section-heading">
        <h2 class="mb30 text-black probootstrap-heading">Overview</h2>
        <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
      </div>
    </div>
    <!-- END row -->
    <div class="row mb100">
      <div class="col-md-8 col-md-pull-2 probootstrap-animate">
        <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/img_showcase_1.jpg" alt="Free Template by uicookies.com" class="img-responsive probootstrap-shadow"></p>
      </div>
      <div class="col-md-4 col-md-pull-1 probootstrap-section-heading">
        <h3 class="text-primary probootstrap-heading-2">Big Benefits for Small Business</h3>
        <p><p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p></p>
        <ul class="probootstrap-list">
          <li class="probootstrap-check">Pointing has no control</li>
          <li class="probootstrap-check">A small river named Duden flows</li>
          <li class="probootstrap-check">Roasted parts of sentences fly into your mouth</li>
        </ul>
      </div>
    </div>
    <!-- END row -->
    <div class="row mb100">
      <!-- <div class="col-md-8 col-md-pull-2"> -->
      <div class="col-md-8 col-md-push-5 probootstrap-animate">
        <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/img_showcase_2.jpg" alt="Free Template by uicookies.com" class="img-responsive probootstrap-shadow"></p>
      </div>
      <!-- <div class="col-md-4 col-md-pull-1"> -->
      <div class="col-md-4 col-md-pull-8 probootstrap-section-heading">
        <h3 class="text-primary probootstrap-heading-2">How Frame is different</h3>
        <p><p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p></p>
        <ul class="probootstrap-list">
          <li class="probootstrap-check">Pointing has no control</li>
          <li class="probootstrap-check">A small river named Duden flows</li>
          <li class="probootstrap-check">Roasted parts of sentences fly into your mouth</li>
        </ul>
      </div>
    </div>
    <!-- END row -->

    <div class="row mb100">
      <div class="col-md-8 col-md-pull-2 probootstrap-animate">
        <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/img_showcase_1.jpg" alt="Free Template by uicookies.com" class="img-responsive probootstrap-shadow"></p>
      </div>
      <div class="col-md-4 col-md-pull-1 probootstrap-section-heading">
        <h3 class="text-primary probootstrap-heading-2">All in One Place</h3>
        <p><p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p></p>
        <ul class="probootstrap-list">
          <li class="probootstrap-check">Pointing has no control</li>
          <li class="probootstrap-check">A small river named Duden flows</li>
          <li class="probootstrap-check">Roasted parts of sentences fly into your mouth</li>
        </ul>
      </div>
    </div>
    <!-- END row -->
  </div>
</section>
<!-- END section -->

<section class="probootstrap-section" data-section="pricing">
  <div class="container">
    <div class="row text-center mb100">
      <div class="col-md-8 col-md-offset-2 probootstrap-section-heading">
        <h2 class="mb30 text-black probootstrap-heading">Choose the plan that???s right for your business </h2>
        <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
      </div>
    </div>
    <!-- END row -->
    <div class="row">
      <div class="col-md-4">
        <div class="probootstrap-pricing">
          <h2>Starter</h2>
          <p class="probootstrap-price"><strong>$00.00</strong></p>
          <p class="probootstrap-note">This is a monthly recurring payment.</p>
          <ul class="probootstrap-list text-left mb50">
            <li class="probootstrap-check">Pointing has no control</li>
            <li class="probootstrap-check">A small river named Duden flows</li>
            <li class="probootstrap-check">Roasted parts of sentences fly into your mouth</li>
          </ul>
          <p><a href="#" class="btn btn-black">Get Started</a></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="probootstrap-pricing probootstrap-popular probootstrap-shadow">
          <h2>Business</h2>
          <p class="probootstrap-price"><strong>$00.00</strong></p>
          <p class="probootstrap-note">This is a monthly recurring payment.</p>
          <ul class="probootstrap-list text-left mb50">
            <li class="probootstrap-check">Pointing has no control</li>
            <li class="probootstrap-check">A small river named Duden flows</li>
            <li class="probootstrap-check">Roasted parts of sentences fly into your mouth</li>
          </ul>
          <p><a href="#" class="btn btn-primary">Get Started</a></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="probootstrap-pricing">
          <h2>Premium</h2>
          <p class="probootstrap-price"><strong>$00.00</strong></p>
          <p class="probootstrap-note">This is a monthly recurring payment.</p>
          <ul class="probootstrap-list text-left mb50">
            <li class="probootstrap-check">Pointing has no control</li>
            <li class="probootstrap-check">A small river named Duden flows</li>
            <li class="probootstrap-check">Roasted parts of sentences fly into your mouth</li>
          </ul>
          <p><a href="#" class="btn btn-black">Get Started</a></p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END section -->

 <section class="probootstrap-section probootstrap-bg-light" data-section="reviews">
  <div class="container">
    <div class="row text-center mb100">
      <div class="col-md-8 col-md-offset-2 probootstrap-section-heading">
        <h2 class="mb30 text-black probootstrap-heading">That???s why 100,000+ Love Frame</h2>
        <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
      </div>
    </div>
    <!-- END row -->
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="probootstrap-testimonial">
          <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/person_1.jpg" class="img-responsive img-circle probootstrap-author-photo" alt="Free Template by uicookies.com"></p>
          <p class="mb10 probootstrap-rate">
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
          </p>
          <blockquote>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
          </blockquote>
          <p class="mb0">&mdash; Garry Alexander</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="probootstrap-testimonial">
          <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/person_2.jpg" class="img-responsive img-circle probootstrap-author-photo" alt="Free Template by uicookies.com"></p>
          <p class="mb10 probootstrap-rate">
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
          </p>
          <blockquote>
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
          </blockquote>
          <p class="mb0">&mdash; James Robertson</p>
        </div>
      </div>
      <div class="clearfix visible-sm-block"></div>
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="probootstrap-testimonial">
          <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/person_3.jpg" class="img-responsive img-circle probootstrap-author-photo" alt="Free Template by uicookies.com"></p>
          <p class="mb10 probootstrap-rate">
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
          </p>
          <blockquote>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
          </blockquote>
          <p class="mb0">&mdash; Ben Goodrich</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="probootstrap-testimonial">
          <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/person_4.jpg" class="img-responsive img-circle probootstrap-author-photo" alt="Free Template by uicookies.com"></p>
          <p class="mb10 probootstrap-rate">
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star-outlined"></i>
          </p>
          <blockquote>
            <p>And if she hasn???t been rewritten, then they are still using her.</p>
          </blockquote>
          <p class="mb0">&mdash; Kip Hugh</p>
        </div>
      </div>
      <div class="clearfix visible-sm-block"></div>
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="probootstrap-testimonial">
          <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/person_2.jpg" class="img-responsive img-circle probootstrap-author-photo" alt="Free Template by uicookies.com"></p>
          <p class="mb10 probootstrap-rate">
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
          </p>
          <blockquote>
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
          </blockquote>
          <p class="mb0">&mdash; James Robertson</p>
        </div>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="probootstrap-testimonial">
          <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/person_3.jpg" class="img-responsive img-circle probootstrap-author-photo" alt="Free Template by uicookies.com"></p>
          <p class="mb10 probootstrap-rate">
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
            <i class="icon-star"></i>
          </p>
          <blockquote>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
          </blockquote>
          <p class="mb0">&mdash; Ben Goodrich</p>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="probootstrap-section">
  <div class="container">
     <div class="row text-center mb100">
      <div class="col-md-8 col-md-offset-2 probootstrap-section-heading">
        <h2 class="mb30 text-black probootstrap-heading">Try It Today</h2>
        <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
        <p><a href="#" class="btn btn-primary">Get It Now</a></p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <p><img src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>img/laptop_1.jpg" alt="Free Template by uicookies.com" class="img-responsive"></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <h4 class="text-black probootstrap-check-2"> What is Instant?</h4>
            <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>

            <h4 class="text-black probootstrap-check-2">How do I use the new features of Frame App?</h4>
            <p>On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country. </p>
          </div>
          <div class="col-md-6">
            <h4 class="text-black probootstrap-check-2">Is this available to my country?</h4>
            <p>The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn???t listen. She packed her seven versalia, put her initial into the belt and made herself on the way.
</p>

            <h4 class="text-black probootstrap-check-2">I have technical problem who do I email?</h4>
            <p>But nothing the copy said could convince her and so it didn???t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END section -->

<section class="probootstrap-section probootstrap-cta">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2 text-center">
        <h2 class="probootstrap-heading">Join With Over 100K Members</h2>
        <p class="probootstrap-sub-heading">But nothing the copy said could convince her and so it didn???t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their.</p>
        <p><a href="#" class="btn btn-black">Get Started</a></p>
      </div>
    </div>
  </div>
</section>
<!-- <section class"probootstrap-section probootstrap-cta">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <h2 class="probootstrap-heading">Join With Over 100K Members</h2>
        <p class="probootstrap-sub-heading">But nothing the copy said could convince her and so it didn???t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their.</p>

      </div>
    </div>
  </div>
</section> -->
<!-- END section -->
<section class="probootstrap-section probootstrap-bg-light" data-section="contact">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <form action="" class="probootstrap-form">
          <h2 class="text-black mt0">Get In Touch</h2>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Your Name">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" placeholder="Your Email">
          </div>
          <div class="form-group">
            <input type="email" class="form-control" placeholder="Your Phone">
          </div>
          <div class="form-group">
            <textarea class="form-control"cols="30" rows="10" placeholder="Write a Message"></textarea>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Sebd Message">
          </div>
        </form>
      </div>
      <div class="col-md-3 col-md-push-1">
        <ul class="probootstrap-contact-details">
          <li>
            <span class="text-uppercase">Email</span>
            probootstrap@gmail.com
          </li>
          <li>
            <span class="text-uppercase">Phone</span>
            +30 976 1382 9921
          </li>
          <li>
            <span class="text-uppercase">Fax</span>
            +30 976 1382 9922
          </li>
          <li>
            <span class="text-uppercase">Address</span>
            San Francisco, CA <br>
            4th Floor8 Lower  <br>
            San Francisco street, M1 50F
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>


<footer class="probootstrap-footer">
  <div class="container text-center">
    <div class="row">
      <div class="col-md-12">
        <p class="probootstrap-social"><a href="#"><i class="icon-twitter"></i></a> <a href="#"><i class="icon-facebook2"></i></a> <a href="#"><i class="icon-instagram2"></i></a><a href="#"><i class="icon-linkedin"></i></a></p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        &copy; 2017 <a href="https://uicookies.com/">uiCookies:Frame</a>. All Rights Reserved. <br> Designed &amp; Developed by <a href="https://uicookies.com">uicookies.com</a> <br> Demo Images by <a href="https://unsplash.com">Unsplash</a>
      </div>
    </div>
  </div>
</footer>



<script src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>js/scripts.min.js"></script>
<script src="<?php echo trailingslashit( plugin_dir_url(__FILE__) ) ?>js/custom.js"></script>

