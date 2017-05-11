
insert into wp_users (user_login, user_pass, user_email, user_status) values ('Jack', 'password', 'jack@mail.com', 1);
insert into wp_users (user_login, user_pass, user_email, user_status) values ('Cameron', 'password', 'cameron@mail.com', 1);
insert into wp_users (user_login, user_pass, user_email, user_status) values ('Pamela', 'password', 'wchrichton2@trellian.com', 1);
insert into wp_users (user_login, user_pass, user_email, user_status) values ('Scarlet', 'password', 'mgoodson3@usa.gov', 1);
insert into wp_users (user_login, user_pass, user_email, user_status) values ('Adorne', 'password', 'kheggman4@flickr.com', 1);


insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (1, 'Jack', 'Waayer', '62-(232)313-6574', '1-(268)216-0509');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (2, 'Cameron', 'Anderson', '420-(644)681-4865', '86-(956)107-4014');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (3, 'Pamela', 'Cadalleder', '86-(390)769-3001', '7-(374)945-9561');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (4, 'Scarlet', 'Routley', '95-(443)791-4910', '86-(735)488-7996');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (5, 'Adorne', 'McJerrow', '380-(478)378-3676', '58-(107)116-2136');


insert into cj_room (room_name, description, price) values ('Single Room', 'This is a single room for one person', '160.00');
insert into cj_room (room_name, description, price) values ('Executive Suite', 'This is one bad ass room', '275.50');


insert into cj_booking (account_id, room_id, date_reserved, date_in, date_out, status, type) values (1, 1, '11/05/2017', '13/05/2017', '16/05/2017', 0, 0);
insert into cj_booking (account_id, room_id, date_reserved, date_in, date_out, status, type) values (1, 2, '10/05/2017', '12/05/2017', '20/05/2017', 2, 0);
insert into cj_booking (account_id, room_id, date_reserved, date_in, date_out, status, type) values (1, 2, '15/05/2017', '20/05/2017', '22/05/2017', 1, 0);
insert into cj_booking (account_id, room_id, date_reserved, date_in, date_out, status, type) values (2, 1, '05/05/2017', '19/05/2017', '21/05/2017', 2, 1);
insert into cj_booking (account_id, room_id, date_reserved, date_in, date_out, status, type) values (2, 2, '05/05/2017', '24/05/2017', '27/05/2017', 2, 1);

