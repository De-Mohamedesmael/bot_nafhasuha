
<script src="{{asset('assets/front-end/web/assets/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/front-end/popper/popper.min.js')}}"></script>
<script src="{{asset('assets/front-end/tether/tether.min.js')}}"></script>
<script src="{{asset('assets/front-end/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.4/js/bootstrap.js"></script>
<script src="{{asset('assets/front-end/smoothscroll/smooth-scroll.js')}}"></script>
<script src="{{asset('assets/front-end/viewportchecker/jquery.viewportchecker.js')}}"></script>
<script src="{{asset('assets/front-end/OwlCarousel/dist/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/front-end/chatbutton/floating-wpp.js')}}"></script>
<script src="{{asset('assets/front-end/chatbutton/script.js')}}"></script>
<script src="{{asset('assets/front-end/dropdown/js/nav-dropdown.js')}}"></script>
<script src="{{asset('assets/front-end/dropdown/js/navbar-dropdown.js')}}"></script>
<script src="{{asset('assets/front-end/touchswipe/jquery.touch-swipe.min.js')}}"></script>
<script src="{{asset('assets/front-end/typed/typed.min.js')}}"></script>
<script src="{{asset('assets/front-end/ytplayer/jquery.mb.ytplayer.min.js')}}"></script>
<script src="{{asset('assets/front-end/vimeoplayer/jquery.mb.vimeo_player.js')}}"></script>
<script src="{{asset('assets/front-end/parallax/jarallax.min.js')}}"></script>
<script src="{{asset('assets/front-end/mbr-tabs/mbr-tabs.js')}}"></script>
<script src="{{asset('assets/front-end/masonry/masonry.pkgd.min.js')}}"></script>
<script src="{{asset('assets/front-end/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('assets/front-end/bootstrapcarouselswipe/bootstrap-carousel-swipe.js')}}"></script>
<script src="{{asset('assets/front-end/mbr-testimonials-slider/mbr-testimonials-slider.js')}}"></script>
<script src="{{asset('assets/front-end/theme/js/script.js')}}"></script>
<script src="{{asset('assets/front-end/gallery/player.min.js')}}"></script>
<script src="{{asset('assets/front-end/gallery/script.js')}}"></script>
<script src="{{asset('assets/front-end/slidervideo/script.js')}}"></script>
<script src="{{asset('assets/front-end/formoid.min.js')}}"></script>

<script>
    $(document).ready(function () {

        $(".client_slider").owlCarousel(
            {
                loop: true,
                nav: false,

                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true
                    },
                    600: {
                        items: 1,
                        nav: false
                    },
                    1000: {
                        items: 3,
                        nav: true,
                        loop: false
                    }
                }

            }
        );
        $(".slider_review").owlCarousel(
            {
                loop: false,
                nav: true,
                dots: false,
                lazyLoad: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: false
                    },
                    600: {
                        items: 1,
                        nav: false
                    },
                    1000: {
                        items: 1,
                        nav: true,
                        loop: false
                    }
                }

            }
        );
        $(".partners_slider").owlCarousel(
            {

                loop: true,
                nav: false,
                dots: false,
                margin: 10,
                responsiveClass: true,

                responsive: {
                    0: {
                        items: 1,
                        nav: false
                    },
                    600: {
                        items: 2,
                        nav: false
                    },
                    1000: {
                        items: 6,
                        nav: false,
                    }
                }

            }
        );
    });


</script>

<script>
    $(document).ready(function () {
        // Smooth Scroll To Div

        $('.links li a').click(function () {

            $('html, body').animate({

                scrollTop: $('#' + $(this).data('value')).offset().top

            }, 1000);

            console.log('#' + $(this).data('value'));

        });
    });
</script>
