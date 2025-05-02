// Exclusão de matricula
var confirmDeleteModal = document.getElementById('confirmDeleteModal');
confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var matriculaId = button.getAttribute('data-id');
  var alunoNome = button.getAttribute('data-nome');

  var modalAlunoNome = confirmDeleteModal.querySelector('#matriculaAlunoNome');
  var confirmDeleteBtn = confirmDeleteModal.querySelector('#confirmDeleteBtn');

  modalAlunoNome.textContent = alunoNome;
  confirmDeleteBtn.href = "delete.php?id=" + matriculaId;
});