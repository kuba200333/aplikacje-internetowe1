create table album (
    id integer not null primary key autoincrement,
    title text not null,
    artist text not null,
    release_year integer not null,
    genre text not null
);
