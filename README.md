# 🔐 Password Generator API

> API REST para geração e histórico de senhas — construída com Laravel 13 e MySQL.

## 📋 Sobre o projeto

O **Password Generator API** é uma API de geração de senhas personalizadas. O usuário define o tamanho e a complexidade — maiúsculas, minúsculas, números e símbolos — e a API gera uma senha segura e salva no histórico.

O projeto foi desenvolvido como portfólio para demonstrar conhecimentos em arquitetura de APIs REST, lógica de geração de dados, validação e testes automatizados com Laravel.

## ✨ Funcionalidades

| Ação | Descrição |
|---|---|
| **Gerar senha** | Gera senha com base no tamanho e complexidade definidos |
| **Listar histórico** | Retorna todas as senhas geradas com suas configurações |
| **Apagar senha** | Remove uma senha do histórico |

## 🛠️ Tecnologias utilizadas

- **PHP 8.5** + **Laravel 13**
- **MySQL** — banco de dados relacional
- **Eloquent ORM** — mapeamento objeto-relacional
- **Form Request** — validação dos parâmetros de entrada
- **PHPUnit** — testes automatizados
- **Postman** — testes de endpoints durante desenvolvimento

## 🏗️ Arquitetura

```
app/
├── Http/
│   ├── Controllers/
│   │   └── PasswordController.php        # Geração, listagem e remoção
│   └── Requests/
│       └── GeneratePasswordRequest.php   # Validação dos parâmetros
└── Models/
    └── GeneratedPassword.php             # Model com casts e fillable

database/
└── migrations/
    └── create_passwords_table.php        # Estrutura da tabela

tests/
└── Feature/
    └── PasswordTest.php                  # Testes automatizados
```

**Fluxo de geração:**

```
POST /api/passwords → Validação → Monta pool de caracteres → Embaralha → substr(length) → Salva → Retorna JSON
```

## 🧠 Decisões técnicas

### Por que usar do...while com str_repeat?
Quando o usuário seleciona poucos tipos de caracteres (ex: só símbolos), o pool tem apenas 8 caracteres. Se o `length` solicitado for maior que o pool, o `substr` retornaria menos caracteres do que o esperado. O `do...while` com `str_repeat` garante que o pool sempre terá caracteres suficientes antes de embaralhar.

### Por que $casts no Model?
Os valores booleanos salvos no MySQL chegam como `0` e `1`. O `$casts` converte automaticamente para `true`/`false` no PHP, garantindo que a resposta JSON retorne tipos corretos.

### Por que Form Request com min:6?
Senhas com menos de 6 caracteres não são seguras. A validação no `GeneratePasswordRequest` garante que o usuário não consiga gerar senhas triviais pela API.

### Por que RefreshDatabase nos testes?
O `RefreshDatabase` reseta o banco antes de cada teste, garantindo que os testes sejam independentes entre si e não interfiram nos dados uns dos outros.

## 🚀 Como rodar localmente

### Pré-requisitos

- PHP 8.3+
- Composer
- MySQL

### Instalação

```bash
# 1. Clone o repositório
git clone https://github.com/felipekauan1/password-generator-api.git
cd password-generator-api

# 2. Instale as dependências
composer install

# 3. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 4. Configure o banco de dados no .env
DB_DATABASE=password_generator_api
DB_USERNAME=root
DB_PASSWORD=sua_senha

# 5. Crie o banco e rode as migrations
php artisan migrate
```

### Rodando o projeto

```bash
php artisan serve
```

### Rodando os testes

```bash
php artisan test
```

**Resultado esperado:**
```
PASS  Tests\Feature\PasswordTest
✓ can generate password
✓ cannot generate password without length
✓ can list passwords

Tests: 4 passed
```

## 📡 Endpoints da API

### Gerar senha
```
POST /api/passwords
Content-Type: application/json

{
    "length": 12,
    "uppercase": true,
    "lowercase": true,
    "numbers": true,
    "symbols": false
}
```

**Parâmetros:**

| Campo | Tipo | Obrigatório | Descrição |
|---|---|---|---|
| `length` | integer | ✅ | Tamanho da senha (min: 6, max: 50) |
| `uppercase` | boolean | ❌ | Incluir letras maiúsculas |
| `lowercase` | boolean | ❌ | Incluir letras minúsculas |
| `numbers` | boolean | ❌ | Incluir números |
| `symbols` | boolean | ❌ | Incluir símbolos (!@#$%^&*) |

**Resposta (201):**
```json
{
    "message": "Senha criada com sucesso!",
    "password": {
        "id": 1,
        "password": "aB3#xK9!mZ2@",
        "length": 12,
        "uppercase": true,
        "lowercase": true,
        "numbers": true,
        "symbols": false,
        "created_at": "2026-05-22T..."
    }
}
```

### Listar histórico
```
GET /api/passwords
```

**Resposta (200):**
```json
{
    "passwords": [
        {
            "id": 1,
            "password": "aB3#xK9!mZ2@",
            "length": 12,
            "uppercase": true,
            "lowercase": true,
            "numbers": true,
            "symbols": false,
            "created_at": "2026-05-22T..."
        }
    ]
}
```

### Apagar senha do histórico
```
DELETE /api/passwords/{id}
```

**Resposta (200):**
```json
{
    "message": "Senha deletada com sucesso!"
}
```

## 📌 Possíveis melhorias futuras

- Autenticação com Laravel Sanctum para histórico por usuário
- Avaliação de força da senha gerada (fraca, média, forte)
- Opção de gerar múltiplas senhas em uma única requisição
- Mais testes automatizados (destroy, validações de borda)

## 👨‍💻 Autor

Desenvolvido por **[@felipekauan1](https://github.com/felipekauan1)**

## 📄 Licença

Este projeto está sob a licença MIT.
