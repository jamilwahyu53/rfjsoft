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
    </style>
    @endif
    <style>
        canvas {
            border: 1px solid #000;
            touch-action: none; /* biar di mobile bisa swipe */
        }
        .btnOrange-sm {
          background-color: #e54c23; /* warna background */
          color: #ffffff;            /* warna teks putih */
          display: inline-block;     /* opsional, agar ukuran sesuai konten */
        }
        .btnOrange {
          background-color: #e54c23; /* warna background */
          color: #ffffff;            /* warna teks putih */
          padding: 10px 20px;        /* opsional, untuk spacing */
          border-radius: 4px;        /* opsional, rounded corner */
          display: inline-block;     /* opsional, agar ukuran sesuai konten */
        }
        .btnToska {
          background-color: #0193a2; /* warna background */
          color: #ffffff;            /* warna teks putih */
          padding: 10px 20px;        /* opsional, untuk spacing */
          border-radius: 4px;        /* opsional, rounded corner */
          display: inline-block;     /* opsional, agar ukuran sesuai konten */
        }

        /* Tombol hamburger fixed top */
      .mobile-menu-btn {
          position: fixed;
          top: 10px;   /* jarak dari atas */
          right: 10px; /* jarak dari kanan */
          z-index: 1050; /* lebih tinggi dari konten */
      }

      /* beri padding-top pada main di mobile agar tidak tertutup tombol */
      @media (max-width: 991.98px) {
          main {
              padding-top: 60px; /* sesuaikan dengan tinggi tombol */
          }
      }
      
      
      .card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 1rem 2rem rgba(0,0,0,0.2);
}


      


     /* Container kartu */
#ktpCard {
    position: relative;       /* HARUS relative agar overlay bisa absolute */
    max-width: 350px;         /* sesuaikan ukuran tampilan modal */
    width: 100%;
    margin: 0 auto;           /* center di modal */
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 8px rgba(0,0,0,0.15);
}

/* Gambar kartu */
#ktpCard img {
    width: 100%;
    height: auto;             /* biar proporsional */
    display: block;
}

/* Nama overlay di atas gambar */
.name-overlay {
    position: absolute;       /* HARUS absolute */
    top: 55%;                 /* vertical center */
    left: 50%;                /* horizontal center */
    transform: translate(-50%, -50%); /* benar-benar center */
    color: white;
    font-weight: bold;
    font-size: 1.5rem;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    pointer-events: none;     /* biar tidak mengganggu klik */
    text-align: center;
    white-space: nowrap;
    width: 100%;              /* optional, agar teks center penuh */
}


@media print {
  /* Overlay nama */
  #ktpCard .name-overlay {
    font-size: 8pt; /* ukuran kecil untuk kartu portrait */
    text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.7);
  }

  /* KTP card tetap ukurannya */
  #ktpCard {
    position: fixed;
    top: 20mm;
    left: 20mm;
    width: 54mm !important;
    height: 85.6mm !important;
    overflow: hidden;
  }

  #ktpCard img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  body * {
    visibility: hidden;
  }

  #ktpCard, #ktpCard * {
    visibility: visible;
  }

  @page {
    size: A4 portrait;
    margin: 0;
  }

  body {
    margin: 0;
    padding: 0;
  }
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
    


    .fullscreen-bg {
      background-image: url('{{ asset('../assets/img/kemenag/backgroundscreen.png') }}');
      background-size: cover;       /* isi seluruh layar */
      background-position: center;  /* selalu di tengah */
      background-repeat: no-repeat; 
      width: 100vw;
      height: 100vh;
  }



    </style>
</head>

<body>
  
@if($sidebars != null)
  @include('Admin.sidebar', ['sidebars' => $sidebars])
@endif

  <main id="main">

    @yield('content')

  </main><!-- End #main -->


  @include('EntranceTemplate.footer')

    <!-- Vue 3 CDN cukup di layout -->
    <!--<script src="{{ asset('../assets/js/vue.global.js') }}"></script> -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
	
    <script src="{{ asset('../assets/js/services/ApiServices.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>


    @yield('scripts')

</body>

</html>