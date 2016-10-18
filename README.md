# Transport-Tracking-System

This project provides a system that comes to the aid of a traveler by giving direct information about the location of vehicles. It is specifically implemented on the bus route from the center of Tirana to Epoka University. 

The idea for this project came from the daily struggles of students to get to campus on time. Especially during exams, there was a flux of students, and the bus timetable was not providing sufficient information on the buses or traffic. So we decided to think of a new method to inform the students. This system documents the *paths* (_in blue_), *stations* (_in white_), and *vehicles* (_in brown_) by using the Google Maps Api.

It works by getting the latitude and longitude coordinates from the busses, in a timeframe of every 2 seconds, then updates this to visualize in the map view. 
The implementation uses PDO extensions to provide a data-access abstraction layer, which means that regardless of which database used, you use the same functions to issue queries and fetch data. After the system fetches the bus or station coordinates it evaluates them to JavaScript Objects. The JSON format is syntactically identical to the code for creating JavaScript objects. Because of this similarity, instead of using a parser (like XML does), a JavaScript program uses standard JavaScript functions to convert JSON data into native JavaScript objects. 

Although not officially in use anymore, the project has saved the time of many students and was accessed [here](http://stud-proj.epoka.edu.al/~edoko/bus/).
