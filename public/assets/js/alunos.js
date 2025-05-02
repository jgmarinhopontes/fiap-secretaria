// Máscara CPF
const cpfInput = document.getElementById('cpf');
if (cpfInput) {
    cpfInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });
}

// Máscara Telefone
const telInput = document.getElementById('telefone');
if (telInput) {
    telInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
        value = value.replace(/(\d{5})(\d{4})$/, '$1-$2');
        e.target.value = value;
    });
}
// Modal exclusão
var confirmDeleteModal = document.getElementById('confirmDeleteModal');
confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var alunoId = button.getAttribute('data-id');
  var alunoNome = button.getAttribute('data-nome');

  var modalBodyNome = confirmDeleteModal.querySelector('#alunoNome');
  var confirmDeleteBtn = confirmDeleteModal.querySelector('#confirmDeleteBtn');

  modalBodyNome.textContent = alunoNome;
  confirmDeleteBtn.href = "delete.php?id=" + alunoId;
});

// Busca automática com delay
let buscaTimeout;
document.getElementById('campoBusca').addEventListener('input', function() {
    clearTimeout(buscaTimeout);
    const valor = this.value.trim();
    buscaTimeout = setTimeout(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('busca', valor);
        url.searchParams.set('pagina', 1); // resetar página
        url.searchParams.set('ordenar', '<?= $ordenar ?>');
        url.searchParams.set('sentido', '<?= $sentido ?>');
        window.location.href = url.toString();
    }, 500);
});