# 🚀 Stokki — Desafio Pleno

Aplicação fullstack desenvolvida como parte do desafio técnico para a vaga de Desenvolvedor Pleno.

## 🛠️ Stack

| Camada | Tecnologia |
|---|---|
| Backend | Laravel + Laravel Sail (Docker) |
| Frontend | Vue.js + Inertia.js + PrimeVue |
| Banco de Dados | MySQL |

---

## ⚙️ Como rodar o projeto

### Pré-requisitos
- Docker e Docker Compose instalados

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd stokki
```

### 2. Suba os containers

```bash
./vendor/bin/sail up -d
```

### 3. Rode as migrations

```bash
./vendor/bin/sail php artisan migrate
```

### 4. Popule o banco com 2 milhões de registros

```bash
./vendor/bin/sail php artisan db:seed
```

### 5. Compile os assets

```bash
# Desenvolvimento (hot reload)
./vendor/bin/sail npm run dev

# Produção
./vendor/bin/sail npm run build
```

### 6. Acesse a aplicação

```
http://localhost
```

---

## 🏗️ Arquitetura

O projeto segue os princípios de **Clean Architecture**, com separação clara de responsabilidades entre as camadas:

```
app/
├── Repositories/       # Interação com dados via Eloquent
│   └── Interfaces/     # Contratos de repositório
├── Services/           # Regras de negócio
│   └── Interfaces/     # Contratos de serviço
└── Http/
    └── Controllers/    # Entrada HTTP (thin controllers)
```

### Padrões utilizados

- **Repository Pattern** — abstrai o acesso ao banco de dados, desacoplando a lógica de negócio do ORM
- **Service Layer** — centraliza as regras de negócio, mantendo controllers e repositories enxutos
- **Dependency Injection + Inversão de Dependência** — as classes dependem de interfaces, não de implementações concretas, facilitando testes e troca de implementações

---

## ⚡ Decisões de Performance

### Seeding de 2 milhões de registros

A inserção em massa utiliza **chunk insert** (inserção em blocos via `DB::insert`), ao invés de `Model::create()` individual.

| Abordagem | INSERTs no banco | Eventos Eloquent | Tempo estimado |
|---|---|---|---|
| `factory()->create()` individual | 2.000.000 | ✅ todos disparados | horas |
| Chunk insert (`DB::insert` em blocos) | ~400 | ❌ bypassa (intencional) | minutos |

### Cache no Dashboard

As queries de dashboard agregam dados sobre 2M+ de registros. Para evitar lentidão a cada acesso, o resultado é **cacheado por 15 minutos** e invalidado automaticamente sempre que um produto é criado, editado ou removido.

### Índices no banco
 
As colunas mais consultadas nas queries de agregação (`name`, `customer_name`, `created_at`) possuem índices dedicados, reduzindo o tempo de `GROUP BY` e `WHERE` em tabelas grandes.
