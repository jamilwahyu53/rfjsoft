<!DOCTYPE html>
<html lang="en">

<head>
    @include('templates.header')
    @if($sidebars != null)
    <style>
      @media (min-width: 991.98px) {
        main {
          padding-left: 240px;
        }
      }
      /* Sidebar */
      .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        padding: 58px 0 0; /* Height of navbar */
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
        width: 240px;
        z-index: 600;
      }

      @media (max-width: 991.98px) {
        .sidebar {
          width: 100%;
        }
      }
      .sidebar .active {
        border-radius: 5px;
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
      }

      .sidebar-sticky {
        position: relative;
        top: 0;
        height: calc(100vh - 48px);
        padding-top: 0.5rem;
        overflow-x: hidden;
        overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
      }

      #loader {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(6px);
      background: rgba(255, 255, 255, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      z-index: 9999;
      opacity: 1;
      transition: opacity .6s ease;
    }

    #loader.fade-out {
      opacity: 0;
      pointer-events: none;
    }

    .loader-logo {
      width: 120px;
      height: auto;
      margin-bottom: 20px;
      animation: logoPop .7s ease;
    }

    @keyframes logoPop {
      0% { transform: scale(0.5); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }



    

    .container-fluid, .fullscreen-bg, .card.fullscreen-card {
    padding: 0 !important;
    margin: 0 !important;
    max-width: none !important;
    width: 100vw !important;
    height: 100vh !important;
}

.fullscreen-bg {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    background: url('/images/background.jpg') no-repeat center center fixed !important;
    background-size: cover !important;
}

.fullscreen-card {
    background-color: rgba(255, 255, 255, 0.95) !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    align-items: center !important;
    width: 100vw !important;
    height: 100vh !important;
    text-align: center !important;
}

.fullscreen-card img {
    width: 80vw !important;
    height: auto !important;
    max-height: 70vh !important;
    object-fit: contain !important;
    margin-bottom: 2rem !important;
    border-radius: 50% !important;
}

.fullscreen-card h3 {
    font-size: 10vw !important;
    font-weight: bold !important;
    margin-bottom: 1rem !important;
}

.fullscreen-card p {
    font-size: 6vw !important;
    margin-bottom: 0.8rem !important;
    color: #222 !important;
}

.fullscreen-card .text-muted {
    font-size: 5vw !important;
}



    </style>
    @endif
</head>

<body>
  
@if($sidebars != null)
  @include('Admin.sidebar', ['sidebars' => $sidebars])
@endif

  <main id="main">

    @yield('content')

  </main><!-- End #main -->


  @include('templates.footer')

</body>

</html>