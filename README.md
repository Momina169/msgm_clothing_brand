 How to Run the Project and Access Admin Panel

Steps to Run the Project Locally:
    Download the code from github, first rename ".env.example" file to ".env".
	Start Apache and MySQL services from XAMPP control panel.
	Open the project folder named msgm.
	Inside the msgm folder, open Command Prompt (CMD).
	Run the following command to migrate and seed the database:
		php artisan migrate:fresh –seed
	After that, start the development server by running: 
		php artisan serve
	The project will now open in your default browser at: http://127.0.0.1:8000


How to Access the Admin Panel:
	Open your browser and go to: http://127.0.0.1:8000/admin/dashboard
	The Admin Login Page will appear for authentication.
	Enter the following credentials:
		Email: devmomina@gmail.com
		Password: password
	Upon successful login, you will be redirected to the Admin Dashboard.
