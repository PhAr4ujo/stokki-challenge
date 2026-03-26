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

### B-Tree / Binary Search e O(log n)

Os índices do MySQL são implementados internamente como **B-Trees (Balanced Trees)**.
Cada inserção ou deleção de registro mantém a árvore automaticamente balanceada via
**node split**: quando um nó atinge sua capacidade máxima, a chave do meio é promovida
ao nó pai e o nó é dividido em dois filhos — garantindo que todos os nós folha
permaneçam sempre na mesma profundidade.

Essa propriedade assegura complexidade **O(log n)** para qualquer busca:
```
Tabela com 2.000.000 de registros:

Full Table Scan  →  2.000.000 comparações  →  O(n)
B-Tree lookup    →  log₂(2.000.000) ≈ 21 comparações  →  O(log n)
```

#### Covering Index — eliminando o acesso à tabela

Os índices compostos criados neste projeto são **covering indexes**: todas as colunas
necessárias para responder às queries de agregação (`name`, `total`, `amount`,
`customer_name`, `created_at`) estão presentes dentro do próprio índice.
```sql
SELECT name, SUM(total), SUM(amount)
FROM products
GROUP BY name
ORDER BY SUM(total) DESC
LIMIT 5;
```

O MySQL percorre as entradas do índice sequencialmente — já ordenadas por `name` —
somando `total` e `amount` à medida que avança, **sem nenhum acesso à tabela principal**.
Isso elimina milhões de I/Os aleatórios em disco, que são mais lentos que
leitura sequencial.

#### Campo `total` pré-calculado

O model dispara `price * amount` no evento `saving()` e persiste o resultado em `total`.
As queries de agregação usam `SUM(total)` em vez de `SUM(price * amount)`, eliminando
2.000.000 multiplicações em runtime por query.

