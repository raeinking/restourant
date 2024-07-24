@extends('components.layout')

@section('main')



<div class="container-fluid my-5">
    <div class="row">
       <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-5 mx-auto">
        <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
          <div class="card-body p-5">
              <img src="/images/logo1.png" class="mb-4" width="145" alt="">
              <h4 class="fw-bold">Get Started Now</h4>
              <p class="mb-0">Enter your credentials to create your account</p>

              <div class="form-body my-4">
                <form id="registerForm" class="row g-3">
                    @csrf
                    <input type="hidden" name="database" value="restaurant">
                    <div class="col-12">
                        <label for="inputUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="inputUsername" name="username" placeholder="JohnDoe" required>
                    </div>
                    <div class="col-12">
                        <label for="inputEmailAddress" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="inputEmailAddress" name="email" placeholder="example@user.com" required>
                    </div>
                    <div class="col-12">
                        <label for="inputChoosePassword" class="form-label">Password</label>
                        <div class="input-group" id="show_hide_password">
                            <input type="password" class="form-control border-end-0" id="inputChoosePassword" minlength="6" name="password" placeholder="Enter Password" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <label for="inputSelectRole" class="form-label">Role</label>
                        <select class="form-select" id="inputSelectRole" name="role" aria-label="Default select example" required>
                            <option value="admin">Admin</option>
                            <option value="cashier">Cashier</option>
                            <option value="kitchen">Kitchen</option>
                            <option value="waiter">Waiter</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-grd-danger">Register</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-grid">
                            <a class="btn btn-danger" href="/">Cancel</a>
                        </div>
                    </div>
                </form>                                                </div>

              {{-- <div class="separator section-padding">
                <div class="line"></div>
                <p class="mb-0 fw-bold">OR</p>
                <div class="line"></div>
              </div> --}}

              {{-- <div class="d-flex gap-3 justify-content-center mt-4">
                <a href="javascript:;" class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-grd-danger">
                  <i class="bi bi-google fs-5 text-white"></i>
                </a>
                <a href="javascript:;" class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-grd-deep-blue">
                  <i class="bi bi-facebook fs-5 text-white"></i>
                </a>
                <a href="javascript:;" class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-grd-info">
                  <i class="bi bi-linkedin fs-5 text-white"></i>
                </a>
                <a href="javascript:;" class="wh-42 d-flex align-items-center justify-content-center rounded-circle bg-grd-royal">
                  <i class="bi bi-github fs-5 text-white"></i>
                </a>
              </div> --}}

          </div>
        </div>
       </div>
    </div><!--end row-->
 </div>

<!--authentication-->




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
  });

  document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);
    const data = {
        database: formData.get('database'),
        username: formData.get('username'),
        email: formData.get('email'),
        password: formData.get('password'),
        role: formData.get('role')
    };

    fetch('{{ env('SERVER_URL') }}/createuser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        alert('Registration successful ✅');
        window.location.href = '/';
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Registration failed ❌: ',data );
    });
});
</script>

@endsection
