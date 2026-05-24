<section id="services">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Services</h2>
          <p>Teknologi yang dirancang untuk menyederhanakan, mempercepat, dan mengamankan setiap aspek kehidupan dan bisnis Anda.</p>
        </div>

        <div class="row gy-4">
        @foreach ($services as $row)
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <div class="box">
              <div class="icon">
                <img src="{!! $row->Icon !!}" alt="Website Development" style="width: 25vh;">
              </div>
              <h4 class="title"><a href="">{{ $row->ServiceName }}</a></h4>
              <p class="description">{{ $row->DetailService }}</p>
            </div>
          </div>
        @endforeach
       
        </div>

      </div>
    </section><!-- End Services Section -->