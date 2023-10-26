
<script src="{{asset('assets/front-end/web/assets/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/front-end/bootstrap/js/bootstrap.min.js')}}"></script>

<script src="{{asset('assets/front-end/OwlCarousel/dist/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/front-end/js/responsivemultimenu.js')}}"></script>
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
                        nav: true
                    },
                    600: {
                        items: 1,
                        nav: true
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
        $("#icon_bar").on("click", function () {
            $("#mobile_navbar").slideToggle();
            $("#mobile_navbar").addClass("mobile_navbar_open");

        });
        $(window).on("click", function (event) {
            if (!$(event.target).closest(" #mobile_navbar").length) {
                $("#mobile_navbar").fadeOut();
            }
        });
    });
</script>
