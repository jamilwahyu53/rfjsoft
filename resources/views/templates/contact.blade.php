<section id="contact">
      <div class="container" data-aos="fade-up">
        <div class="section-header">
          <h2>Contact Us</h2>
          <p>Kami ingin menjalin komunikasi dengan Anda. Silakan beri tahu kami bagaimana kami dapat membantu.</p>
        </div>

        <div class="row contact-info">

          <div class="col-md-4">
            <div class="contact-address">
              <i class="bi bi-geo-alt"></i>
              <h3>Address</h3>
              <address> 
                @php
                  $dataFilter = App\Http\Controllers\rjsoft::filteringObj($detailsCompanies, 'Alamat'); 
                  echo $dataFilter;
                  
                @endphp
              </address>
            </div>
          </div>

          <div class="col-md-4">
            <div class="contact-phone">
              <i class="bi bi-phone"></i>
              <h3>Phone Number</h3>
                @php 
                  $dataFilter = App\Http\Controllers\rjsoft::filteringObj($detailsCompanies, 'Phone'); 
                @endphp
              <p><a href="tel:{{ $dataFilter }}">{{ $dataFilter }}</a></p>
            </div>
          </div>

          <div class="col-md-4">
            <div class="contact-email">
              <i class="bi bi-envelope"></i>
              <h3>Email</h3>
              <p><a href="mailto:info@example.com">
                @php 
                  $dataFilter = App\Http\Controllers\rjsoft::filteringObj($detailsCompanies, 'Email'); 
                @endphp
                {{ $dataFilter }}
              </a></p>
            </div>
          </div>

        </div>
      </div>

      <div class="container mb-4">

      @php 
        $dataFilter = App\Http\Controllers\rjsoft::filteringObj($detailsCompanies, 'GMap'); 
      @endphp
      {!! $dataFilter !!}
      
      </div>

      <div class="container">
        
        <div class="form">
          <form action="{{ route('InsertMessageCust') }}" method="post" >
          @csrf  
          <div class="row">
                @if($errors->any())
                <div class="col-12 mb-2">
                    <small><label class="text-xs text-white bg-danger rounded">{{ $errors->first() }}</label></small>
                </div>
                @endif
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
              </div>
              <div class="form-group col-md-6 mt-3 mt-md-0">
                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
              </div>
            </div>
            <div class="form-group mt-3">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
            </div>
            <div class="form-group mt-3">
              <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
            </div>
            <button type="submit" class="btn float-end btn-success text-white mt-2">Send Message</button>
          </form>
        </div>

      </div>
    </section><!-- End Contact Section -->



