DROP TABLE IF EXISTS Facultet CASCADE;
DROP TABLE IF EXISTS Speciality CASCADE;
DROP TABLE IF EXISTS Cabinet CASCADE;
DROP TABLE IF EXISTS Cafedra CASCADE;
DROP TABLE IF EXISTS Predmet CASCADE;
DROP TABLE IF EXISTS Teacher CASCADE;
DROP TABLE IF EXISTS StudyGroup CASCADE;
DROP TABLE IF EXISTS Schedule CASCADE;
DROP TABLE IF EXISTS StudyPlane CASCADE;

CREATE TABLE Facultet (
    FacultetID SERIAL PRIMARY KEY,
    FacultetCipher VARCHAR(10) NOT NULL UNIQUE
);

CREATE TABLE Speciality (
    SpecialityID SERIAL PRIMARY KEY,
    Facultet INTEGER REFERENCES Facultet(FacultetID) ON DELETE CASCADE,
    SpecialityCipher VARCHAR(10) NOT NULL UNIQUE
);

CREATE TABLE Cabinet (
    CabinetID SERIAL PRIMARY KEY,
    CabinetNumber INTEGER NOT NULL,
    Corpus VARCHAR(5) NOT NULL,
    Purpose VARCHAR(30) CHECK (Purpose IN ('Лабораторная работа', 'Лекция', 'Семинар')),
    Capacity INTEGER NOT NULL,
    UNIQUE (Corpus, CabinetNumber)
);

CREATE TABLE Cafedra (
    CafedraID SERIAL PRIMARY KEY,
    FacultetID INTEGER REFERENCES Facultet(FacultetID) ON DELETE CASCADE,
    CafedraCipher VARCHAR(10) NOT NULL UNIQUE,
    Login VARCHAR(30) NOT NULL UNIQUE,
    Password VARCHAR(30) NOT NULL
);

CREATE TABLE Predmet (
    PredmetID SERIAL PRIMARY KEY,
    PredmetName VARCHAR(70) NOT NULL UNIQUE
);

CREATE TABLE Teacher (
    TeacherID SERIAL PRIMARY KEY,
    Name VARCHAR(30) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    ThirdName VARCHAR(30),
    Cafedra INTEGER REFERENCES Cafedra(CafedraID) ON DELETE CASCADE
);

CREATE TABLE StudyGroup (
    GroupID SERIAL PRIMARY KEY,
    Cipher VARCHAR(10) NOT NULL UNIQUE,
    Speciality INTEGER REFERENCES Speciality(SpecialityID) ON DELETE CASCADE,
    GroupSize INTEGER CHECK (GroupSize > 0)
);

CREATE TABLE StudyPlane (
    PlaneID SERIAL PRIMARY KEY,
    Speciality INTEGER REFERENCES Speciality(SpecialityID) ON DELETE CASCADE,
    Predmet INTEGER REFERENCES Predmet(PredmetID) ON DELETE CASCADE,
    LessonType VARCHAR(30) CHECK (LessonType IN ('Лабораторная работа', 'Лекция', 'Семинар')),
    Hours INTEGER CHECK (Hours > 0)
);

CREATE TABLE Schedule (
    ScheduleID SERIAL PRIMARY KEY,
    DayOfWeek VARCHAR(15) CHECK (DayOfWeek IN ('Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота')),
    LessonOrder INTEGER CHECK (LessonOrder BETWEEN 1 AND 6),
    Cafedra INTEGER REFERENCES Cafedra(CafedraID) ON DELETE CASCADE,
    Teacher INTEGER REFERENCES Teacher(TeacherID) ON DELETE CASCADE,
    Cabinet INTEGER REFERENCES Cabinet(CabinetID) ON DELETE CASCADE,
    StudyGroup INTEGER REFERENCES StudyGroup(GroupID) ON DELETE CASCADE,
    Plane INTEGER REFERENCES StudyPlane(PlaneID) ON DELETE CASCADE,
    UNIQUE (DayOfWeek, LessonOrder)
);


