<?php if(!class_exists('Rain\Tpl')){exit;}?><!--begin::App Main-->
<main class="app-main">
  <!--begin::App Content Header-->
  <div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
      <!--begin::Row-->
      <div class="row">
        <div class="col-sm-6">
          <h3 class="mb-0">Dashboard </h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-end">
            <li class="breadcrumb-item"><a href="/admin/index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard </li>
          </ol>
        </div>
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon text-bg-primary shadow-sm">
              <i class="bi bi-cart-fill"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">TITULARES</span>
              
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon text-bg-success shadow-sm">

              <i class="bi bi-people-fill"></i>
            </span>

            <!-- plate-->
            <div class="info-box-content">
              <span class="info-box-text">DEPENDENTES</span>
              <span class="info-box-number">400</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <!-- <div class="clearfix hidden-md-up"></div> -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon text-bg-danger shadow-sm">
              <i class="bi bi-trash3"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">FAMILIAS</span>
              <span class="info-box-number">760</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon text-bg-warning shadow-sm">
              <i class="bi bi-people-fill"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">TOTAL CADASTRADOS</span>
              <span class="info-box-number">2,000</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!--begin::Row-->
      <div class="row">
        <div class="col-md-12">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title">Relatório de Recapitulação Mensal</h5>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                  <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                  <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                </button>

                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!--begin::Row-->
              <div class="row">
                <div class="col-md-100">
                  <p class="text-center">
                    <strong>Vendas: 3º de março de 2025 - 30 de julho de 2025</strong>
                  </p>
                  <div id="sales-chart"></div>
                </div>
                <!-- /.col -->

                <!-- /.col -->
              </div>
              <!--end::Row-->
            </div>
            <!-- ./card-body -->
            <div class="card-footer">
              <!--begin::Row-->
              <div class="row">
                <div class="col-md-3 col-6">
                  <div class="text-center border-end">
                    <span class="text-bg-success">

                    </span>
                    <h5 class="fw-bold mb-0">5.000</h5>
                    <span class="text-uppercase">REFEIÇÕES VENDIDAS</span>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-6">
                  <div class="text-center border-end">
                    <span class="text-bg-success"> </span>
                    <h5 class="fw-bold mb-0">5.000</h5>
                    <span class="text-uppercase">REFEIÇÕES SERVIDAS</span>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-6">
                  <div class="text-center border-end">
                    <span class="text-bg-success">

                    </span>
                    <h5 class="fw-bold mb-0">5.000</h5>
                    <span class="text-uppercase">SOBRA DE REFEIÇÕES</span>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-6">
                  <div class="text-center">
                    <span class="text-bg-success">

                    </span>
                    <h5 class="fw-bold mb-0">10.000</h5>
                    <span class="text-uppercase">USUÁRIOS
                    </span>
                  </div>
                </div>
              </div>
              <!--end::Row-->
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!--end::Row-->
      <!--begin::Row-->
      <div class="row">
        <!-- Start col -->
        <div class="col-md-8">

          <!--end::Row-->
          <!--begin::Latest Order Widget-->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">USUÁRIOS</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                  <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                  <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                </button>
                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table m-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>NOME</th>
                      <th>RG</th>
                      <th>CPF</th>
                      <th>NIS</th>
                      <th>IDADE</th>
                      <th>SEXO</th>
                      <th>STATUS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $counter1=-1;  if( isset($index) && ( is_array($index) || $index instanceof Traversable ) && sizeof($index) ) foreach( $index as $key1 => $value1 ){ $counter1++; ?>
                    <tr>
                      <td><?php echo htmlspecialchars( $value1["id_pessoa"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                      <td><?php echo htmlspecialchars( $value1["nome_funcionario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                      <td><?php echo htmlspecialchars( $value1["email"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                      <td><?php echo htmlspecialchars( $value1["cpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>

                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-primary float-start">
                NOVO CADASTRO
              </a>
              <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-end">
                Ver todos os clientes
              </a>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">

          <!-- /.info-box -->
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">FAIXA ETÁRIA</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                  <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                  <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                </button>
                <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                  <i class="bi bi-x-lg"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!--begin::Row-->
              <div class="row">
                <div class="col-12">
                  <div id="pie-chart"></div>
                </div>
                <!-- /.col -->
              </div>
              <!--end::Row-->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    3 ATÉ 17 ANOS MASCULINO
                    <span class="float-end text-danger">
                      <i class="bi bi-arrow-down fs-7"></i>
                      12%
                    </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    3 ATÉ 17 ANOS MASCULINO(PCD)
                    <span class="float-end text-success">
                      <i class="bi bi-arrow-up fs-7"></i> 4%
                    </span>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    3 ATÉ 17 ANOS FEMININO
                    <span class="float-end text-info">
                      <i class="bi bi-arrow-left fs-7"></i> 0%
                    </span>
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.footer -->
          </div>

        </div>
        <!-- /.col -->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::App Content-->
</main>