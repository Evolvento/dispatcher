CREATE OR REPLACE FUNCTION check_lesson_and_cabinet_type()
RETURNS TRIGGER AS $$
BEGIN
    DECLARE
        cabinet_purpose VARCHAR(30);
        lesson_type VARCHAR(30);
    BEGIN
        SELECT Purpose INTO cabinet_purpose
        FROM Cabinet
        WHERE CabinetID = NEW.Cabinet;

        SELECT LessonType INTO lesson_type
        FROM StudyPlane
        WHERE PlaneID = NEW.Plane;

        IF cabinet_purpose <> lesson_type THEN
            RAISE EXCEPTION 'Тип аудитории (%) и тип занятия (%) не совпадают.',
            cabinet_purpose, lesson_type;
        END IF;
    END;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_lesson_and_cabinet_type
BEFORE INSERT OR UPDATE ON Schedule
FOR EACH ROW
EXECUTE FUNCTION check_lesson_and_cabinet_type();

-------------------------------------------------------------

CREATE OR REPLACE FUNCTION check_unique_group_schedule()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Schedule
        WHERE DayOfWeek = NEW.DayOfWeek
          AND LessonOrder = NEW.LessonOrder
          AND Group = NEW.Group
          AND ScheduleID <> NEW.ScheduleID
    ) THEN
        RAISE EXCEPTION 'Для группы уже существует занятие на этот день и время';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_unique_group_schedule
BEFORE INSERT OR UPDATE ON Schedule
FOR EACH ROW
EXECUTE FUNCTION check_unique_group_schedule();

-----------------------------------------------------------

CREATE OR REPLACE FUNCTION check_cabinet_capacity()
RETURNS TRIGGER AS $$
BEGIN
    DECLARE
        group_size INTEGER;
        cabinet_capacity INTEGER;
    BEGIN
        SELECT GroupSize
        INTO group_size
        FROM StudyGroup
        WHERE GroupID = NEW.Group;

        SELECT Capacity
        INTO cabinet_capacity
        FROM Cabinet
        WHERE CabinetID = NEW.Cabinet;

        IF group_size > cabinet_capacity THEN
            RAISE EXCEPTION 'Размер группы превышает вместимость аудитории';
        END IF;
    END;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_cabinet_capacity
BEFORE INSERT OR UPDATE ON Schedule
FOR EACH ROW
EXECUTE FUNCTION check_cabinet_capacity();

------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION check_teacher_availability()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM Schedule
        WHERE DayOfWeek = NEW.DayOfWeek
          AND LessonOrder = NEW.LessonOrder
          AND Teacher = NEW.Teacher
          AND ScheduleID <> NEW.ScheduleID
    ) THEN
        RAISE EXCEPTION 'Преподаватель уже занят в это время';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_check_teacher_availability
BEFORE INSERT OR UPDATE ON Schedule
FOR EACH ROW
EXECUTE FUNCTION check_teacher_availability();

----------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION assign_default_cabinet()
RETURNS TRIGGER AS $$
DECLARE
    default_cabinet INTEGER;
BEGIN
    SELECT CabinetID
    INTO default_cabinet
    FROM Cabinet
    WHERE Purpose = NEW.LessonType
    LIMIT 1;

    IF default_cabinet IS NULL THEN
        RAISE EXCEPTION 'Нет доступного кабинета для типа занятия %', NEW.LessonType;
    END IF;

    NEW.Cabinet = default_cabinet;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_assign_default_cabinet
BEFORE INSERT ON Schedule
FOR EACH ROW
EXECUTE FUNCTION assign_default_cabinet();
