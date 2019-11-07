create table polls {
    poll_id int(255) unsigned auto_increment primary key,
    poll_category varchar(100),
    poll_user_id int(255) unsigned not null,
    poll_name longtext not null,
    poll_created_at timestamp,
    poll_likes int unsigned default 0,
    poll_dislikes int unsigned default 0,    
    poll_view int unsigned default 0,    
    poll_likes varchar(100) unsigned default "public"
}


create table options (
    option_id int unsigned auto_increment primary key,
    option_name varchar(100) not null,
    option_belongsto int not null,
    option_addedby_id int not null,
    option_created_at timestamp,
    option_votes int not null default 0
)

create table comments (
    comment_id int unsigned auto_increment primary key,
    comment_name varchar(100) not null,
    comment_belongsto int not null,
    comment_addedby_id int not null,
    comment_created_at timestamp
)