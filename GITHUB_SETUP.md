# Публикация на GitHub (Kirigyau)

Локальный репозиторий уже создан, первый коммит сделан. Файл `.env` в Git **не попал** (только `.env.example`).

## Шаг 1 — войти в GitHub

В терминале (PowerShell):

```powershell
gh auth login
```

Выберите:
- **GitHub.com**
- **HTTPS**
- **Login with a web browser** — откроется браузер, войдите как **Kirigyau**

## Шаг 2 — создать репозиторий и отправить код

```powershell
cd "d:\OSPanel\domains\moto-shop"
gh repo create moto-shop --public --source=. --remote=origin --push
```

Если репозиторий `moto-shop` на GitHub уже создан вручную:

```powershell
cd "d:\OSPanel\domains\moto-shop"
git push -u origin main
```

(При запросе логина: пользователь **Kirigyau**, пароль — **Personal Access Token**, не пароль от почты.  
Создать токен: GitHub → Settings → Developer settings → Personal access tokens.)

## Проверка

Откройте: https://github.com/Kirigyau/moto-shop

## Данные автора коммитов

Имя: **Kirigyau**  
Почта: **mr.emyanovnik@mail.ru**

Чтобы Git подставлял их в новые коммиты (по желанию):

```powershell
git config --global user.name "Kirigyau"
git config --global user.email "mr.emyanovnik@mail.ru"
```
