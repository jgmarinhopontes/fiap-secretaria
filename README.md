👏 **Sensacional! Mandou bem demais no projeto — aqui está o seu `README.md` atualizado com os pontos finais que você pediu, incluindo o link de acesso online e as infos sobre ambiente de desenvolvimento!**
---

## ✅ Também disponível online:

👉 [https://fiap.ravenstudio.com.br/fiap-secretaria/public/login](https://fiap.ravenstudio.com.br/fiap-secretaria/public/login)

---
---

```md
# 📚 Sistema Secretaria FIAP

Sistema desenvolvido em **PHP puro** e **MySQL** como parte do desafio da FIAP. Permite o gerenciamento de **Alunos**, **Turmas** e **Matrículas**, com controle de acesso por login de administrador.

---

## 🚀 Funcionalidades principais

✅ **Login do administrador** com autenticação segura  
✅ **CRUD completo de Alunos** (Cadastrar, Listar, Editar, Excluir)  
✅ **CRUD completo de Turmas** (Cadastrar, Listar com paginação, Editar, Excluir)  
✅ **Matrículas** (evita matrícula duplicada na mesma turma)  
✅ **Visualização de alunos matriculados por Turma**  
✅ **Exportação da lista de alunos em CSV**  
✅ **Ordenação dinâmica por Nome, Idade ou Data de Cadastro**  
✅ **CSS e JS minificados para maior performance**

---

## 📝 Regras de Negócio Implementadas

- ✔️ Validação de campos obrigatórios
- ✔️ Nome com mínimo de 3 caracteres e obrigatório ter nome + sobrenome
- ✔️ CPF validado e único por aluno
- ✔️ E-mail único por aluno
- ✔️ Não permite matrícula duplicada do mesmo aluno na mesma turma
- ✔️ Contagem automática de alunos por turma
- ✔️ Senha forte (mínimo 8 caracteres, com maiúscula, minúscula, número e símbolo)
- ✔️ Senha criptografada com `password_hash`
- ✔️ Paginação em Turmas (10 por página)
- ✔️ Busca por nome em Alunos e Turmas
- ✔️ Confirmação com modal ao excluir registros

---

## 🎨 Diferenciais

✨ **Dashboard com contadores animados**  
✨ **Modal de inatividade com logout automático (após 15 segundos)**  
✨ **Menu lateral responsivo com efeito deslizante no mobile**  
✨ **Validação dinâmica de descrição de turma (contador de caracteres)**  
✨ **Interface moderna com Bootstrap 5 e customização CSS**  
✨ **Listagens com DataTables (responsivo, pesquisa e paginação)**  
✨ **Mensagens dinâmicas de sucesso/erro no topo da tela**

---

## 📂 Estrutura de pastas

/fiap-secretaria
  /public
    index.php
    login.php
    logout.php
    primeiro_acesso.php
    assets/
  /views
    /alunos
      list.php
      create.php
      edit.php
      delete.php
      export.php
    /turmas
      list.php
      create.php
      edit.php
      delete.php
      view_alunos.php
    /matriculas
      list.php
      create.php
      delete.php
  /includes
    auth.php
    db.php
    header.php
    footer.php
  /assets
    /css
      style.min.css
    /js
      main.min.js
      alunos.js
      matriculas.js
      turmas.js
  dump.sql
  README.md
```

---

## 🛠️ Tecnologias Utilizadas

- PHP 7.4+ (desenvolvido/testado no XAMPP para Windows)
- MySQL
- Bootstrap 5
- DataTables
- JavaScript puro
- jQuery (para modais Bootstrap)

---

## 🏃‍♂️ Como executar o projeto localmente

1️⃣ **Clone o repositório:**

```bash
git clone https://github.com/jgmarinhopontes/fiap-secretaria.git
```

2️⃣ **Importe o banco de dados:**

No phpMyAdmin ou via CLI, importe o arquivo `dump.sql`.

3️⃣ **Configure o banco de dados:**

Edite `/includes/db.php` com os dados da sua conexão local:

```php
<?php
// Configurações de conexão
$host = 'localhost';
$dbname = 'fiap_secretaria';
$username = 'root';
$password = ''; // sem senha no XAMPP por padrão
```

4️⃣ **Acesse no navegador:**

```http
http://localhost/fiap-secretaria/public/login.php
```

✅ **Primeiro acesso:**

O sistema direciona automaticamente para **`primeiro_acesso.php`** se ainda não existir administrador cadastrado.

---

## ✅ Também disponível online:

👉 [https://fiap.ravenstudio.com.br/fiap-secretaria/public/login](https://fiap.ravenstudio.com.br/fiap-secretaria/public/login)

---

## 👤 Usuário padrão (opcional)

Caso já tenha criado um admin manualmente:

- **Email:** admin@fiap.com.br
- **Senha:** (a senha criada)

✅ Senhas são criptografadas.

---

## 💬 Observações

- Desenvolvido em **PHP puro** (sem frameworks PHP)
- Conexão segura com **PDO + prepared statements**
- Interface moderna, responsiva, com **Bootstrap 5**
- Testado e produzido usando **XAMPP no Windows**
- Funcionando em ambiente **Linux (Hostinger)** com PHP 8.1+

---

## 📧 Contato

Desenvolvido por **Guilherme Pontes**  
✉️ [jgmarinhopontes@hotmail.com] | [https://www.linkedin.com/in/guilherme-marinho-pontes/](https://www.linkedin.com/in/guilherme-marinho-pontes/)

---

🎉 **Obrigado por conferir este projeto!**
