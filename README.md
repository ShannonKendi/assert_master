##  ASSERT

<p align="center">


## About Assert

Protesting is a fundamental right to every citizen. Under article 37 of the Kenyan constitution, every person has the right, peaceably and unarmed, to assemble, to demonstrate, to picket, and to present petitions to public authorities. It is also the work of anyone who plans to protest in Kenya to notify the police and ensure that participants are peaceful. The protests being held today have tendencies of turning violent and leading to the interruptions of the daily work schedules of individuals. The protest application, Assert, makes it easier for a planner to organize his protest and individuals to join the protests and follow the guidelines of having a peaceful protest and a way to tackle emergencies when needed. With Assert, the vision of peaceful and impactful protests can become a reality, furthering the ideals of justice, equality, and freedom in society.


Setting up the laravel backend
===============================

I suggest you make a new directory to store all this work(ie.new folder -> ASSERT)
Run these lines of code in the terminal directory you want to save your works
	git 
		- this should clone the repository to your local machine
		.

cd assert_laravel
		-to enter this directory
	
	composer install
	
	code .
		- This opens vscode in the current directory
		-there's a file called `.env.example` , rename it to `.env`
		-open the new `.env` file and change `DB_DATABASE=laravel` to `DB_DATABASE=assert`
	
	(Open the terminal in the same directory or in the vscode window you opened the project)
	php artisan key:generate
	
	php artisan serve
Setting Up the react frontend
==============================

I suggest you clone this in the same directory you cloned 
	git
	
	cd assert_react
		- to make sure you're in the assert directory

-(You need to install node js for this to work)
	npm install
	
	npm run dev
		-launches the app
	



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


