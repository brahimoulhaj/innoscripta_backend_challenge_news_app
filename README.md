# Innoscripta Backend Home Take Challenge

### Versions

- PHP 8.3
- Laravel 11

### How to use it?

1. Run `composer install`
2. Copy `.env.example` to `.env` and fill in the variables
3. Run `php artisan key:generate`

### Environment Variables

- `THE_GUARDIAN_API_KEY`
- `NEWS_API_API_KEY`
- `NEW_YORK_TIMES_API_KEY`

### API Docs

- Get, Search, and Filter articles
  - `GET /api/articles?page=2`
  - `GET /api/articles?search=gpt`
  - `GET /api/articles?filters=published_at:2025-02-10,2025-02-25;category.name:technology;source.name:news api;author:John Doe`

- User auth and preferences
  - `POST /api/login` - `{"email":"john@example.com","password":"mypass"}`
  - `POST /api/register` - `{"name":"John Doe","email":"john@example.com","password":"mypass"}`
  - `POST /api/register` - `{"sources":[1,2],"categoryes":[1,2,3],"authors":["John", "Jane"]}` with `Authorization: Bearer MY_TOKEN`
