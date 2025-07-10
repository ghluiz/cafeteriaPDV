function previewImagem(event) {
  const input = event.target;
  const imagem = document.getElementById('previewImagem');

  if (input.files && input.files[0]) {
    const leitor = new FileReader();
    leitor.onload = function(e) {
      imagem.src = e.target.result;
    };
    leitor.readAsDataURL(input.files[0]);

    // envia automaticamente
    document.getElementById('formImagem').submit();
  }
}