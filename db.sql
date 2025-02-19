-- Create database
DROP DATABASE IF EXISTS quiz_system;
CREATE DATABASE quiz_system;
USE quiz_system;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(50) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create quizzes table
CREATE TABLE quizzes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    course_code VARCHAR(50) NOT NULL,
    duration INT NOT NULL,
    total_questions INT NOT NULL,
    exam_key VARCHAR(100) NOT NULL,
    status ENUM('open', 'closed') DEFAULT 'open'
);

-- Create questions table
CREATE TABLE quiz_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quiz_id INT,
    question_text TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_answer CHAR(1) NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Create results table
CREATE TABLE quiz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    quiz_id INT,
    score INT,
    total_questions INT,
    submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Insert admin user
INSERT INTO users (name, email, password, is_admin) VALUES 
('Admin', 'admin', 'admin123', TRUE);

-- Insert sample quizzes
INSERT INTO quizzes (title, course_code, duration, total_questions, exam_key, status) VALUES
('TypeScript Fundamentals', 'TS101', 40, 20, 'TS101KEY', 'open'),
('Learn NextJS 15', 'WEBAI101', 40, 20, 'NEXTJS15KEY', 'open'),
('JavaScript Basics', 'JS101', 30, 15, 'JS101KEY', 'open'),
('PHP Development', 'PHP101', 45, 25, 'PHP101KEY', 'open');

-- Insert sample questions for TypeScript quiz
INSERT INTO quiz_questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(1, 'What is TypeScript?', 'A programming language', 'A JavaScript framework', 'A superset of JavaScript', 'A database', 'c'),
(1, 'Which symbol is used for type annotation in TypeScript?', ':', ';', '->', '=>', 'a'),
(1, 'What is the file extension for TypeScript files?', '.ts', '.tsx', '.typescript', '.type', 'a'),
(1, 'Which of these is a TypeScript-specific feature?', 'Loops', 'Arrays', 'Type Inference', 'Console.log', 'c'),
(1, 'What is an interface in TypeScript?', 'A class', 'A type definition', 'A function', 'A variable', 'b');

-- Insert sample questions for NextJS quiz
INSERT INTO quiz_questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(2, 'What is Next.js?', 'A database', 'A React framework', 'A programming language', 'A styling library', 'b'),
(2, 'Which routing system does Next.js use?', 'File-based', 'Configuration-based', 'Manual routing', 'No routing', 'a'),
(2, 'What is the purpose of getStaticProps?', 'Client-side data fetching', 'Static site generation', 'API routes', 'Styling', 'b'),
(2, 'Which folder contains API routes in Next.js?', 'api', 'routes', 'endpoints', 'services', 'a'),
(2, 'What is Server-Side Rendering in Next.js?', 'Client rendering', 'Static rendering', 'Dynamic rendering', 'Server rendering', 'd');

-- Insert sample questions for JavaScript quiz
INSERT INTO quiz_questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(3, 'What is JavaScript?', 'Markup language', 'Programming language', 'Database', 'Operating System', 'b'),
(3, 'Which keyword declares a variable in JavaScript?', 'var', 'string', 'declare', 'variable', 'a'),
(3, 'What is an array in JavaScript?', 'A function', 'A loop', 'A collection of items', 'A condition', 'c'),
(3, 'How do you write a comment in JavaScript?', '//', '<!--', '/**/', '#', 'a'),
(3, 'What is the === operator in JavaScript?', 'Assignment', 'Strict equality', 'Addition', 'OR', 'b');

-- Insert sample questions for PHP quiz
INSERT INTO quiz_questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer) VALUES
(4, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Program High Performance', 'Public Host Program', 'b'),
(4, 'How do you start a PHP block?', '<?php', '<php>', '<script php>', '<p>', 'a'),
(4, 'Which symbol is used before variables in PHP?', '#', '$', '@', '&', 'b'),
(4, 'Which function prints output in PHP?', 'console.log()', 'print()', 'echo', 'write()', 'c'),
(4, 'What is the correct way to end a PHP statement?', '.', ';', ':', '!', 'b');

-- Insert sample users
INSERT INTO users (name, email, password) VALUES
('John Doe', 'john@example.com', 'john123'),
('Jane Smith', 'jane@example.com', 'jane123'),
('Bob Wilson', 'bob@example.com', 'bob123');

-- Insert sample quiz results
INSERT INTO quiz_results (user_id, quiz_id, score, total_questions) VALUES
(2, 1, 18, 20),
(2, 2, 15, 20),
(3, 1, 16, 20),
(4, 3, 12, 15);