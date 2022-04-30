### Changes I Made
since submitting my Project Proposal

- I removed TotalPrice column from SnowRemoval table
- Instead, TotalPrice is calculated via the VIEW RemovalsPrices column TotalPrice

**********
    snow_removal_service.sql  <-- SQL script
            snowservice.html  <-- website starting page
    
    notes.md and ToDoList.txt were working files that probably didn't need to be included

# Final Project Assignment

### Requirements
For your final project, you will create a website that uses PHP to access a MySQL database. Your project should include the following:

1. A SQL script that contains
    - a physical implementation for your database (database, tables, constraints, indexes)
    - statements that inserts sample data into your database
    - statements that create a user and grants appropriate access to the user.  This user will access the database in your PHP programs.

2. HTML and CSS documents and PHP scripts used to implement a well-designed and useful website that:
    - displays all of the relevant data, excluding data that isn’t relevant to a user, such as autonumber keys
    - allows the user to search (query) the database in some way
    - allow users to add data to the database and prevent SQL injection attacks
    - allow users to edit or delete data in the database and prevent SQL injection attacks
    - includes any additional web pages that you feel would be useful


### Grading
This project is worth **70 points**.
|Criteria|points|
| -- | -- |
| A SQL script is included that creates the database and tables| 5 |
|Attributes have reasonable data types|7 |
|Primary key constraints are included for all tables|7 |
|Foreign key constraints are included for all relationships including actions for update and delete.|8 |
|Indexes are created for fields frequently queried but not for fields that are primary keys or foreign keys|7 |
|The script inserts sample data into tables|5 |
|PHP script(s) that display all of the relevant data, excluding data that isn’t relevant to a user, such as auto number keys|8 |
|PHP script(s) that filter data based on some criteria|8 |
|PHP script(s) that allow users to add data to the database and prevents SQL injection attacks|8|
|Assignment is submitted on time and via GitHub|7 |

### Assignment Submission

Push all files required to create your database and PHP files to your **GitHub repository**.

```
git add .
git commit -m "completed final project"
git push
```
**Submit the assignment in Blackboard**, including a link to your GitHub repository in the comment section of the assignment.

**This assignment is due no later than 11:59 PM on Tuesday, December 14th.**
