# SQL

il existe 4 types de requetes SQL :

* INSERT INTO
* SELECT
* UPDATE
* DELETE

on appelle ça un CRUD (Create, Read, Update, Delete)

## SELECT

### Récupérer tous les films

```sql
SELECT *
FROM movie
```

### Récupérer les acteurs et leur(s) rôle(s) pour un film donné

```sql
SELECT *
FROM casting
WHERE movie_id = 15
```

```sql
SELECT *
FROM casting
INNER JOIN person ON casting.person_id = person.id
WHERE movie_id = 15
```

```sql
SELECT casting.role, person.firstname, person.lastname
FROM casting
INNER JOIN person ON casting.person_id = person.id
WHERE movie_id = 15
```

### Récupérer les genres associés à un film donné

```sql
SELECT *
FROM movie_genre
WHERE movie_id = 12
```

```sql
SELECT *
FROM movie_genre
INNER JOIN genre ON movie_genre.genre_id = genre.id
WHERE movie_id = 12
```

```sql
SELECT genre.name
FROM movie_genre
INNER JOIN genre ON movie_genre.genre_id = genre.id
WHERE movie_id = 12
```

### Récupérer les saisons associées à un film/série donné

```sql
SELECT *
FROM season
WHERE movie_id = 5
```

## tout les reviews d'un utilisateur

```sql
SELECT *
FROM review
WHERE user_id = 2
```

## tout les noms de films qu'un utilisateur (2) a commenté

```sql
SELECT movie.title
FROM movie
WHERE movie.id IN (3,6,16,18)
```

```sql
SELECT movie_id
FROM review
WHERE user_id = 2
```

```sql
SELECT movie.title
FROM movie
WHERE movie.id IN (SELECT movie_id
FROM review
WHERE user_id = 2)
```

```sql
SELECT movie.title
FROM movie, review
WHERE 
movie.id = review.movie_id
AND review.user_id = 2
```

```sql
SELECT movie.title
FROM movie
INNER JOIN review ON movie.id = review.movie_id
WHERE review.user_id = 2
```

## INSERT INTO

```sql
INSERT INTO `table`
(column_1, column_2)
VALUES
('value_1', value_2)
```

## UPDATE

```sql
UPDATE `table`
set column = value
WHERE condition
```

## DELETE

```sql
DELETE FROM `table`
WHERE condition
```
