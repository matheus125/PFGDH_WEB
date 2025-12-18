<?php if(!class_exists('Rain\Tpl')){exit;}?><footer class="app-footer">
  <!--begin::To the end-->

  <!--end::To the end-->
  <!--begin::Copyright-->
  <strong>
    Copyright &copy; 2024 - <span id="current-year"></span>&nbsp;
    <a href="" class="text-decoration-none">MS-SOLUÇÕES EM TECNOLOGIA</a>.
  </strong>
  All rights reserved.

  <script>
    // Obtém o ano atual
    var currentYear = new Date().getFullYear();

    // Coloca o ano no elemento com o ID 'current-year'
    document.getElementById("current-year").textContent = currentYear;
  </script>
  <!--end::Copyright-->
</footer>
<!--end::Footer-->
</div>
<!--end::App Wrapper-->
<!--begin::Script-->
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
  integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
  integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
  integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="/res/admin/dist/js/adminlte.js"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<script>
  const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
  const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
  };
  document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
      OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
        scrollbars: {
          theme: Default.scrollbarTheme,
          autoHide: Default.scrollbarAutoHide,
          clickScroll: Default.scrollbarClickScroll,
        },
      });
    }
  });
</script>
<!--end::OverlayScrollbars Configure-->
<!-- OPTIONAL SCRIPTS -->
<!-- apexcharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
  integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
<script>
  //-------------
  // - PIE CHART -
  //-------------


  document.addEventListener('DOMContentLoaded', function () {
    const el = document.querySelector('#pie-chart');
    if (!el) return;

    fetch('/api/grafico-titulares')
      .then(response => {
        if (!response.ok) throw new Error('Resposta da API não OK: ' + response.status);
        return response.json();
      })
      .then(data => {
        // labels e valores do retorno
        const labels = Object.keys(data);
        const valores = Object.values(data).map(v => Number(v) || 0);

        // opções do gráfico
        const graficoOptions = {
          series: valores,
          chart: {
            type: 'donut',
            height: 350
          },
          labels: labels,
          legend: { position: 'bottom' },
          colors: ['#0d6efd', '#20c997', '#ffc107'], // ajuste se quiser mais cores
          tooltip: {
            y: {
              formatter: function (val) { return val + ' pessoas'; }
            }
          }
        };

        // renderiza gráfico (se já existir um gráfico, destrói antes)
        if (window._pieChartInstance instanceof ApexCharts) {
          window._pieChartInstance.destroy();
        }
        window._pieChartInstance = new ApexCharts(el, graficoOptions);
        window._pieChartInstance.render();

        // monta o rodapé/legenda com valores e percentuais
        const legend = document.querySelector('#pie-legend');
        legend.innerHTML = ''; // limpa

        const total = valores.reduce((a, b) => a + b, 0) || 1;
        labels.forEach((label, i) => {
          const value = valores[i] || 0;
          const percent = ((value / total) * 100).toFixed(1);

          const li = document.createElement('li');
          li.className = 'nav-item';
          li.innerHTML = `
          <a href="#" class="nav-link d-flex justify-content-between align-items-center">
            <span>${label}</span>
            <span class="float-end">
              <strong>${value}</strong>
              <small class="text-muted"> &nbsp;(${percent}%)</small>
            </span>
          </a>
        `;
          legend.appendChild(li);
        });
      })
      .catch(err => {
        console.error('Erro ao buscar API:', err);
        // opcional: mostrar mensagem amigável no card
        const legend = document.querySelector('#pie-legend');
        if (legend) legend.innerHTML = '<li class="nav-item"><a class="nav-link text-danger">Erro ao carregar dados</a></li>';
      });
  });


  function atualizarUsuarios() {
    fetch("/api/total-usuarios")
      .then(r => r.json())
      .then(d => {
        document.getElementById("totalUsuarios").textContent = d.total;
      });
  }

  atualizarUsuarios();
  setInterval(atualizarUsuarios, 10000); // 10 segundos

  
 function atualizarFamilia() {
    fetch("/api/total-familias")
      .then(r => r.json())
      .then(d => {
        document.getElementById("totalFamilias").textContent = d.total;
      });
  }

  atualizarFamilia();
  setInterval(atualizarFamilia, 10000); // 10 segundos

  let usuarios = [];
  let pagina = 1;
  const itensPorPagina = 8;
  let ordemColuna = "";
  let ordemAsc = true;

  // ---- CARREGAMENTO DOS DADOS ----
  function carregarUsuarios(animacao = false) {
    document.getElementById("loader").style.display = "inline";

    fetch("/api/usuarios-titulares")
      .then(res => res.json())
      .then(data => {
        usuarios = data;
        pagina = 1;
        atualizarTabela(animacao);
        document.getElementById("totalRegistros").innerText = usuarios.length;
      })
      .finally(() => {
        document.getElementById("loader").style.display = "none";
      });
  }

 

  // ---- INICIA ----
  carregarUsuarios();

  function atualizarDependentes() {
    fetch("/api/total-dependentes")
      .then(r => r.json())
      .then(d => {
        document.getElementById("totalDependentes").textContent = d.total;
      })
      .catch(err => console.error("Erro ao carregar total de dependentes:", err));
  }

  atualizarDependentes();
  setInterval(atualizarDependentes, 10000); // a cada 10 segundos

  function atualizarTotalGeral() {
    Promise.all([
      fetch("/api/total-usuarios").then(r => r.json()),
      fetch("/api/total-dependentes").then(r => r.json())
    ])
      .then(([tit, dep]) => {
        const total = (tit.total ?? 0) + (dep.total ?? 0);
        document.getElementById("totalGeral").textContent = total;
      });
  }

  atualizarTotalGeral();
  setInterval(atualizarTotalGeral, 10000);



</script>
<!--end::Script-->
<style>
  .table-modern thead th {
    background: #0d6efd;
    color: white;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
  }

  .table-modern tbody tr:hover {
    background: rgba(13, 110, 253, .09);
    transition: 0.2s;
  }

  .table-modern td,
  .table-modern th {
    vertical-align: middle;
  }

  .nova-linha {
    animation: pulseNew 1.5s ease-out;
  }

  @keyframes pulseNew {
    0% {
      background: #ffe066;
    }

    100% {
      background: transparent;
    }
  }
</style>

</body>
<!--end::Body-->

</html>