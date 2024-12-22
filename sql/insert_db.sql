INSERT INTO Cabinet (CabinetNumber, Corpus, Purpose, Capacity)
VALUES
(100, 'А', 'Лабораторные работы', 20),
(101, 'А', 'Лекции', 50),
(100, 'Б', 'Семинары', 30);

INSERT INTO Facultet (FacultetCipher)
VALUES
('ФИИТ'),
('ИВТ');

INSERT INTO Speciality (Facultet, SpecialityCipher)
VALUES
(1, '09.03.01'),
(1, '09.03.02'),
(2, '01.03.02');

INSERT INTO Predmet (PredmetName)
VALUES
('Математика'),
('Программирование'),
('Физика'),
('Алгоритмы и структуры данных'),
('Базы данных');

INSERT INTO Teacher (Name, LastName, ThirdName, Cafedra)
VALUES
('Иван', 'Петров', 'Алексеевич', 1),
('Мария', 'Смирнова', 'Ивановна', 1),
('Олег', 'Кузнецов', 'Викторович', 2),
('Анна', 'Волкова', 'Дмитриевна', 2);

INSERT INTO StudyGroup (Cipher, Speciality, GroupSize)
VALUES
('ФИИТ-101', 1, 20),
('ФИИТ-102', 1, 10),
('ИВТ-201', 2, 25),
('ИВТ-202', 2, 30);

INSERT INTO StudyPlane (Speciality, Predmet, LessonType, Hours)
VALUES
(1, 1, 'Лекция', 32),
(1, 2, 'Лабораторная работа', 48),
(2, 3, 'Лекция', 40),
(2, 4, 'Семинар', 20),
(3, 5, 'Лекция', 36);

INSERT INTO Schedule (DayOfWeek, LessonOrder, Cafedra, Teacher, Cabinet, StudyGroup, Plane)
VALUES
('Понедельник', 1, 1, 1, 1, 1, 1),
('Понедельник', 2, 1, 2, 2, 2, 2),
('Вторник', 3, 2, 3, 3, 3, 3),
('Среда', 4, 2, 4, 1, 4, 4),
('Четверг', 5, 1, 1, 2, 1, 5);