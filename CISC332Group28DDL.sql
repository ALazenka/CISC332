CREATE TABLE Theater_Complex
(
  name VARCHAR(50) NOT NULL,
  street VARCHAR(50) NOT NULL,
  town VARCHAR(50) NOT NULL,
  postalcode VARCHAR(50) NOT NULL,
  province VARCHAR(50) NOT NULL,
  country VARCHAR(50) NOT NULL,
  phone_number NUMERIC NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id));

CREATE TABLE Theater
(
  theater_number INT NOT NULL,
  max_seats INT NOT NULL,
  screen_size VARCHAR(20) NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id));

CREATE TABLE Movie_Supplier
(
  company_name VARCHAR(50) NOT NULL,
  street VARCHAR(50) NOT NULL,
  town VARCHAR(50) NOT NULL,
  postalcode VARCHAR(50) NOT NULL,
  province VARCHAR(50) NOT NULL,
  country VARCHAR(50) NOT NULL,
  phone_number NUMERIC NOT NULL,
  contact_first_name VARCHAR(50) NOT NULL,
  contact_last_name VARCHAR(50) NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id));

CREATE TABLE Customer
(
  account_number INT NOT NULL,
  password VARCHAR(20) NOT NULL,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  street VARCHAR(50) NOT NULL,
  town VARCHAR(50) NOT NULL,
  postalcode VARCHAR(50) NOT NULL,
  province VARCHAR(50) NOT NULL,
  country VARCHAR(50) NOT NULL,
  phone_number NUMERIC NOT NULL,
  email_address VARCHAR(50) NOT NULL,
  cc_number BIGINT NOT NULL,
  cc_expiry_date VARCHAR(50) NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id),
  UNIQUE (account_number),
  UNIQUE (email_address));

CREATE TABLE Movie
(
  title VARCHAR(50) NOT NULL,
  run_time NUMERIC NOT NULL,
  rating VARCHAR(2) NOT NULL,
  synopsis VARCHAR(1000) NOT NULL,
  director VARCHAR(50) NOT NULL,
  production_company VARCHAR(50) NOT NULL,
  supplier_name VARCHAR(50) NOT NULL,
  start_date NUMERIC NOT NULL,
  end_date NUMERIC NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id));

CREATE TABLE Reservation
(
  account_number INT NOT NULL,
  showing_id INT NOT NULL,
  tickets_reserved INT NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id),
  UNIQUE (account_number),
  UNIQUE (showing_id));

CREATE TABLE Review
(
  title VARCHAR(50) NOT NULL,
  content VARCHAR(1000) NOT NULL,
  score INT(1) NOT NULL,
  movie_id INT NOT NULL,
  id INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id),
  UNIQUE (movie_id));

CREATE TABLE Showing
(
  id INT NOT NULL AUTO_INCREMENT,
  theater_complex_id INT NOT NULL,
  movie_id INT NOT NULL,
  theater_id INT NOT NULL,
  start_time VARCHAR(10) NOT NULL,
  seats_available INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE (theater_complex_id),
  UNIQUE (movie_id),
  UNIQUE (theater_id));

CREATE TABLE customer_res
(
  id INT NOT NULL AUTO_INCREMENT,
  reservation_id INT NOT NULL,
  customer_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (customer_id) REFERENCES Customer(id),
  FOREIGN KEY (reservation_id) REFERENCES Reservation(id));

CREATE TABLE movie_review
(
  id INT NOT NULL AUTO_INCREMENT,
  review_id INT NOT NULL,
  movie_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (movie_id) REFERENCES Movie(id),
  FOREIGN KEY (review_id) REFERENCES Review(id));

CREATE TABLE customer_review
(
  id INT NOT NULL AUTO_INCREMENT,
  customer_id INT NOT NULL,
  review_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (customer_id) REFERENCES Customer(id),
  FOREIGN KEY (review_id) REFERENCES Review(id));

CREATE TABLE in_complex
(
  id INT NOT NULL AUTO_INCREMENT,
  theater_id INT NOT NULL,
  complex_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (complex_id) REFERENCES Theater_Complex(id),
  FOREIGN KEY (theater_id) REFERENCES Theater(id));

CREATE TABLE theater_showing
(
  id INT NOT NULL AUTO_INCREMENT,
  theater_id INT NOT NULL,
  showing_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (theater_id) REFERENCES Theater(id),
  FOREIGN KEY (showing_id) REFERENCES Showing(id));

CREATE TABLE movie_showing
(
  id INT NOT NULL AUTO_INCREMENT,
  movie_id INT NOT NULL,
  showing_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (movie_id) REFERENCES Movie(id),
  FOREIGN KEY (showing_id) REFERENCES Showing(id));

CREATE TABLE Actor
(
  id INT NOT NULL AUTO_INCREMENT,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  PRIMARY KEY (id));

CREATE TABLE actor_movie
(
  id INT NOT NULL AUTO_INCREMENT,
  actor_id INT NOT NULL,
  movie_id INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (actor_id) REFERENCES Actor(id),
  FOREIGN KEY (movie_id) REFERENCES Movie(id));

INSERT INTO Theater_Complex
(name, street, town, postalcode, province, country, phone_number, id)
VALUES
('Landmark Cinemas', '123 Day St.', 'Kingston', 'K7L4Y1', 'Ontario', 'Canada', 6135557819, 1);

INSERT INTO Theater_Complex
(name, street, town, postalcode, province, country, phone_number, id)
VALUES
('Rainbow Cinemas', '465 Canyon Dr.', 'Toronto', 'M3X2J7', 'Ontario', 'Canada', 4167779089, 2);

INSERT INTO Theater_Complex
(name, street, town, postalcode, province, country, phone_number, id)
VALUES
('Cineplex', '1000 Ridge Rd.', 'Ottawa', 'L3M2J8', 'Ontario', 'Canada', 6138796754, 3);

INSERT INTO Theater
(id, theater_number, max_seats, screen_size)
VALUES
(1, 1, 200, 'small');

INSERT INTO Theater
(id, theater_number, max_seats, screen_size)
VALUES
(2, 2, 250, 'medium');

INSERT INTO Theater
(id, theater_number, max_seats, screen_size)
VALUES
(3, 3, 300, 'large');

INSERT INTO Movie_Supplier
(id, company_name, street, town, postalcode, province, country, phone_number, contact_first_name, contact_last_name)
VALUES
(1, 'Walt Disney Studios', '22 Lawson Ave.', 'Toronto', 'L1V7Y2', 'Ontario', 'Canada', 4167954783, 'Susan', 'Boyle');

INSERT INTO Movie_Supplier
(id, company_name, street, town, postalcode, province, country, phone_number, contact_first_name, contact_last_name)
VALUES
(2, 'Warner Bros', '265 Kingston Rd.', 'London', 'K6Y4E1', 'Ontario', 'Canada', 2896666789, 'Hugh', 'Drew');

INSERT INTO Movie_Supplier
(id, company_name, street, town, postalcode, province, country, phone_number, contact_first_name, contact_last_name)
VALUES
(3, 'Sony Pictures', '76 Queen St.', 'Ottawa', 'M9T8W3', 'Ontario', 'Canada', 6472647777, 'Alex', 'Trebec');

INSERT INTO Customer
(id, account_number, password, firstname, lastname, street, town, postalcode, province, country, phone_number, email_address, cc_number, cc_expiry_date)
VALUES
(1, 10178956, 'ir0ck!', 'Beyonce', 'Knowles', 'Johnson St.', 'Toronto', 'L2N7H4', 'Ontario', 'Canada', 4162227348, 'iambeyonce@gmail.com', 9574847390567894, '10/19');

INSERT INTO Customer
(id, account_number, password, firstname, lastname, street, town, postalcode, province, country, phone_number, email_address, cc_number, cc_expiry_date)
VALUES
(2, 12167345, '#aw3sum', 'Bruno', 'Mars', 'King St.', 'Toronto', 'H2G6N3', 'Ontario', 'Canada', 6478934444, 'uptownfunk@gmail.com', 8459037628797485, '05/20');

INSERT INTO Customer
(id, account_number, password, firstname, lastname, street, town, postalcode, province, country, phone_number, email_address, cc_number, cc_expiry_date)
VALUES
(3, 10176676, 'letmein', 'David', 'Letterman', 'Wildwood', 'Vancouver', 'O8I8P8', 'British Columbia', 'Canada', '3332226666', 'dletterman@hotmail.com', 2147483647, '10/18');

INSERT INTO Movie
(id, run_time, rating, synopsis, director, production_company, supplier_name, start_date, end_date, title)
VALUES
(1, 132, 'PG', 'Inspired by the imagination of P. T. Barnum, The Greatest Showman is an original musical that celebrates the birth of show business & tells of a visionary who rose from nothing to create a spectacle that became a worldwide sensation.', 'Michael Gracey', 'Seed Production', 'Warner Bros', 10042018, 11042018, 'The Greatest Showman');

INSERT INTO Movie
(id, run_time, rating, synopsis, director, production_company, supplier_name, start_date, end_date, title)
VALUES
(2, 117, 'R', 'Lara Croft, the fiercely independent daughter of a missing adventurer, must push herself beyond her limits when she finds herself on the island where her father disappeared.', 'Roar Uthaug', 'Colubia Entertainment', 'Warner Bros', 10042018, 11042018, 'Tomb Raider');

INSERT INTO Movie
(id, run_time, rating, synopsis, director, production_company, supplier_name, start_date, end_date, title)
VALUES
(3, 115, '14A', 'After the death of his father, T Challa returns home to the African nation of Wakanda to take his rightful place as king. When a powerful enemy suddenly reappears, T Challas mettle as king -- and as Black Panther -- gets tested when he is drawn into a conflict that puts the fate of Wakanda and the entire world at risk. Faced with treachery and danger, the young king must rally his allies and release the full power of Black Panther to defeat his foes and secure the safety of his people.', 'Ryan Coogler', 'Marvel Studios', 'Walt Disney Studios', 4222018, 06152018, 'Black Panther');

INSERT INTO Reservation
(showing_id, account_number, tickets_reserved, id)
VALUES
(1, 10178956, 3, 1);

INSERT INTO Reservation
(showing_id, account_number, tickets_reserved, id)
VALUES
(2, 12167345, 2, 2);

INSERT INTO Reservation
(showing_id, account_number, tickets_reserved, id)
VALUES
(3, 10176676, 1, 3);

INSERT INTO Review
(id, title, content, score, movie_id)
VALUES
(1, 'Beyonce', 'The Greatest Showman tries hard to dazzle the audience with a Barnum-style sense of wonder -- but at the expense of its complex subject is far more intriguing real-life story.', 3, 1);

INSERT INTO Review
(id, title, content, score, movie_id)
VALUES
(2, 'Bruno', 'Black Panther elevates superhero cinema to thrilling new heights while telling one of the MCUs most absorbing stories -- and introducing some of its most fully realized characters.', 4, 2);

INSERT INTO Review
(id, title, content, score, movie_id)
VALUES
(3, "Andrew's Review", "Tomb Raider felt like an instant classic, would recommend this to all of my friends!", 3, 3);

INSERT INTO `showing` (`movie_id`, `theater_id`, `theater_complex_id`, `start_time`, `seats_available`) VALUES
(1, 1, 1, '2:10', 100),
(2, 2, 2, '3:30', 110),
(3, 3, 3, '12:00', 90);

INSERT INTO `actor` (`id`, `firstname`, `lastname`) VALUES
(1, 'Tom', 'Cruise'),
(2, 'Jennifer', 'Lawrence'),
(3, 'Jim', 'Carrey');

INSERT INTO `actor_movie` (`id`, `actor_id`, `movie_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

INSERT INTO `movie_review` (`id`, `review_id`, `movie_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

INSERT INTO `customer_review` (`id`, `customer_id`, `review_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

INSERT INTO `customer_res` (`id`, `reservation_id`, `customer_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

INSERT INTO `movie_showing` (`id`, `movie_id`, `showing_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

INSERT INTO `theater_showing` (`id`, `theater_id`, `showing_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);