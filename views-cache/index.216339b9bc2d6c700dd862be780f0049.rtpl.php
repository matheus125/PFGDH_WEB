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

      <div class="row justify-content-center gy-3">
        <!--end::Col-->
        <div class="col-lg-3 col-6">
          <!--begin::Small Box Widget 2-->
          <div class="small-box text-bg-success">
            <div class="inner">
              <h3 id="totalUsuarios">...</h3>
              <p>Titulares Cadastrados</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
              </path>
            </svg>

          </div>
          <!--end::Small Box Widget 2-->
        </div>
        <!--end::Col-->
        <div class="col-lg-3 col-6">
          <!--begin::Small Box Widget 3-->
          <div class="small-box text-bg-warning">
            <div class="inner">
              <h3 id="totalDependentes">...</h3>
              <p>Dependentes Cadastrados</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
              <path
                d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z">
              </path>
            </svg>

          </div>

          <!--end::Small Box Widget 3-->
        </div>
        <!--end::Col-->
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-danger">
            <div class="inner">
              <h3 id="totalGeral">...</h3>
              <p>Total Geral (Titulares + Dependentes)</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
              <path clip-rule="evenodd" fill-rule="evenodd"
                d="M12 2.25a9.75 9.75 0 110 19.5 9.75 9.75 0 010-19.5zM11.25 7.5a.75.75 0 011.5 0V12a.75.75 0 01-.75.75H9a.75.75 0 010-1.5h2.25V7.5z">
              </path>
            </svg>
          </div>
        </div>

        <!--end::Col-->
      </div>
      <!-- Info boxes -->
      <div class="row">


        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!--begin::Row-->
      <div class="row">

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
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm" id="btnExcel">📊 Exportar Excel</button>
                    <button class="btn btn-danger btn-sm" id="btnPDF">📄 Exportar PDF</button>
                  </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                  <input id="pesquisaInput" type="text" class="form-control w-50"
                    placeholder="🔍 Pesquisar por nome, CPF ou RG">
                  <span id="loader" class="ms-3" style="display:none;">⏳ Carregando...</span>
                </div>

                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-modern">
                    <thead id="usuariosHeader">
                      <tr>
                        <th data-col="nome">Nome ▲▼</th>
                        <th data-col="telefone">Telefone ▲▼</th>
                        <th data-col="email">Email ▲▼</th>
                        <th data-col="rg">RG ▲▼</th>
                        <th data-col="cpf">CPF ▲▼</th>
                        <th data-col="data_nascimento">Nascimento ▲▼</th>
                        <th data-col="estado_civil">Estado Civil ▲▼</th>
                        <th data-col="grau_escolaridade">Escolaridade ▲▼</th>

                      </tr>
                    </thead>
                    <tbody id="usuariosTabela"></tbody>
                  </table>
                </div>

                <div class="mt-2 text-end">
                  <button id="btnAnterior" class="btn btn-outline-secondary btn-sm">Anterior</button>
                  <span id="paginaAtual" class="mx-2"></span>
                  <button id="btnProximo" class="btn btn-outline-secondary btn-sm">Próximo</button>
                </div>



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
                  <!-- gráfico: apenas um elemento com este id -->
                  <div id="pie-chart" style="width:100%; height:350px;"></div>
                </div>
                <!-- /.col -->
              </div>
              <!--end::Row-->
            </div>
            <!-- /.card-body -->

            <!-- footer preenchido dinamicamente -->
            <div class="card-footer p-0">
              <ul id="pie-legend" class="nav nav-pills flex-column">
                <!-- itens serão inseridos via JS -->
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