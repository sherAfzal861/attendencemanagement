-- Drop the database if it already exists to start fresh
DROP DATABASE IF EXISTS AttendanceManagementSystem;

-- Create the database
CREATE DATABASE IF NOT EXISTS AttendanceManagementSystem;
USE AttendanceManagementSystem;


-- 1. Create the tables
CREATE TABLE IF NOT EXISTS Sessions (
    SessionID INT PRIMARY KEY AUTO_INCREMENT,
    Session VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS Departments (
    DepartmentID INT PRIMARY KEY AUTO_INCREMENT,
    DeptCode INT UNIQUE,
    DeptName VARCHAR(50),
    Password VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS Programs (
    ProgramID INT PRIMARY KEY AUTO_INCREMENT,
    Department VARCHAR(50),
    ProCode INT,
    ProgramName VARCHAR(50) UNIQUE
);

CREATE TABLE IF NOT EXISTS Semesters (
    SemesterID INT PRIMARY KEY AUTO_INCREMENT,
    Semester VARCHAR(10) UNIQUE
);

CREATE TABLE IF NOT EXISTS Subjects (
    SubjectID INT PRIMARY KEY AUTO_INCREMENT,
    Department VARCHAR(50),
    SubjectCode VARCHAR(10),
    SubjectName VARCHAR(100),
    Program VARCHAR(50),
    Semester VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS Teachers (
    TeacherID INT PRIMARY KEY AUTO_INCREMENT,
    DeptCode INT,
    DeptName VARCHAR(50),
    TeacherCode INT UNIQUE,
    TeacherName VARCHAR(100),
    Email varchar(50) NOT NULL,
    Password VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS Students (
    StudentID INT PRIMARY KEY AUTO_INCREMENT,
    SessionID INT,
    RegNumber VARCHAR(50) UNIQUE,
    StudentName VARCHAR(100),
    Department VARCHAR(50),
    Program VARCHAR(50),
    Semester VARCHAR(10),
    Password VARCHAR(20),
    FOREIGN KEY (SessionID) REFERENCES Sessions(SessionID)
);
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `Id` int(10) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- Create table for class schedules
CREATE TABLE IF NOT EXISTS Schedule (
    ScheduleID INT PRIMARY KEY AUTO_INCREMENT,
    Day VARCHAR(20) NOT NULL, -- E.g., "Monday", "Tuesday"
    Time VARCHAR(20) NOT NULL,
    Department varchar(50),
    Program VARCHAR(50),
    Semester VARCHAR(10),
    Subject VARCHAR(100),
    TeacherCode INT  ,
    Session Varchar(50),
    RoomNo VARCHAR(20) NOT NULL,
    FOREIGN KEY (TeacherCode) REFERENCES Teachers(TeacherCode) ON DELETE CASCADE
);
-- Create table for recording attendance
CREATE TABLE IF NOT EXISTS Attendance (
    AttendanceID INT PRIMARY KEY AUTO_INCREMENT,
    StudentRegNumber VARCHAR(50),
    ScheduleID INT,
    Date DATE,
    Status ENUM('Present', 'Absent', 'Leave'),
    CreditHours INT NOT NULL,
    FOREIGN KEY (StudentRegNumber) REFERENCES Students(RegNumber),
    FOREIGN KEY (ScheduleID) REFERENCES Schedule(ScheduleID) ON DELETE CASCADE
);

-- Create table to hold assignment marks for students

CREATE TABLE IF NOT EXISTS AcademicRecord (
    RecordID INT PRIMARY KEY AUTO_INCREMENT,
    StudentRegNumber VARCHAR(50),
    ScheduleID INT,
    AssesmentType ENUM('Assignment1', 'Assignment2', 'Assignment3', 'Quiz1', 'Quiz2', 'Project1', 'Project2'),
    TotalMarks DECIMAL(5,2),
    MarksObtained DECIMAL(5,2),
    SubmissionDate DATE,
    FOREIGN KEY (ScheduleID) REFERENCES Schedule(ScheduleID),
    FOREIGN KEY (StudentRegNumber) REFERENCES Students(RegNumber)
);
CREATE TABLE IF NOT EXISTS HOD (
    HODID INT PRIMARY KEY AUTO_INCREMENT,
    DepartmentID INT,
    TeacherID INT,
    FOREIGN KEY (DepartmentID) REFERENCES Departments(DepartmentID),
    FOREIGN KEY (TeacherID) REFERENCES Teachers(TeacherID)
);

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`Id`, `firstName`, `lastName`, `emailAddress`, `password`) VALUES
(1, 'Admin', '', 'admin@mail.com', '654321');



-- 2. Insert the data into each table

-- Insert Sessions data
INSERT INTO Sessions (Session) VALUES
('Fall 2026'),
('Spring 2026'),
('Fall 2025'),
('Spring 2025'),
('Fall 2024'),
('Spring 2024');

-- Insert Departments data
INSERT INTO Departments (DeptCode, DeptName, Password) VALUES
(107, 'DNAMS', 'DNA987'),
(106, 'Education', 'EDU123'),
(105, 'Engineering Technology', 'ET234'),
(104, 'Social Science', 'SS456'),
(103, 'Management Science', 'MS321'),
(102, 'Computer Science', 'CS123'),
(101, 'Islamic Studies', 'IS786');
-- Insert Programs data
INSERT INTO Programs (Department, ProCode, ProgramName) VALUES
('Education', 1016, 'PHDEDU'),
('Education', 1015, 'MPHEDU'),
('Education', 1014, 'BSEDU'),
('Education', 1013, 'BED2.5'),
('Education', 1012, 'BED1.5'),
('Engineering Technology', 1011, 'BSCT'),
('Management Science', 1010, 'PHDBA'),
('Management Science', 1009, 'MSBA'),
('Management Science', 1008, 'MBAEXE'),
('Management Science', 1007, 'MBA'),
('Management Science', 1006, 'BSAAF'),
('Management Science', 1005, 'BBA'),
('Management Science', 1004, 'ADPBA'),
('Computer Science', 1003, 'MSCS'),
('Computer Science', 1002, 'BSCS'),
('Computer Science', 1001, 'ADPCS');

-- Insert Semesters data
INSERT INTO Semesters (Semester) VALUES
( 'X'),
('IX'),
('VIII'),
('VII'),
('VI'),
('V'),
('IV'),
('III'),
('II'),
('I');
-- Insert Subjects data
InSERT INTO Subjects (Department, SubjectCode, SubjectName, Program, Semester) VALUES
('Education', 'CS-301', ' Introduction to Information Communication Technology', 'BSEDU', 'I'),
('Education', 'AR-301', ' Functional Arabic-I', 'BSEDU', 'I'),
('Education', 'EN-301', 'English-I (English Composition & Comprehension)', 'BSEDU', 'I'),
('Education', 'EB-301', 'Child Development (Foundation)', 'BSEDU', 'I'),
('Education', 'PH-301', 'Applied Physics', 'BSEDU', 'I'),
('Education', 'MT-301', 'Applied Mathematics I', 'BSEDU', 'I'),
('Management Science', 'HR-714', ' Leadership and Motivation', 'MBA', 'III'),
('Management Science', 'HR-703', 'Strategic Human Resource Management', 'MBA', 'III'),
('Management Science', 'MK-711', 'Strategic Marketing', 'MBA', 'II'),
('Management Science', 'MG-712', 'Islamic Management', 'MBA', 'II'),
('Management Science', 'FN-612', 'Financial Reporting Analysis', 'MBA', 'II'),
('Management Science', 'FN-522', 'Financial Management', 'MBA', 'II'),
('Management Science', 'FN-710', 'Micro Finance  ', 'MBA', 'II'),
('Management Science', 'FN-701', 'Financial Modeling', 'MBA', 'II'),
('Management Science', 'MG-603', 'Theory and Practice of Management', 'MBA', 'I'),
('Management Science', 'CS-801', 'Research Methodology', 'MBA', 'I'),
('Management Science', 'MK-511', ' Marketing Management', 'MBA', 'I'),
('Management Science', 'AC-301', 'Financial Accounting', 'MBA', 'I'),
('Management Science', 'MT-522', 'Business Mathematics and Statistics', 'MBA', 'I'),
('Management Science', 'EC-601', 'Business Economics', 'MBA', 'I'),
('Engineering Technology', 'CS-301', 'Introduction To Information And Communication Technology', 'BSCT', 'II'),
('Engineering Technology', 'AR-312', 'Functional Arabic-II', 'BSCT', 'II'),
('Engineering Technology', 'EN-312', 'English – II (Communication Skills)', 'BSCT', 'II'),
('Engineering Technology', 'EC-303', 'Economics', 'BSCT', 'II'),
('Engineering Technology', 'AS-312', 'Differential Equations', 'BSCT', 'II'),
('Engineering Technology', 'CT-307', 'Concrete Technology', 'BSCT', 'II'),
('Engineering Technology', ' MS-303', 'Civil Engineering Drawing', 'BSCT', 'II'),
('Engineering Technology', 'EN-301', 'English-I (English Composition & Comprehension)', 'BSCT', 'I'),
('Engineering Technology', 'CT-304', 'Surveying', 'BSCT', 'I'),
('Engineering Technology', 'CT-305', 'Materials and Methods of Construction', 'BSCT', 'I'),
('Engineering Technology', 'AR-301', 'Functional Arabic-I', 'BSCT', 'I'),
('Engineering Technology', 'AS-302', 'Calculus & Analytical Geometry', 'BSCT', 'I'),
('Engineering Technology', 'PH-301', 'Applied Physics', 'BSCT', 'I'),
('Computer Science', 'MT-403', 'Linear Algebra', 'BSCS', 'III'),
('Computer Science', 'IS-403', 'Islamic Education-I', 'BSCS', 'III'),
('Computer Science', 'HG-305', 'Ideology and Constitution of Pakistan', 'BSCS', 'III'),
('Computer Science', 'EN-421', 'English – III (Technical Report Writing & Presentation Skills)', 'BSCS', 'III'),
('Computer Science', 'CS-306', 'Discrete Structures', 'BSCS', 'III'),
('Computer Science', 'CS-314', 'Data Structure & Algorithm', 'BSCS', 'III'),
('Computer Science', 'CS-501', 'Computer Organization & Assembly Language', 'BSCS', 'III'),
('Computer Science', 'ST-302', 'Probability and Statistics', 'BSCS', 'II'),
('Computer Science', 'CS-315', 'Object Oriented Programming', 'BSCS', 'II'),
('Computer Science', 'AR-312', 'Functional Arabic-II', 'BSCS', 'II'),
('Computer Science', 'EN-312', 'English – II (Communication Skills)', 'BSCS', 'II'),
('Computer Science', 'EC-303', 'Economics', 'BSCS', 'II'),
('Computer Science', 'EL-302', 'Digital Logic Design', 'BSCS', 'II'),
('Computer Science', 'CS-303', 'Programming Fundamentals', 'BSCS', 'I'),
('Computer Science', 'CS-301', 'Introduction to Information Communication Technology', 'BSCS', 'I'),
('Computer Science', 'AR-301', 'Functional Arabic-I', 'BSCS', 'I'),
('Computer Science', 'EN-301', 'English-I (English Composition & Comprehension)', 'BSCS', 'I'),
('Computer Science', 'AS-302', 'Calculus & Analytical Geometry', 'BSCS', 'I'),
('Computer Science', 'PH-301', 'Applied Physics', 'BSCS', 'I');
-- Insert Teachers data
INSERT INTO Teachers (DeptCode, DeptName, TeacherCode, TeacherName, email, Password) VALUES
(105, 'Engineering Technology', 111, 'Shahid Ali', 'shahid.ali@gmail.com', '654321'),
(105, 'Engineering Technology', 110, 'Laiba Munir', 'laiba.munir@gmail.com', '654321'),
(105, 'Engineering Technology', 109, 'Ahmed Faraz', 'ahmed.faraz@gmail.com', '654321'),
(105, 'Engineering Technology', 108, 'Muhammad Usama', 'muhammad.usama@gmail.com', '654321'),
(102, 'Computer Science', 107, 'Syed Zaffar Iqbal', 'syed.zaffar@gmail.com', '654321'),
(102, 'Computer Science', 106, 'Muhammad Imran Khan', 'imran.khan@gmail.com', '654321'),
(102, 'Computer Science', 105, 'Ali Muhammad', 'ali.muhammad@gmail.com', '654321'),
(102, 'Computer Science', 104, 'Qazi Aamir Sohail', 'qazi.aamir@gmail.com', '654321'),
(102, 'Computer Science', 103, 'Raheel Asghar', 'raheel.asghar@gmail.com', '654321'),
(102, 'Computer Science', 102, 'Abdul Wadood', 'abdul.wadood@gmail.com', '654321'),
(102, 'Computer Science', 101, 'Shahab ur Rehman', 'shahab.rehman@gmail.com', '654321'),
(102, 'Computer Science', 112, 'Syed Atta Ur Rehman', 'atta.rehman@aiu.edu', '654321'),
(102, 'Computer Science', 113, 'Syed Ateeq Ullah', 'ateeq.ullah@aiu.edu', '654321'),
(102, 'Computer Science', 114, 'Shaheen Fatima', 'shaheen.fatima@aiu.edu', '654321'),
(105, 'Engineering Technology', 115, 'Shahid Ali', 'shahid.ali@aiu.edu', '654321'),
(105, 'Engineering Technology', 116, 'Muhammad Usama', 'muhammad.usama@aiu.edu', '654321'),
(106, 'Education', 117, 'Shagufta Ambreen', 'shagufta.ambreen@aiu.edu', '654321'),
(106, 'Management Science', 118, 'Laiba Munir', 'laiba.munir@aiu.edu', '654321');


INSERT INTO Schedule(Department, Program, Semester, Subject, TeacherCode,Session) VALUES
('Computer Science', 'BSCS', 'I', 'Applied Physics', 102,'Fall 2024');
INSERT INTO Schedule(Department, Program, Semester, Subject, TeacherCode,Session) VALUES
('Computer Science', 'BSCS', 'II', 'Economics', 102,'Spring 2024');
-- Insert records into Schedule table referencing updated TeacherCode values
INSERT INTO Schedule (Day, Time,Session, Department, Program, Semester, Subject, RoomNo, TeacherCode) VALUES 
('Saturday', '09:00-10:00', 'Fall 2024', 'Computer Science', 'BSCS', 'I', 'Functional Arabic-I', 'C-01', 112),
('Saturday', '09:00-10:00', 'Fall 2024', 'Computer Science', 'BSCS', 'I', 'Functional Arabic-II', 'C-02', 113),
('Saturday', '09:00-10:00', 'Fall 2024', 'Computer Science', 'BSCS', 'III', 'Linear Algebra', 'C-08', 114),
('Saturday', '09:00-10:00', 'Fall 2024', 'Engineering Technology', 'BSCT', 'I', 'Functional Arabic-I', 'C-01', 112),
('Saturday', '09:00-10:00', 'Fall 2024', 'Engineering Technology', 'BSCT', 'I', 'Functional Arabic-II', 'C-02', 113),
('Saturday', '09:00-10:00', 'Fall 2024', 'Education', 'BSEDU', 'I', 'Functional Arabic-I', 'C-01', 112),
('Saturday', '09:00-10:00', 'Fall 2024', 'Management Science', 'MBA', 'I', 'Theory and Practice of Management', 'C-06', 115),
('Saturday', '10:00-11:00', 'Fall 2024', 'Computer Science', 'BSCS', 'I', 'Calculus and Analytical Geometry', 'C-01', 114),
('Saturday', '10:00-11:00', 'Fall 2024', 'Computer Science', 'BSCS', 'II', 'Digital Logic Design', 'C-02', 116),
('Saturday', '10:00-11:00', 'Fall 2024', 'Computer Science', 'BSCS', 'II', 'Islamic Education-I', 'C-05', 113),
('Saturday', '10:00-11:00', 'Fall 2024', 'Engineering Technology', 'BSCT', 'I', 'Calculus and Analytical Geometry', 'C-01', 114),
('Saturday', '10:00-11:00', 'Fall 2024', 'Engineering Technology', 'BSCT', 'II', 'Civil Engineering Drawing', 'C-05', 116),
('Saturday', '10:00-11:00', 'Fall 2024', 'Education', 'BSEDU', 'II', 'Child Development', 'C-06', 117),
('Saturday', '10:00-11:00', 'Fall 2024', 'Management Science', 'MBA', 'I', 'Research Methodology', 'C-07', 118),
('Monday', '09:00-10:00', 'Fall 2024', 'Computer Science', 'BSCS', 'I', 'Applied Physics', 'C-01', 107),
('Monday', '10:00-11:00', 'Fall 2024', 'Computer Science', 'BSCS', 'I', 'Introduction to Information Communication Technology', 'C-02', 106),
('Tuesday', '11:00-12:00', 'Fall 2024', 'Computer Science', 'BSCS', 'II', 'Economics', 'C-03', 104),
('Tuesday', '12:00-01:00', 'Fall 2024', 'Computer Science', 'BSCS', 'II', 'Calculus & Analytical Geometry', 'C-04', 103),
('Wednesday', '09:00-10:00', 'Fall 2024', 'Computer Science', 'BSCS', 'III', 'Discrete Structures', 'C-05', 102),
('Wednesday', '10:00-11:00', 'Fall 2024', 'Computer Science', 'BSCS', 'III', 'Programming Fundamentals', 'C-06', 112),
('Thursday', '11:00-12:00', 'Fall 2024', 'Computer Science', 'BSCS', 'I', 'Functional Arabic-I', 'C-07', 113),
('Thursday', '01:00-02:00', 'Fall 2024', 'Computer Science', 'BSCS', 'II', 'Digital Logic Design', 'C-08', 114),
('Friday', '10:00-11:00', 'Fall 2024', 'Computer Science', 'BSCS', 'III', 'Data Structure & Algorithm', 'C-09', 115);


-- Insert Students data
INSERT INTO Students (RegNumber,SessionID, StudentName, Department, Program, Semester, Password) VALUES
('BSEDU-F-501120/2024',5 ,'Naseeb Ur Rehman', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-501090/2024',5 ,'Waris Khan', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-500940/2024',5 ,'Bakht Muhammad', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-500889/2024',5 ,'Muhammad Alyas', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-500657/2024',5 ,'Muzamil Ahmed', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-500767/2024',5 ,'Manahil Zaheer ', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-500656/2024',5 ,'Zaina Yasir Butt', 'Education', 'BSEDU', 'I', 123456),
('BSEDU-F-500655/2024',5 ,'Ifra Nabeel', 'Education', 'BSEDU', 'I', 123456),
('BED1.5-F-500952/2024',5, 'Mahwish Hafeez', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500736/2024',5, 'Um E Kulsoom', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500735/2024',5, 'Sahrish', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500659/2024',5, 'Maliha Maheen', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500970/2024',5, 'Khansa Khan', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500969/2024',5, 'Faiza Khan', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500885/2024',5, 'Muhammad Zahid ', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500834/2024',5, 'Azizullah', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500780/2024',5, 'Nizam ud Din', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500691/2024',5, 'Muhammad Sajid', 'Education', 'BED1.5', 'I', 123456),
('BED1.5-F-500658/2024',5, 'Nazeer Ahmed', 'Education', 'BED1.5', 'I', 123456),
('BSCT-S-480510/2024',6 ,'Zeeshan Ali', 'Engineering Technology', 'BSCT', 'II', 123456),
('BSCT-S-470112/2024',6 ,'Muhammad Faisal', 'Engineering Technology', 'BSCT', 'II', 123456),
('BSCT-S-470111/2024',6 ,'Maqbool Ahmad', 'Engineering Technology', 'BSCT', 'II', 123456),
('BSCT-S-470110/2024',6 ,'Rana Muhammad Sarfraz', 'Engineering Technology', 'BSCT', 'II', 123456),
('BSCT-S-470109/2024',6 ,'Atta Ullah', 'Engineering Technology', 'BSCT', 'II', 123456),
('BSCT-S-468729/2024',6 ,'Muhammad Subhan', 'Engineering Technology', 'BSCT', 'II', 123456),
('BSCT-F-501092/2024',5 ,'Muhammad Waseem Riaz', 'Engineering Technology', 'BSCT', 'I', 123456),
('BSCT-F-501091/2024',5 ,'Aqib Javid', 'Engineering Technology', 'BSCT', 'I', 123456),
('BSCT-F-500806/2024',5 ,'Muhammad Akhtar', 'Engineering Technology', 'BSCT', 'I', 123456),
('BSCS-S-470471/2024',6 ,'Ubaid Ullah', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470433/2024',6 ,'Zaryab Ahmed', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470432/2024',6 ,'Abdul Rehman', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470375/2024',6 ,'Muhammad Ishaq', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470374/2024',6 ,'Hassan Bin Shahid', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470373/2024',6 ,'Mrastyal Khan', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470314/2024',6 ,'Fahad Ali', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470281/2024',6 ,'Zihan Rhaman', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470280/2024',6 ,'Qazi Noor Muhammad', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470279/2024',6 ,'Muhammad Shahzaib Khan', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470004/2024',6 ,'Naimat Ullah', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-S-470002/2024',6 ,'Abdul Hanan Dhariwal', 'Computer Science', 'BSCS', 'II', 123456),
('BSCS-F-501187/2024',5 ,'Saif Ali Qureshi', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-501164/2024',5 ,'Muhammad Usman Khan', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-501163/2024',5 ,'Bilal Ahmed', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-501162/2024',5 ,'Muhammad Anas Faisal', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-501084/2024',5 ,'Muhammad Azan', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500948/2024',5 ,'Muhammad Sufian ', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500947/2024',5 ,'Muhammad Awais ', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500946/2024',5 ,'Abdul Wahab Khan', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500888/2024',5 ,'Hamdan Aslam', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500737/2024',5 ,'Zaryan Khan', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500732/2024',5 ,'Muhammad Awais', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500730/2024',5 ,'Hammad Nadeem ', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500671/2024',5 ,'Muhammad Huzaifa', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500664/2024',5 ,'Abdullah Bin Tahir', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500663/2024',5 ,'Muhammad Zafar', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500640/2024',5 ,'Fahad ibrahim', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500639/2024',5 ,'Muhammad Bilal Dar', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500583/2024',5 ,'Sameer Ulfat Maseeh', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500880/2024',5 ,'Aqsa Bilal', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500788/2024',5 ,'Bibi Hafsa Gul', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500707/2024',5 ,'Zoha Nadeem', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500641/2024',5 ,'Rukhsar', 'Computer Science', 'BSCS', 'I', 123456),
('BSCS-F-500582/2024',5 ,'Rameen Saleem', 'Computer Science', 'BSCS', 'I', 123456);



INSERT INTO Attendance (AttendanceID, StudentRegNumber, ScheduleID, Date, Status, CreditHours) VALUES
(1, 'BSCS-F-500582/2024', 1, '2024-11-17', 'Present', 1),
(2, 'BSCS-F-500583/2024', 1, '2024-11-17', 'Present', 1),
(3, 'BSCS-F-500639/2024', 1, '2024-11-17', 'Absent', 1),
(4, 'BSCS-F-500640/2024', 1, '2024-11-17', 'Present', 1),
(5, 'BSCS-F-500641/2024', 1, '2024-11-17', 'Leave', 1),
(6, 'BSCS-F-500663/2024', 1, '2024-11-17', 'Present', 1),
(7, 'BSCS-F-500664/2024', 1, '2024-11-17', 'Present', 1),
(8, 'BSCS-F-500671/2024', 1, '2024-11-17', 'Absent', 1),
(9, 'BSCS-F-500707/2024', 1, '2024-11-17', 'Present', 1),
(10, 'BSCS-F-500730/2024', 1, '2024-11-17', 'Present', 1),
(11, 'BSCS-F-500582/2024', 1, '2024-11-17', 'Present', 1),
(12, 'BSCS-F-500583/2024', 1, '2024-11-17', 'Present', 1),
(13, 'BSCS-F-500639/2024', 1, '2024-11-17', 'Present', 1),
(14, 'BSCS-F-500640/2024', 1, '2024-11-17', 'Present', 1),
(15, 'BSCS-F-500641/2024', 1, '2024-11-17', 'Present', 1),
(16, 'BSCS-F-500663/2024', 1, '2024-11-17', 'Present', 1),
(17, 'BSCS-F-500664/2024', 1, '2024-11-17', 'Present', 1),
(18, 'BSCS-F-500671/2024', 1, '2024-11-17', 'Present', 1),
(19, 'BSCS-F-500707/2024', 1, '2024-11-17', 'Present', 1),
(20, 'BSCS-F-500730/2024', 1, '2024-11-17', 'Present', 1),
(21, 'BSCS-F-500732/2024', 1, '2024-11-17', 'Present', 1),
(22, 'BSCS-F-500737/2024', 1, '2024-11-17', 'Present', 1),
(23, 'BSCS-F-500788/2024', 1, '2024-11-17', 'Present', 1),
(24, 'BSCS-F-500880/2024', 1, '2024-11-17', 'Present', 1),
(25, 'BSCS-F-500888/2024', 1, '2024-11-17', 'Absent', 1),
(26, 'BSCS-F-500946/2024', 1, '2024-11-17', 'Present', 1),
(27, 'BSCS-F-500947/2024', 1, '2024-11-17', 'Present', 1),
(28, 'BSCS-F-500948/2024', 1, '2024-11-17', 'Present', 1),
(29, 'BSCS-F-501084/2024', 1, '2024-11-17', 'Present', 1),
(30, 'BSCS-F-501162/2024', 1, '2024-11-17', 'Leave', 1),
(31, 'BSCS-F-501163/2024', 1, '2024-11-17', 'Present', 1),
(32, 'BSCS-F-501164/2024', 1, '2024-11-17', 'Present', 1),
(33, 'BSCS-F-501187/2024', 1, '2024-11-17', 'Absent', 1),
(34, 'BSCS-S-470002/2024', 2, '2024-11-17', 'Present', 1),
(35, 'BSCS-S-470004/2024', 2, '2024-11-17', 'Present', 1),
(36, 'BSCS-S-470279/2024', 2, '2024-11-17', 'Present', 1),
(37, 'BSCS-S-470280/2024', 2, '2024-11-17', 'Present', 1),
(38, 'BSCS-S-470281/2024', 2, '2024-11-17', 'Leave', 1),
(39, 'BSCS-S-470314/2024', 2, '2024-11-17', 'Present', 1),
(40, 'BSCS-S-470373/2024', 2, '2024-11-17', 'Present', 1),
(41, 'BSCS-S-470374/2024', 2, '2024-11-17', 'Leave', 1),
(42, 'BSCS-S-470375/2024', 2, '2024-11-17', 'Present', 1),
(43, 'BSCS-S-470432/2024', 2, '2024-11-17', 'Present', 1),
(44, 'BSCS-S-470002/2024', 2, '2024-11-20', 'Absent', 1),
(45, 'BSCS-S-470004/2024', 2, '2024-11-20', 'Absent', 1),
(46, 'BSCS-S-470279/2024', 2, '2024-11-20', 'Present', 1),
(47, 'BSCS-S-470280/2024', 2, '2024-11-20', 'Present', 1),
(48, 'BSCS-S-470281/2024', 2, '2024-11-20', 'Present', 1),
(49, 'BSCS-S-470314/2024', 2, '2024-11-20', 'Present', 1),
(50, 'BSCS-S-470373/2024', 2, '2024-11-20', 'Present', 1),
(51, 'BSCS-S-470374/2024', 2, '2024-11-20', 'Present', 1),
(52, 'BSCS-S-470375/2024', 2, '2024-11-20', 'Present', 1),
(53, 'BSCS-S-470432/2024', 2, '2024-11-20', 'Present', 1),
(57, 'BSCS-S-470002/2024', 2, '2024-11-19', 'Present', 1),
(58, 'BSCS-S-470004/2024', 2, '2024-11-19', 'Present', 1),
(59, 'BSCS-S-470279/2024', 2, '2024-11-19', 'Present', 1),
(60, 'BSCS-S-470280/2024', 2, '2024-11-19', 'Present', 1),
(61, 'BSCS-S-470281/2024', 2, '2024-11-19', 'Present', 1),
(62, 'BSCS-S-470314/2024', 2, '2024-11-19', 'Present', 1),
(63, 'BSCS-S-470373/2024', 2, '2024-11-19', 'Present', 1),
(64, 'BSCS-S-470374/2024', 2, '2024-11-19', 'Present', 1),
(65, 'BSCS-S-470375/2024', 2, '2024-11-19', 'Present', 1),
(66, 'BSCS-S-470432/2024', 2, '2024-11-19', 'Present', 1),
(67, 'BSCS-F-500582/2024', 1, '2024-11-21', 'Present', 0),
(68, 'BSCS-F-500583/2024', 1, '2024-11-21', 'Present', 0),
(69, 'BSCS-F-500639/2024', 1, '2024-11-21', 'Present', 0),
(70, 'BSCS-F-500640/2024', 1, '2024-11-21', 'Present', 0),
(71, 'BSCS-F-500641/2024', 1, '2024-11-21', 'Present', 0),
(72, 'BSCS-F-500663/2024', 1, '2024-11-21', 'Present', 0),
(73, 'BSCS-F-500664/2024', 1, '2024-11-21', 'Present', 0),
(74, 'BSCS-F-500671/2024', 1, '2024-11-21', 'Present', 0),
(75, 'BSCS-F-500707/2024', 1, '2024-11-21', 'Present', 0),
(76, 'BSCS-F-500730/2024', 1, '2024-11-21', 'Present', 0),
(77, 'BSCS-F-500732/2024', 1, '2024-11-21', 'Present', 0),
(78, 'BSCS-F-500737/2024', 1, '2024-11-21', 'Present', 0),
(79, 'BSCS-F-500788/2024', 1, '2024-11-21', 'Present', 0),
(80, 'BSCS-F-500880/2024', 1, '2024-11-21', 'Present', 0),
(81, 'BSCS-F-500888/2024', 1, '2024-11-21', 'Present', 0),
(82, 'BSCS-F-500946/2024', 1, '2024-11-21', 'Present', 0),
(83, 'BSCS-F-500947/2024', 1, '2024-11-21', 'Present', 0),
(84, 'BSCS-F-500948/2024', 1, '2024-11-21', 'Present', 0),
(85, 'BSCS-F-501084/2024', 1, '2024-11-21', 'Present', 0),
(86, 'BSCS-F-501162/2024', 1, '2024-11-21', 'Present', 0),
(87, 'BSCS-F-501163/2024', 1, '2024-11-21', 'Present', 0),
(88, 'BSCS-F-501164/2024', 1, '2024-11-21', 'Present', 0),
(89, 'BSCS-F-501187/2024', 1, '2024-11-21', 'Present', 0),
(90, 'BSCS-F-500582/2024', 1, '2024-11-21', 'Present', 0),
(91, 'BSCS-F-500583/2024', 1, '2024-11-21', 'Present', 0),
(92, 'BSCS-F-500639/2024', 1, '2024-11-21', 'Present', 0),
(93, 'BSCS-F-500640/2024', 1, '2024-11-21', 'Present', 0),
(94, 'BSCS-F-500641/2024', 1, '2024-11-21', 'Present', 0),
(95, 'BSCS-F-500663/2024', 1, '2024-11-21', 'Present', 0),
(96, 'BSCS-F-500664/2024', 1, '2024-11-21', 'Present', 0),
(97, 'BSCS-F-500671/2024', 1, '2024-11-21', 'Present', 0),
(98, 'BSCS-F-500707/2024', 1, '2024-11-21', 'Present', 0),
(99, 'BSCS-F-500730/2024', 1, '2024-11-21', 'Present', 0),
(100, 'BSCS-F-500732/2024', 1, '2024-11-21', 'Present', 0),
(101, 'BSCS-F-500737/2024', 1, '2024-11-21', 'Present', 0),
(102, 'BSCS-F-500788/2024', 1, '2024-11-21', 'Present', 0),
(103, 'BSCS-F-500880/2024', 1, '2024-11-21', 'Present', 0),
(104, 'BSCS-F-500888/2024', 1, '2024-11-21', 'Present', 0),
(105, 'BSCS-F-500946/2024', 1, '2024-11-21', 'Present', 0),
(106, 'BSCS-F-500947/2024', 1, '2024-11-21', 'Present', 0),
(107, 'BSCS-F-500948/2024', 1, '2024-11-21', 'Present', 0),
(108, 'BSCS-F-501084/2024', 1, '2024-11-21', 'Present', 0),
(109, 'BSCS-F-501162/2024', 1, '2024-11-21', 'Present', 0),
(110, 'BSCS-F-501163/2024', 1, '2024-11-21', 'Present', 0),
(111, 'BSCS-F-501164/2024', 1, '2024-11-21', 'Present', 0),
(112, 'BSCS-F-501187/2024', 1, '2024-11-21', 'Present', 0),
(113, 'BSCS-S-470002/2024', 2, '2024-11-21', 'Present', 0),
(114, 'BSCS-S-470004/2024', 2, '2024-11-21', 'Present', 0),
(115, 'BSCS-S-470279/2024', 2, '2024-11-21', 'Absent', 0),
(116, 'BSCS-S-470280/2024', 2, '2024-11-21', 'Present', 0),
(117, 'BSCS-S-470281/2024', 2, '2024-11-21', 'Present', 0),
(118, 'BSCS-S-470314/2024', 2, '2024-11-21', 'Present', 0),
(119, 'BSCS-S-470373/2024', 2, '2024-11-21', 'Present', 0),
(120, 'BSCS-S-470374/2024', 2, '2024-11-21', 'Present', 0),
(121, 'BSCS-S-470375/2024', 2, '2024-11-21', 'Present', 0),
(122, 'BSCS-S-470432/2024', 2, '2024-11-21', 'Absent', 0),
(123, 'BSCS-S-470433/2024', 2, '2024-11-21', 'Present', 0),
(124, 'BSCS-S-470471/2024', 2, '2024-11-21', 'Present', 0),
(125, 'BSCS-F-500582/2024', 1, '2024-11-22', 'Present', 1),
(126, 'BSCS-F-500583/2024', 1, '2024-11-22', 'Present', 1),
(127, 'BSCS-F-500639/2024', 1, '2024-11-22', 'Present', 1),
(128, 'BSCS-F-500640/2024', 1, '2024-11-22', 'Present', 1),
(129, 'BSCS-F-500641/2024', 1, '2024-11-22', 'Absent', 1),
(130, 'BSCS-F-500663/2024', 1, '2024-11-22', 'Present', 1),
(131, 'BSCS-F-500664/2024', 1, '2024-11-22', 'Present', 1),
(132, 'BSCS-F-500671/2024', 1, '2024-11-22', 'Present', 1),
(133, 'BSCS-F-500707/2024', 1, '2024-11-22', 'Present', 1),
(134, 'BSCS-F-500730/2024', 1, '2024-11-22', 'Present', 1),
(135, 'BSCS-F-500732/2024', 1, '2024-11-22', 'Present', 1),
(136, 'BSCS-F-500737/2024', 1, '2024-11-22', 'Present', 1),
(137, 'BSCS-F-500788/2024', 1, '2024-11-22', 'Present', 1),
(138, 'BSCS-F-500880/2024', 1, '2024-11-22', 'Present', 1),
(139, 'BSCS-F-500888/2024', 1, '2024-11-22', 'Present', 1),
(140, 'BSCS-F-500946/2024', 1, '2024-11-22', 'Present', 1),
(141, 'BSCS-F-500947/2024', 1, '2024-11-22', 'Present', 1),
(142, 'BSCS-F-500948/2024', 1, '2024-11-22', 'Present', 1),
(143, 'BSCS-F-501084/2024', 1, '2024-11-22', 'Present', 1),
(144, 'BSCS-F-501162/2024', 1, '2024-11-22', 'Absent', 1),
(145, 'BSCS-F-501163/2024', 1, '2024-11-22', 'Present', 1),
(146, 'BSCS-F-501164/2024', 1, '2024-11-22', 'Present', 1),
(147, 'BSCS-F-501187/2024', 1, '2024-11-22', 'Present', 1);