
insert into wp_users (ID, user_login, user_pass, user_email, user_status) values ('2', 'Jack', 'password', 'jack@mail.com', 1);
insert into wp_users (ID, user_login, user_pass, user_email, user_status) values ('3', 'Cameron', 'password', 'cameron@mail.com', 1);
insert into wp_users (ID, user_login, user_pass, user_email, user_status) values ('4', 'Pamela', 'password', 'wchrichton2@trellian.com', 1);
insert into wp_users (ID, user_login, user_pass, user_email, user_status) values ('5', 'Scarlet', 'password', 'mgoodson3@usa.gov', 1);
insert into wp_users (ID, user_login, user_pass, user_email, user_status) values ('6', 'Adorne', 'password', 'kheggman4@flickr.com', 1);


insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (2, 'Jack', 'Waayer', '62-(232)313-6574', '1-(268)216-0509');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (3, 'Cameron', 'Anderson', '420-(644)681-4865', '86-(956)107-4014');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (4, 'Pamela', 'Cadalleder', '86-(390)769-3001', '7-(374)945-9561');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (5, 'Scarlet', 'Routley', '95-(443)791-4910', '86-(735)488-7996');
insert into cj_account (user_id, first_name, last_name, home_number, mobile_number) values (6, 'Adorne', 'McJerrow', '380-(478)378-3676', '58-(107)116-2136');


insert into cj_room (room_name, description, price) values ('Single Room', 'This is a single room for one person', '160.00');
insert into cj_room (room_name, description, price) values ('Executive Suite', 'This is one bad ass room', '275.50');


insert into cj_booking (account_id, room_id, date_booked, status, type) values (1, 1, '2017-05-11', 0, 0);
insert into cj_booking (account_id, room_id, date_booked, status, type) values (1, 2, '2017-05-10', 2, 0);
insert into cj_booking (account_id, room_id, date_booked, status, type) values (1, 2, '2017-05-15', 1, 0);
insert into cj_booking (account_id, room_id, date_booked, status, type) values (2, 1, '2017-05-05', 2, 1);
insert into cj_booking (account_id, room_id, date_booked, status, type) values (2, 2, '2017-05-05', 2, 1);


insert into cj_extra (extra_name, price) values ('Parking space', 10.50);
insert into cj_extra (extra_name, price) values ('Breakfast', 8.50);
insert into cj_extra (extra_name, price) values ('Room with view', 15.00);
insert into cj_extra (extra_name, price) values ('Baby utillites', 11.40);
insert into cj_extra (extra_name, price) values ('Late checkout', 5.55);

