function mostrarNotaFiscal(idVenda) {
  fetch(`buscar_nota.php?id=${idVenda}`)
    .then(response => response.text())
    .then(data => {
      document.getElementById('conteudoNotaFiscal').innerHTML = data;
      document.getElementById('notaFiscalModal').style.display = 'block';
    });
}

function fecharModal() {
  document.getElementById('notaFiscalModal').style.display = 'none';
}