CREATE DATABASE student_management ;
use student_management;
CREATE TABLE students ( Id INT NOT NULL AUTO_INCREMENT , 
student_name VARCHAR(100) NOT NULL , 
email VARCHAR(50) NOT NULL ,
 student_number VARCHAR(50) NOT NULL , 
year_of_study INT(10) NOT NULL ,
 batch_name VARCHAR(50) NOT NULL ,
Created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 PRIMARY KEY ('Id'), UNIQUE ('email'), UNIQUE (`student_number`)) ENGINE = InnoDB;