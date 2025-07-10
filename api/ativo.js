const links = document.querySelectorAll('.botao-header');
const currentUrl = window.location.href;

links.forEach(link => {
  if (currentUrl.includes(link.getAttribute('href'))) {
    link.classList.add('ativa');
  }
});