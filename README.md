# ExamPoll
PHP poll for choosing the subject of the exam party. (#Nehalennia)<br>

## Features
To ensure that nobody can vote multiple times, every participating student gets sent a unique code with which they can vote. As soon as someone's vote is cast, the code gets removed from the database.<br>
This is an anonymous poll; without changing the code or logging something spooky, you can't see who voted for which option.<br>

## Code
The code for this project is organised as follows:
### Shell scripts
The codes need to be generated (generate_codes.sh), imported into the MySQL database (mysql_import.sh) and sent to the students via Itslearning (mass_itslearning_mail.sh), using the addresses specified in students.txt.
### PHP
The options on which people can vote, are specified in the options file (options.php). The order of these options should not be changed after people have voted on the poll, because the order is critical for how the votes are stored in the database.<br>
When someone visits the poll page (poll.php), actions will be taken depending on what data the browser supplied. Any attempts to vote get passed to the validation code (Vote_Validator.php). If someone votes with a correct vote code, the vote will be put into the database, using the MySQL connection with access data from the configuration file (config.php).<br>
There is a results page (results.php), accessible only to the people who have the password to it.
### Database
The MySQL database consists of two tables: <i>codes</i> and <i>votes</i>. The <i>codes</i> table contains the remaining valid vote codes, in VARCHAR(32) format. The <i>votes</i> table contains the IDs of the poll options in TINYINT format, in the order in which they are encountered in the options file, and their vote count, in INT format.<br>
These tables will be created automatically by the PHP scripts; the database, however, should be created manually.

## Requirements
<ul>
 <li>Node.js</li>
 <li>MySQL Server & Client</li>
 <li>Apache webserver</li>
 <li>A non-Windows-OS</li>
</ul>
