create table users (
    user_id         serial          not null,
    username        varchar(255)    not null,
    password        varchar(32)     not null,
    user_type       varchar(20)     not null,
    ts_created      datetime        not null,
    ts_last_login   datetime,

    primary key (user_id),
    unique (username)
) type = InnoDB;

create table users_profile (
    user_id         bigint unsigned not null,
    profile_key     varchar(255)    not null,
    profile_value   text            not null,

    primary key (user_id, profile_key),
    foreign key (user_id) references users (user_id)
) type = InnoDB;

create table blog_posts (
    post_id         serial          not null,
    user_id         bigint unsigned not null,

    url             varchar(255)    not null,
    ts_created      datetime        not null,
    status          varchar(10)     not null,

    primary key (post_id),
    foreign key (user_id) references users (user_id)
) type = InnoDB;

create index blog_posts_url on blog_posts (url);

create table blog_posts_profile (
    post_id         bigint unsigned not null,
    profile_key     varchar(255)    not null,
    profile_value   text            not null,

    primary key (post_id, profile_key),
    foreign key (post_id) references blog_posts (post_id)
) type = InnoDB;

create table relations (
    from_user       bigint unsigned     not null,
    to_user         bigint unsigned     not null,
    status          char not null,
    ts_requested    datetime            not null,
    ts_response     datetime,

    primary key (from_user, to_user),
    foreign key (from_user) references users (user_id),
    foreign key (to_user) references users (user_id)
) type = InnoDB;

create table comment_posts (
    post_id         serial          not null auto_increment,
    user_id         bigint unsigned not null,
    ts_created      datetime        not null,
    value           text            not null,
    parent_post_id  bigint unsigned, 
    topic_post_id   bigint unsigned not null, 

    primary key (post_id),
    foreign key (user_id) references users (user_id),
    foreign key (parent_post_id) references comment_posts (post_id),
    foreign key (topic_post_id) references blog_posts (post_id)
) type = InnoDB;

create table tag {
    tag_id bigint unsigned not null,
    name varchar(255)    not null
} type = InnoDB;


