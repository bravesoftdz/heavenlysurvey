Code Exercise
Overview:
--------------------------------------
Create a one page web application that shows a survey question with possible answers.  After the question is answered then results will be displayed.
 
A site visitor who views the survey page will see 1 Survey Question with 2 ... N Survey Answers.
The visitor will only be able to select a single answer for the question.
The visitor will be required to select a single answer before submitting the form.
After form submission the visitor should see other answers that have been submitted in the past.
 
Tech:
--------------------------------------
The application question and its answers should come from a datastore on the server. This could be a simple JSON file, Redis, LevelDB, or any other database (completely up to you).
The application webpage should be served from a web server running Node JS or PHP
The application should use Node.js for the front end
The application should have its visitor answers persist after it is restarted.
The submission and results should be displayed without refreshing the webpage. I.E. using Ajax.
The rendered HTML should be semantic and page should have a good user experience based on best practices.
 
Survey Question Structure
================
id
question text
 
 
Survey Question Answer Structure
================
id
order
answer text
survey question id
 
 
Question Example
--------------------------------------
How many people live in your household?
 
1
2
3
4
More than 4
 
 
Survey Results Example
--------------------------------------
How many people live in your household?
 
1                       ( 5 answered     25% )
2                       ( 0 answered     0%   )
3                       ( 0 answered     0%   )
4                       ( 10 answered   50% )
More than 4      ( 5 answered     25% )
 
 
The examples provided are just here to give you a better idea of what the application will do.  Please take liberties with the visual design.
If you have any questions please reach out for clarification of requirements.  This is not meant to take a long time, but should give us an understanding of how you approach problems.