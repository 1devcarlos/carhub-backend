@extends('layouts.app')

@section('content')
<section class="vh-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="https://images.unsplash.com/photo-1567808291548-fc3ee04dbcf0?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form>

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <span class="h1 fw-bold mb-0">Logo</span>
                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Entre na sua conta</h5>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="form2Example17">Email</label>
                    <input type="email" id="form2Example17" class="form-control form-control-lg" />
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="form2Example27">Senha</label>
                    <input type="password" id="form2Example27" class="form-control form-control-lg" />
                  </div>

                  <div class="pt-1 mb-4">
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="button">Entrar</button>
                  </div>

                  <a class="small text-muted" href="#!">Forgot password?</a>
                  <p class="mb-5 pb-lg-2" style="color: #BB86FC;">Don't have an account? <a href="#!" style="color: #393f81;">Register here</a></p>
                  <a href="#!" class="small text-muted">Terms of use.</a>
                  <a href="#!" class="small text-muted">Privacy policy</a>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection