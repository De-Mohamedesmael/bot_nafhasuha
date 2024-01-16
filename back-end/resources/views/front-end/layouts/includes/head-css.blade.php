<link href="{{asset('assets/front-end/FontAwesome/css/font-awesome.css')}}" rel="stylesheet">
<link href="{{asset('assets/front-end/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/front-end/bootstrap/css/bootstrap-grid.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/front-end/js/bootstrap.min.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/front-end/css/animate.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/front-end/css/responsivemultimenu.css')}}" rel="stylesheet">
<link href="{{asset('assets/front-end/OwlCarousel/dist/assets/owl.carousel.min.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/front-end/OwlCarousel/dist/assets/owl.theme.default.min.css')}}" rel="stylesheet"/>
<link as="style"
      href="https://fonts.googleapis.com/css?family=Tajawal:200,300,400,500,600,700,800,900&display=swap"
      onload="this.onload=null;this.rel='stylesheet'"
      rel="preload">
<noscript>
    <link href="https://fonts.googleapis.com/css?family=Tajawal:200,300,400,500,600,700,800,900&display=swap"
          rel="stylesheet">
</noscript>

<link as="style"
      href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500,600,700,800,900&display=swap"
      onload="this.onload=null;this.rel='stylesheet'"
      rel="preload">
<noscript>
    <link href="https://fonts.googleapis.com/css?family=Roboto:200,300,400,500,600,700,800,900&display=swap"
          rel="stylesheet">
</noscript>

<link href="{{asset('assets/front-end/css/style.css')}}" rel="stylesheet">
<link href="{{asset('assets/front-end/css/responsive.css')}}" rel="stylesheet">
<style>
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    footer a.nav-link {
        color: #fff;
        text-align: end;
        padding: 3px 0px !important;
    }
    footer .social ul li {
        width: 25px;
        height: 25px;
        padding: 0 !important;
        background-color: unset !important;
    }
    .loading-text {
        font-size: 24px;
        color: #333;
    }
    #mobile_navbar {
        display: none;
        padding: 10px;
        position: fixed;
        width: 50%;
        height: 100%;
        z-index: 999999999999;
        background-color: #212529;
        left: 0;
        right: 0;
        top: 0;

        color: #fff !important;
        background-image: url('public/images/headerBackground.svg');

    }
    #mobile_navbar a {
        color: #fff !important;

    }
    .mobile_navbar_open {
        display: flex !important;
        flex-wrap: nowrap;
        flex-direction: column;
        align-content: space-between;
        justify-content: space-evenly;
        align-items: center;
        padding: 26px !important;
    }


</style>
<script>
    // Simulating a 2-second delay
    setTimeout(function () {
        document.querySelector('.loading').style.display = 'none';
    }, 2000);

</script>
