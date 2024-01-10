# About
The project is an school assignment in the Yrgo education for web dev. We are supposed to build a operational website for an fictive hotel in an fictiv archepelago. The hotel gets more "stars" the higher standards the hotel have. The more stars the more the hotel manager can charge for the rooms I guess. In the end all the students gets 100 bucks and for a given time period we are gonna "stay" at the others hotels, while the others stays at mine hotel. In the end we count wich student have the most money on their account ( i.e the hotel that have the mosts guests)
# Island name
Lagoon Luster Cove
# Hotel name
Hotel Avalon
-Where Paradise Meets Perfection
# features
-massage
-underground hotsprings
-bedtime storyteller
# stars
atm its a 5 star hotel, (I think)
-We have graphical presentation of book avability in the calender. 1 star
-we give discount, 10% each day over 3 days. 2 stars
-we have 3 features. 3 stars
-we have an adminpage where the manager can change the pricing of the rooms and features. 4 stars
-we are fetching an random hotel gif from giphy's API and sending that gif with the response on succeful booking, In other words: We are using external data to generate a response on succeful booking. 5 stars
# Instructions
No installation needed. The Hotel operates online. You only get your transfercode from the centralbank for the right amount and then use it to book your room. You can see in the calender if an certain room is already booked on a certain date. So dont try and doublebook becouse it is not possbile anyways. 
The only tricky part is that you have to lock in your arrival-date before you are able to pick an departure-date if you are using the calender to pick dates. Otherwise when you click on a date you'll only altering the arrival-date. But i wrote an FAQ in a pop-up to explain this to the user, so hopefully it will be all-right. 

In the vacations folder I have set up a script that automatic takes the loggbook and insert the content into an DB. Later I fetch the information from that DB and present it visually in a table. The idea is to incorporate this into the admin page perhaps, or mayby let it be freestanding. 
# Database
I use a sqlite database. Its simple but gets the job done. For more specific information about the db structure you can check out the database folder where I keep the db config file. It handles the information about the rooms and the features along with the pricing of those. That gives me the oppurtonity to via the admin page change the values in the db. And becouse I fetch the information about the rooms and features from the db in the html code it means that if I change the price from the admin the page the website automatic changes the price on the website and all the calculations also. Because they are all based of the value in the db.

I also have a small indipendent DB for my own vacation loggbok. I use that to visually present my loggbok. 
# Admin page
I made an admin page that the hotel manager can log into using my API-key and a password that is stored in the dotenv file. In the Admin page section the manager can alter the price of all the rooms and all the features. This is done via prepared pdo statements wich alter the actual DB values. The website and all calculations gets the pricing also directly from the DB so there is no chance of something going wrong, like for example if I change the room price and the JS that is showing the user the price BEFORE booking wouldnt show the new price. 

I also made a script that fetch all the guests that have or are gonna stay at the hotel. When an booking occours that transfercode and the amount along with some other info are stored in a file called validation response. This is the actual validation from the centralbank that we get when we validate the users transfercode. This way I dont have to start up vs code to check on my guests, how many they have been, I can just log into the adminpage. It is also useful to check if the sum of money I SHOULD have on my account based on number of guests and bookings are true to whats actuall on my account. 
# Other
I tried to structure the project the best I could. At first I tried to make use of an autoloader but ran into problem when deploying the site. For some reason the relative paths I was using was not working on the deployed site and I had to manually change ALL paths in the entire project on the deployed site. And in all this mess I decided to not use autoloader becouse it was giving me more trouble then it was worth. 
I should also mention some of the names of variables and files might not make much sence if you are not aware of certain things. Like for example the files "post-hero" and "pre-hero". post-hero was named ironcily since its really a "pre-hero" but since I had already made an "pre-hero" section that I later decided to move down to after the actual hero, And since its was the very last thing I added to the site I named it Post in a bad internal joke kind of thing. But anyways, the structure is kinda straight forward. i Have seperated all css into its own files. So the header, navbar, footer etc all have its own css file to make it more managable. The same goes for JS scripts. All the composer nad guzzle files I dared not touch, so they are staying where they "landed". 

I also had an idea early on on some of the things I wanted to do, but I never got around on finishing up on thoose ideas. As for example I have a bunch of functions ready to go to be used for an user to fetch their bookings from the DB. But when push comes to shove I figured it would require me to make some sort of login process for the user and truth be told I kinda forgot about it until now at the very end when I was cleaning up dead code and stuff. 

I also decided to automate the procces of depositing the money. So whenever a booking succefully validates the transfercode, the script immidiatly deposites the transfercode into my account. I figured that if I would have kept it and done it manually i would probably forget or something. 

# Code review

1. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
2. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
3. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
4. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
5. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
6. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
7. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
8. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
9. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
10. example.js:10-15 - Remember to think about X and this could be refactored using the amazing Y function.
