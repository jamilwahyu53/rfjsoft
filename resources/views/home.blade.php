@extends('templates.main', 
    [
    'sliding' => $sliding, 
    'tagline' => $companies->TagLine ?? null,
    'detailsCompanies' => $detailsCompanies,
    'companyName' => $companies->CompanyName ?? null,
    'topMenus' => $topMenus,
    'seoHeaders' => $seoHeaders,
    'slideShows' => $slideShows,
    ])

@section('content')
    <!-- ======= About Section ======= -->
    @include('templates.about')

    <!-- ======= Services Section ======= -->
    @include('templates.services')

    <!-- ======= Clients Section ======= -->
    @include('templates.clients')

    
    <!-- ======= Contact Section ======= -->
    @include('templates.contact')
@endsection