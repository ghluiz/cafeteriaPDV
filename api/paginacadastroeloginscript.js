const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});


// const form = document.getElementById('userFormulario');
// const profilePage = document.getElementById('profile-page');

// form.addEventListener('submit', (e) => {
//   e.preventDefault(); 

//   const nome = document.getElementById('nome').value;
//   const email = document.getElementById('email').value;
//   const cpf = document.getElementById('cpf').value;
//   const telefone = document.getElementById('telefone').value;

  
//   document.getElementById('nome-display').textContent = `Nome: ${nome}`;
//   document.getElementById('userEmail').textContent = `email-display: ${email}`;
//   document.getElementById('cpf-display').textContent = `CPF: ${cpf}`;
//   document.getElementById('telefone-display').textContent = `Telefone: ${telefone}`;
// });