<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  @php 
   if (isset($seoHeaders) ) 
   {  
    $dataFilter = App\Http\Controllers\rjsoft::filteringObjReal($seoHeaders, 'Tittle');
   }
   else 
   {
    $dataFilter = "Admin";
   }
    @endphp
  <title>{{ $dataFilter }}</title>
  @php 
  if (isset($seoHeaders)  ) 
   {  
    $dataFilter = App\Http\Controllers\rjsoft::filteringObjReal($seoHeaders, 'Description');
   }
   else 
   {
    $dataFilter = "Admin";
   }
  
  @endphp
  <meta content="{{ $dataFilter }}" name="description">
  @php 
  if (isset($seoHeaders) ) 
   {  
    $dataFilter = App\Http\Controllers\rjsoft::filteringObjReal($seoHeaders, 'Keyword');
   }
   else 
   {
    $dataFilter = "Admin";
   }
  
 
  @endphp
  <meta content="{{ $dataFilter }}" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="{{ asset('../assets/img/iconRemoveYhoora.png') }}" rel="icon">
  <!-- <link href="{{ asset('../assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon"> -->

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800|Montserrat:300,400,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('../assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('../assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('../assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('../assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('../assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('../assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('../assets/css/style.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ asset('../assets/css/dataTables.css') }}">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">



  <style>
  .btn-group-xs > .btn, .btn-xs {
    padding  : .25rem .4rem;
    font-size  : .875rem;
    line-height  : .5;
    border-radius : .2rem;
  }
  </style>