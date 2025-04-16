
  <style>
    .card-stats {
      border: none;
      border-radius: 15px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.05);
      transition: 0.3s ease-in-out;
    }
    .card-stats:hover {
      transform: translateY(-3px);
    }
    .icon-circle {
      width: 50px;
      height: 50px;
      background-color: rgba(0, 123, 255, 0.1);
      color: #007bff;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-size: 1.4rem;
    }
  </style>


  <!-- Dashboard Cards Section -->
  <div class="container py-2">
    <h3 class="mb-4 text-center">Dashboard Overview</h3>
    <div class="row g-4">

      <!-- Requests Card -->
      <div class="col-sm-6 col-lg-3">
        <div class="card card-stats p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="icon-circle"><i class="bi bi-send-arrow-down-fill"></i></div>
            </div>
            <div class="text-end">
              <h6 class="text-muted mb-1">Requests</h6>
              <h4 class="fw-bold">120</h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Users Card -->
      <div class="col-sm-6 col-lg-3">
        <div class="card card-stats p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="icon-circle bg-success bg-opacity-10 text-success"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="text-end">
              <h6 class="text-muted mb-1">Users</h6>
              <h4 class="fw-bold">540</h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Messages Card -->
      <div class="col-sm-6 col-lg-3">
        <div class="card card-stats p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="icon-circle bg-warning bg-opacity-10 text-warning"><i class="bi bi-envelope-fill"></i></div>
            </div>
            <div class="text-end">
              <h6 class="text-muted mb-1">Messages</h6>
              <h4 class="fw-bold">89</h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Sales Card -->
      <div class="col-sm-6 col-lg-3">
        <div class="card card-stats p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="icon-circle bg-danger bg-opacity-10 text-danger"><i class="bi bi-currency-dollar"></i></div>
            </div>
            <div class="text-end">
              <h6 class="text-muted mb-1">Sales</h6>
              <h4 class="fw-bold">$4,300</h4>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

