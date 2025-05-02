// ===================
// Atualizar hora atual em tempo real
// ===================
function atualizarHora() {
    const agora = new Date();
    const dia = agora.getDate().toString().padStart(2, '0');
    const mes = (agora.getMonth() + 1).toString().padStart(2, '0');
    const ano = agora.getFullYear();
    const horas = agora.getHours().toString().padStart(2, '0');
    const minutos = agora.getMinutes().toString().padStart(2, '0');
    const segundos = agora.getSeconds().toString().padStart(2, '0');
    const horaElem = document.getElementById('hora-atual');
    if (horaElem) {
        horaElem.textContent = `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
    }
}
atualizarHora();
setInterval(atualizarHora, 1000);

// ===================
// Atualizar tempo de sessão ativa
// ===================
const timestampInicio = window.timestampInicio || Math.floor(Date.now() / 1000);
function atualizarTempoSessao() {
    const agora = Math.floor(Date.now() / 1000);
    let segundosAtivos = agora - timestampInicio;
    const minutos = Math.floor(segundosAtivos / 60);
    const segundos = segundosAtivos % 60;
    const tempoElem = document.getElementById('tempo-sessao');
    if (tempoElem) {
        tempoElem.textContent = `${minutos}m ${(segundos < 10 ? '0' : '')}${segundos}s`;
    }
}
atualizarTempoSessao();
setInterval(atualizarTempoSessao, 1000);

// ===================
// Animação dos contadores no dashboard
// ===================
document.querySelectorAll('.contador').forEach(contador => {
    const total = parseInt(contador.getAttribute('data-total'));
    let valorAtual = 0;
    const incremento = Math.ceil(total / 150); // ajusta a velocidade
    const animar = setInterval(() => {
        valorAtual += incremento;
        if (valorAtual >= total) {
            valorAtual = total;
            clearInterval(animar);
        }
        contador.textContent = valorAtual;
    }, 70); // intervalo de atualização
});
