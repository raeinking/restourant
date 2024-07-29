@extends('./components/layout')

@section('main')
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
      <div class="container-fluid my-5 my-lg-0">
        <div class="row">
           <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
            <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
              <div class="card-body p-5">
                  <img src="/images/logo1.png" class="mb-4" width="145" alt="">
                  <h4 class="fw-bold">Get Started Now</h4>
                  <p class="mb-0">Enter your credentials to login your account</p>

                  <div class="form-body my-5">
                      <form id="loginForm" class="row g-3">
                          @csrf
                          <input type="hidden" name="database" value="restaurant">
                          <div class="col-12">
                              <label for="inputEmailAddress" class="form-label">Email</label>
                              <input type="email" class="form-control" name="email" id="inputEmailAddress" placeholder="jhon@example.com">
                          </div>
                          <div class="col-12">
                              <label for="inputChoosePassword" class="form-label">Password</label>
                              <div class="input-group" id="show_hide_password">
                                  <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Enter Password">
                                  <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
                              </div>
                          </div>

                          <div class="col-12">
                              <div class="d-grid">
                                  <button type="submit" class="btn btn-grd-primary">Login</button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
            </div>
           </div>
        </div><!--end row-->
     </div>
    </div>
    <!--authentication-->

    <!-- Loading indicator -->
    <div id="loading" class="loader-wrapper" style="display:none;">
        <div class="loader"></div>
    </div>

    <!--plugins-->
    <script src="/js/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bi-eye-slash-fill");
                    $('#show_hide_password i').removeClass("bi-eye-fill");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bi-eye-slash-fill");
                    $('#show_hide_password i').addClass("bi-eye-fill");
                }
            });

            document.getElementById('loginForm').addEventListener('submit', function (event) {
                event.preventDefault();
                document.getElementById('loading').style.display = 'flex'; // Show loading indicator

                const form = event.target;
                const formData = new FormData(form);
                const data = {
                    email: formData.get('email'),
                    password: formData.get('password'),
                    database: formData.get('database')
                };

                fetch('{{ env('SERVER_URL') }}/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Uncomment if CSRF protection is needed
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').style.display = 'none'; // Hide loading indicator
                    if (data.message === 'Login successful ✅') {
                        console.log('Success:', data);
                        alert('Login successful ✅:' + data.token );
                        localStorage.setItem('token', data.token); // Store the token
                        window.location.href = '/';
                    } else {
                        console.log('Error:', data);
                        // alert('Login failed ❌: ' + data.message);
                    }
                })
                .catch((error) => {
                    document.getElementById('loading').style.display = 'none'; // Hide loading indicator
                    console.error('Error:', error);
                    // alert('Login failed ❌ error: ' + error.message);
                });
            });
        });
    </script>
@endsection
