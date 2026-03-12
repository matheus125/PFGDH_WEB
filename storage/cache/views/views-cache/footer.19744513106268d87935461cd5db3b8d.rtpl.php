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
/* =========================================================
   UTILIDADES
========================================================= */

/**
 * Atualiza texto de um elemento apenas se ele existir
 */
function setText(id, valor = 0) {
  const el = document.getElementById(id);
  if (el) el.textContent = valor;
}

/**
 * Fetch seguro com tratamento de erro
 */
function fetchJson(url) {
  return fetch(url).then(res => {
    if (!res.ok) {
      throw new Error(`Erro ${res.status} em ${url}`);
    }
    return res.json();
  });
}

/* =========================================================
   GRÁFICO DE PIZZA (APEXCHARTS)
========================================================= */

document.addEventListener('DOMContentLoaded', () => {
  const el = document.querySelector('#pie-chart');
  if (!el || typeof ApexCharts === 'undefined') return;

  fetchJson('/api/grafico-titulares')
    .then(data => {
      const labels = Object.keys(data || {});
      const valores = Object.values(data || {}).map(v => Number(v) || 0);

      const options = {
        series: valores,
        chart: { type: 'donut', height: 350 },
        labels,
        legend: { position: 'bottom' },
        colors: ['#0d6efd', '#20c997', '#ffc107'],
        tooltip: {
          y: { formatter: val => `${val} pessoas` }
        }
      };

      if (window._pieChartInstance instanceof ApexCharts) {
        window._pieChartInstance.destroy();
      }

      window._pieChartInstance = new ApexCharts(el, options);
      window._pieChartInstance.render();

      /* Legenda customizada */
      const legend = document.getElementById('pie-legend');
      if (!legend) return;

      legend.innerHTML = '';
      const total = valores.reduce((a, b) => a + b, 0) || 1;

      labels.forEach((label, i) => {
        const percent = ((valores[i] / total) * 100).toFixed(1);
        legend.insertAdjacentHTML('beforeend', `
          <li class="nav-item">
            <a class="nav-link d-flex justify-content-between">
              <span>${label}</span>
              <span><strong>${valores[i]}</strong> <small>(${percent}%)</small></span>
            </a>
          </li>
        `);
      });
    })
    .catch(err => {
      console.error('Erro no gráfico:', err);
      const legend = document.getElementById('pie-legend');
      if (legend) legend.innerHTML = '<li class="text-danger">Erro ao carregar gráfico</li>';
    });
});

/* =========================================================
   CONTADORES
========================================================= */

function atualizarUsuarios() {
  fetchJson('/api/total-titulares')
    .then(d => setText('totalUsuarios', d.total))
    .catch(err => console.error('Usuários:', err));
}

function atualizarFamilia() {
  fetchJson('/api/total-familias')
    .then(d => setText('totalFamilias', d.total))
    .catch(err => console.error('Famílias:', err));
}

function atualizarDependentes() {
  fetchJson('/api/total-dependentes')
    .then(d => setText('totalDependentes', d.total))
    .catch(err => console.error('Dependentes:', err));
}

function atualizarTotalGeral() {
  Promise.all([
    fetchJson('/api/total-titulares'),
    fetchJson('/api/total-dependentes')
  ])
    .then(([tit, dep]) => {
      setText('totalGeral', (tit.total || 0) + (dep.total || 0));
    })
    .catch(err => console.error('Total geral:', err));
}

/* Inicialização */
atualizarUsuarios();
atualizarFamilia();
atualizarDependentes();
atualizarTotalGeral();

/* Intervalos */
setInterval(atualizarUsuarios, 10000);
setInterval(atualizarFamilia, 10000);
setInterval(atualizarDependentes, 10000);
setInterval(atualizarTotalGeral, 10000);

/* =========================================================
   LISTA DE USUÁRIOS (SEM QUEBRAR SE NÃO EXISTIR TABELA)
========================================================= */

let usuarios = [];

function carregarUsuarios() {
  const loader = document.getElementById('loader');
  if (loader) loader.style.display = 'inline';

  fetchJson('/api/usuarios-titulares')
    .then(data => {
      if (!Array.isArray(data)) {
        console.error('usuarios-titulares não retornou array:', data);
        return;
      }

      usuarios = data;
      setText('totalRegistros', usuarios.length);

      /* Só chama se a função existir */
      if (typeof atualizarTabela === 'function') {
        atualizarTabela();
      }
    })
    .catch(err => console.error('Erro usuários:', err))
    .finally(() => {
      if (loader) loader.style.display = 'none';
    });
}

carregarUsuarios();

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