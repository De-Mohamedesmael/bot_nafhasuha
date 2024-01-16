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


<link href="{{asset('assets/front-end/css/style_provider.css')}}" rel="stylesheet">
<style>
    .loading {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .loading-text {
        font-size: 24px;
        color: #333;
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
</style>
<script>
    // Simulating a 2-second delay
    setTimeout(function () {
        document.querySelector('.loading').style.display = 'none';
        document.querySelector('.content').style.display = 'block';
    }, 2000);
</script>


