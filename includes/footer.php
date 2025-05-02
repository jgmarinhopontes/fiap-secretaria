</div> <!-- fecha .main-content -->

<!-- Modal de confirmação de logout -->
<div class="modal fade" id="confirmLogout" tabindex="-1" aria-labelledby="confirmLogoutLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmação de Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza que deseja sair?
      </div>
      <div class="modal-footer">
      <a href="/fiap-secretaria/public/logout.php" class="btn btn-danger">Sim, sair!</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
      </div>
    </div>
  </div>
</div>


<!-- SCRIPTS JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="/fiap-secretaria/public/assets/js/main.min.js?v=1.0"></script>
<?php if (strpos($_SERVER['PHP_SELF'], '/views/alunos/') !== false): ?>
    <script src="/fiap-secretaria/public/assets/js/alunos.js"></script>
<?php endif; ?>

<?php if (strpos($_SERVER['PHP_SELF'], '/views/turmas/') !== false): ?>
    <script src="/fiap-secretaria/public/assets/js/turmas.js"></script>
<?php endif; ?>

<?php if (strpos($_SERVER['PHP_SELF'], '/views/matriculas/') !== false): ?>
    <script src="/fiap-secretaria/public/assets/js/matriculas.js"></script>
<?php endif; ?>


</body>
</html>
