/*==============================================================*/
/* Values: USERS, CUSTOMER, CREDIT_CARD							*/
/*==============================================================*/
insert into USERS (EMAIL) values ('emptyUser');
insert into CUSTOMER (EMAIL) values ('emptyUser');
insert into CREDIT_CARD (CARDID, EMAIL, CARDNAME) values 
('C0000', 'emptyUser', 'Select a card');

insert into USERS values ('horhou.lam@students.mq.edu.au', 'Jason', 'Lam', 'q123123');
insert into CUSTOMER values ('horhou.lam@students.mq.edu.au', '129', 'Frederick Street', 'Ashfield', 'NSW', '2131');
insert into CREDIT_CARD values ('C0001', 'horhou.lam@students.mq.edu.au', '1234567890', 'Lam Card', '02/2014', '123');

/*==============================================================*/
/* Values: CATEGORY												*/
/*==============================================================*/
insert into CATEGORY values
('CA001', 'Book');

insert into CATEGORY values
('CA002', 'DVD');

insert into CATEGORY values
('CA003', 'Devices');

/*==============================================================*/
/* Values: ATTRIBUTENAME                                        */
/*==============================================================*/
insert into ATTRIBUTENAME values
('A0001', 'CA001', 'Author');

insert into ATTRIBUTENAME values
('A0002', 'CA001', 'Publisher');

insert into ATTRIBUTENAME values
('A0003', 'CA001', 'ISBN');

insert into ATTRIBUTENAME values
('A0004', 'CA001', 'Number of pages');

insert into ATTRIBUTENAME values
('A0005', 'CA001', 'Year');

insert into ATTRIBUTENAME values
('A0006', 'CA001', 'Available Stock');

insert into ATTRIBUTENAME values
('A0007', 'CA001', 'Price');

insert into ATTRIBUTENAME values
('A0008', 'CA002', 'Director');

insert into ATTRIBUTENAME values
('A0009', 'CA002', 'Producer');

insert into ATTRIBUTENAME values
('A0010', 'CA002', 'Year');

insert into ATTRIBUTENAME values
('A0011', 'CA002', 'Available Stock');

insert into ATTRIBUTENAME values
('A0012', 'CA002', 'Price');

insert into ATTRIBUTENAME values
('A0013', 'CA003', 'Manufacturer');

insert into ATTRIBUTENAME values
('A0014', 'CA003', 'Model Number');

insert into ATTRIBUTENAME values
('A0015', 'CA003', 'Operating System');

insert into ATTRIBUTENAME values
('A0016', 'CA003', 'Size');

insert into ATTRIBUTENAME values
('A0018', 'CA003', 'Available Stock');

insert into ATTRIBUTENAME values
('A0019', 'CA003', 'Price');


/*==============================================================*/
/* Values: SUBCAT												*/
/*==============================================================*/
insert into SUBCAT values
('SC001', 'CA001', 'Hobby');

insert into SUBCAT values
('SC002', 'CA001', 'Cooking');

insert into SUBCAT values
('SC003', 'CA001', 'Children');

insert into SUBCAT values
('SC004', 'CA001', 'Educational');

insert into SUBCAT values
('SC005', 'CA001', 'Young Adult');

insert into SUBCAT values
('SC006', 'CA001', 'Computing');

insert into SUBCAT values
('SC007', 'CA001', 'Sci-fi');

insert into SUBCAT values
('SC008', 'CA001', 'Fantasy');

insert into SUBCAT values
('SC009', 'CA001', 'Mathematics');

insert into SUBCAT values
('SC010', 'CA001', 'Romance');

insert into SUBCAT values
('SC011', 'CA001', 'Science');

insert into SUBCAT values
('SC012', 'CA002', 'Romance');

insert into SUBCAT values
('SC013', 'CA002', 'Action');

insert into SUBCAT values
('SC014', 'CA002', 'Sci-fi');

insert into SUBCAT values
('SC015', 'CA002', 'Documentaries');

insert into SUBCAT values
('SC016', 'CA003', 'Tablets and E-Readers');

/*==============================================================*/
/* Values: PRODUCT												*/
/*==============================================================*/
insert into PRODUCT values 
('P0001', 'Book of Cars', 'A must have for car enthusiasts, John Bob takes you into his world of fast and sexy cars.' );

insert into PRODUCT values
('P0002', 'Delicous Mexican Food', 'A simple guide on making the finest of Mexican food.');

insert into PRODUCT values
('P0003', 'The Happy Dog', 'Rex is a happy dog, who find new bones.');

insert into PRODUCT values
('P0004', 'Advanced Vector Calculus', 'Maths is fun.');

insert into PRODUCT values
('P0005', 'The Black Lotus', '');

insert into PRODUCT values
('P0006', 'Cloud Computing Fundamentals', '');

insert into PRODUCT values
('P0007', 'Hollow Soul', '');

insert into PRODUCT values
('P0008', 'Apparition Stalks the Night', '');

insert into PRODUCT values
('P0009', 'Introduction to Astrophysics', '');

insert into PRODUCT values
('P0010', 'Interesting Desserts', '');

insert into PRODUCT values
('P0011', 'Galaxy Conflict I', '');

insert into PRODUCT values
('P0012', 'Galaxy Conflict II', '');

insert into PRODUCT values
('P0013', 'Galaxy Conflict III', '');

insert into PRODUCT values
('P0014', 'Predators of the Animal Kingdom', '');

insert into PRODUCT values
('P0015', 'Orange mySlate', '');

insert into PRODUCT values
('P0016', 'Rainforest Ignite', '');

insert into PRODUCT values
('P0017', 'Samsong Universal Pad 8.9', '');

/*==============================================================*/
/* Values: PRODUCTSUBCAT										*/
/*==============================================================*/
insert into PRODUCTSUBCAT values ('P0001', 'SC001');
insert into PRODUCTSUBCAT values ('P0002', 'SC002');
insert into PRODUCTSUBCAT values ('P0003', 'SC003');
insert into PRODUCTSUBCAT values ('P0004', 'SC004');
insert into PRODUCTSUBCAT values ('P0004', 'SC009');
insert into PRODUCTSUBCAT values ('P0004', 'SC011');
insert into PRODUCTSUBCAT values ('P0005', 'SC005');
insert into PRODUCTSUBCAT values ('P0005', 'SC010');
insert into PRODUCTSUBCAT values ('P0005', 'SC011');
insert into PRODUCTSUBCAT values ('P0005', 'SC012');
insert into PRODUCTSUBCAT values ('P0006', 'SC004');
insert into PRODUCTSUBCAT values ('P0006', 'SC006');
insert into PRODUCTSUBCAT values ('P0007', 'SC008');
insert into PRODUCTSUBCAT values ('P0008', 'SC005');
insert into PRODUCTSUBCAT values ('P0008', 'SC007');
insert into PRODUCTSUBCAT values ('P0008', 'SC008');
insert into PRODUCTSUBCAT values ('P0009', 'SC004');
insert into PRODUCTSUBCAT values ('P0009', 'SC011');
insert into PRODUCTSUBCAT values ('P0010', 'SC002');
insert into PRODUCTSUBCAT values ('P0011', 'SC013');
insert into PRODUCTSUBCAT values ('P0011', 'SC014');
insert into PRODUCTSUBCAT values ('P0012', 'SC013');
insert into PRODUCTSUBCAT values ('P0012', 'SC014');
insert into PRODUCTSUBCAT values ('P0013', 'SC013');
insert into PRODUCTSUBCAT values ('P0013', 'SC014');
insert into PRODUCTSUBCAT values ('P0014', 'SC015');
insert into PRODUCTSUBCAT values ('P0015', 'SC016');
insert into PRODUCTSUBCAT values ('P0016', 'SC016');
insert into PRODUCTSUBCAT values ('P0017', 'SC016');

/*==============================================================*/
/* Values: ITEM													*/
/*==============================================================*/
insert into ITEM values 
('P0001', 'CA001', 'John Bob:-Wilsons Press:-7567491235:-150:-1998:-20:-20', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0002', 'CA001', 'Enrico Sanchez:-Wests Books:-4563876254:-223:-2007:-20:-14.95', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0003', 'CA001', 'Jane Smith:-Early Bird:-3452793516:-27:-2000:-20:-12', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0004', 'CA001', 'Jim Chen:-Howard and Smith:-6354826749:-526:-1989:-20:-350', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0005', 'CA001', 'Emma Sullivan:-Harper Collins:-1526493588:-485:-2009:-20:-40', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0005', 'CA002', 'Josh Stone:-Galaxy Studios:-2011:-20:-35', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0006', 'CA001', 'Kenny Sparks:-Pendant Publishing:-7625439681:-632:-2006:-20:-219.95', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0007', 'CA001', 'TK Anderson:-Harper Collins:-4625354864:-352:-2012:-20:-50', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0008', 'CA001', 'LD:-Wests Books:-2653481259:-250:-1992:-0:-30', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0009', 'CA001', 'Leeroy Jones:-Wilsons Press:-7699966997:-572:-1994:-20:-330', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0010', 'CA001', 'Kari Austin:-Pendant Publishing:-4623846345:-226:-2002:-20:-35', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0011', 'CA002', 'Geoff Lancaster:-Millenium Pictures:-2004:-20:-30', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0012', 'CA002', 'Geoff Lancaster:-Millenium Pictures:-2008:-20:-30', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0013', 'CA002', 'Geoff Lancaster:-Millenium Pictures:-2011:-20:-30', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0014', 'CA002', 'Jack Johnson:-Explorer Channel:-2005:-20:-20', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0015', 'CA003', 'Orange:-M823TF6:-myOS 6:-32GB:-20:-699.95', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0016', 'CA003', 'Rainforest:-IN3000S:-Robot 2.3:-8GB:-20:-200', 'bookcovers/smiley.jpg');

insert into ITEM values
('P0017', 'CA003', 'Samsong:-UP9025I:-Robot 4.2:-32GB:-20:-400', 'bookcovers/smiley.jpg');

commit;