</main>
</body>
<footer class="bg-dark text-white text-center py-2 mt-5 fixed-bottom">
          <h4 class="mb-0 text-secondary pt-1">Curso PHP & MySQL</h4>
          <p class="text-secondary">&COPY;2025 Senac</p>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<script>
$(document).ready(function () {
  $('#minhaTabela').DataTable({
    "pageLength": 4, // mostra 5 linhas por página
    "lengthChange": false, // remove o seletor de quantidade
    "searching": false,       
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
    }
  });
});
</script>


</html>