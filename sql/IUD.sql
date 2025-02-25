-- Facultet
CREATE OR REPLACE FUNCTION insert_facultet(
    cipher VARCHAR
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Facultet (FacultetCipher) 
    VALUES (cipher);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_facultet_cipher(facultet_id INT, new_cipher VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Facultet SET FacultetCipher = new_cipher WHERE FacultetID = facultet_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_facultet(facultet_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Facultet WHERE FacultetID = facultet_id;
END;
$$ LANGUAGE plpgsql;

-- Speciality
CREATE OR REPLACE FUNCTION insert_speciality(
    fac_id INT, 
    cipher VARCHAR, 
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Speciality (Facultet, SpecialityCipher) 
    VALUES (fac_id, cipher);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_speciality_cipher(speciality_id INT, new_cipher VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Speciality SET SpecialityCipher = new_cipher WHERE SpecialityID = speciality_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_speciality(speciality_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Speciality WHERE SpecialityID = speciality_id;
END;
$$ LANGUAGE plpgsql;

-- Cabinet
CREATE OR REPLACE FUNCTION insert_cabinet(
    num INT, 
    corpus VARCHAR, 
    purpose VARCHAR, 
    capacity INT
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Cabinet (CabinetNumber, Corpus, Purpose, Capacity) 
    VALUES (num, corpus, purpose, capacity);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_cabinet_purpose(cabinet_id INT, new_purpose VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Cabinet SET Purpose = new_purpose WHERE CabinetID = cabinet_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_cabinet_capacity(cabinet_id INT, new_capacity INT)
RETURNS VOID AS $$
BEGIN
    UPDATE Cabinet SET Capacity = new_capacity WHERE CabinetID = cabinet_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_cabinet(cabinet_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Cabinet WHERE CabinetID = cabinet_id;
END;
$$ LANGUAGE plpgsql;

-- Cafedra
CREATE OR REPLACE FUNCTION insert_cafedra(
    fac_id INT, 
    cipher VARCHAR, 
    login VARCHAR, 
    passwd VARCHAR
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Cafedra (FacultetID, CafedraCipher, Login, Password) 
    VALUES (fac_id, cipher, login, passwd);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_cafedra_cipher(cafedra_id INT, new_cipher VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Cafedra SET CafedraCipher = new_cipher WHERE CafedraID = cafedra_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_cafedra_login(cafedra_id INT, new_login VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Cafedra SET Login = new_login WHERE CafedraID = cafedra_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_cafedra_password(cafedra_id INT, new_password VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Cafedra SET Password = new_password WHERE CafedraID = cafedra_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_cafedra(cafedra_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Cafedra WHERE CafedraID = cafedra_id;
END;
$$ LANGUAGE plpgsql;

-- Predmet
CREATE OR REPLACE FUNCTION insert_predmet( 
    predmet_name VARCHAR
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Predmet (PredmetName) 
    VALUES (predmet_name);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_predmet_name(predmet_id INT, new_name VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Predmet SET PredmetName = new_name WHERE PredmetID = predmet_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_predmet(predmet_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Predmet WHERE PredmetID = predmet_id;
END;
$$ LANGUAGE plpgsql;

-- Teacher
CREATE OR REPLACE FUNCTION insert_teacher(
    name VARCHAR, 
    last_name VARCHAR, 
    third_name VARCHAR,
    cafedra_id INT
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Teacher (Name, LastName, ThirdName, Cafedra) 
    VALUES (name, last_name, third_name, cafedra_id);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_teacher_name(teacher_id INT, new_name VARCHAR, new_last_name VARCHAR, new_third_name VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE Teacher 
    SET Name = new_name, LastName = new_last_name, ThirdName = new_third_name 
    WHERE TeacherID = teacher_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_teacher_cafedra(teacher_id INT, new_cafedra_id INT)
RETURNS VOID AS $$
BEGIN
    UPDATE Teacher SET Cafedra = new_cafedra_id WHERE TeacherID = teacher_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_teacher(teacher_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Teacher WHERE TeacherID = teacher_id;
END;
$$ LANGUAGE plpgsql;

-- StudyGroup
CREATE OR REPLACE FUNCTION insert_study_group(
    cipher VARCHAR, 
    speciality_id INT, 
    group_size INT
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO StudyGroup (Cipher, Speciality, GroupSize) 
    VALUES (cipher, speciality_id, group_size);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_group_cipher(group_id INT, new_cipher VARCHAR)
RETURNS VOID AS $$
BEGIN
    UPDATE StudyGroup SET Cipher = new_cipher WHERE GroupID = group_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_group_group_size(group_id INT, new_group_size INT)
RETURNS VOID AS $$
BEGIN
    UPDATE StudyGroup SET GroupSize = new_group_size WHERE GroupID = group_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_group(group_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM StudyGroup WHERE GroupID = group_id;
END;
$$ LANGUAGE plpgsql;

-- StudyPlane
CREATE OR REPLACE FUNCTION insert_study_plane(
    predmet INT, 
    speciality_id INT, 
    lesson_type VARCHAR,
    hours INT
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO StudyPlane (Predmet, Speciality, LessonType, Hours) 
    VALUES (predmet, speciality_id, lesson_type, hours);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_study_plane_hours(plane_id INT, new_hours INT)
RETURNS VOID AS $$
BEGIN
    UPDATE StudyPlane SET Hours = new_hours WHERE PlaneID = plane_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_study_plane(plane_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM StudyPlane WHERE PlaneID = plane_id;
END;
$$ LANGUAGE plpgsql;

-- Schedule
CREATE OR REPLACE FUNCTION insert_schedule(
    day_of_week VARCHAR, 
    lesson_order INT, 
    cafedra_id INT,
    teacher_id INT,
    cabinet_id INT,
    study_group_id INT,
    plane_id INT
)
RETURNS VOID AS $$
BEGIN
    INSERT INTO Schedule (DayOfWeek, LessonOrder, Cafedra, Teacher, Cabinet, StudyGroup, Plane) 
    VALUES (day_of_week, lesson_order, cafedra_id, teacher_id, cabinet_id, study_group_id, plane_id);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_schedule_lesson(schedule_id INT, new_day VARCHAR, new_order INT)
RETURNS VOID AS $$
BEGIN
    UPDATE Schedule 
    SET DayOfWeek = new_day, LessonOrder = new_order 
    WHERE ScheduleID = schedule_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_schedule_teacher(schedule_id INT, new_teacher INT)
RETURNS VOID AS $$
BEGIN
    UPDATE Schedule SET Teacher = new_teacher WHERE ScheduleID = schedule_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_schedule_cabinet(schedule_id INT, new_cabinet INT)
RETURNS VOID AS $$
BEGIN
    UPDATE Schedule SET Cabinet = new_cabinet WHERE ScheduleID = schedule_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION update_schedule_group(schedule_id INT, new_group INT)
RETURNS VOID AS $$
BEGIN
    UPDATE Schedule SET Group = new_group WHERE ScheduleID = schedule_id;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION delete_schedule(schedule_id INT)
RETURNS VOID AS $$
BEGIN
    DELETE FROM Schedule WHERE ScheduleID = schedule_id;
END;
$$ LANGUAGE plpgsql;
