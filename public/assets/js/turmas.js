// Contador de Caracter
const textarea = document.querySelector('textarea[name="descricao"]');
const contador = document.getElementById('descricaoContador');

if (textarea && contador) {
    function atualizarContador() {
        contador.textContent = textarea.value.length + ' / 255 caracteres';
    }

    textarea.addEventListener('input', atualizarContador);
    atualizarContador();
}

// Modal exclusão
var confirmDeleteModal = document.getElementById('confirmDeleteModal');
confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var turmaId = button.getAttribute('data-id');
  var turmaNome = button.getAttribute('data-nome');

  var modalBodyNome = confirmDeleteModal.querySelector('#turmaNome');
  var confirmDeleteBtn = confirmDeleteModal.querySelector('#confirmDeleteBtn');

  modalBodyNome.textContent = turmaNome;
  confirmDeleteBtn.href = "delete.php?id=" + turmaId;
});

// Busca automática com delay
let buscaTimeout;
const campoBusca = document.getElementById('campoBusca');
if (campoBusca) {
    campoBusca.addEventListener('input', function() {
        clearTimeout(buscaTimeout);
        const valor = this.value.trim();
        buscaTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            url.searchParams.set('busca', valor);
            url.searchParams.set('pagina', 1);
            url.searchParams.set('ordenar', '<?= $ordenar ?>');
            url.searchParams.set('sentido', '<?= $sentido ?>');
            window.location.href = url.toString();
        }, 500);
    });
}
