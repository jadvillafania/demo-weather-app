# Fullstack Challenge

## Instructions

Using Laravel and VueJS, create an application which shows the weather for a set of users.

- Clone this repository.
- Once completed, send a link of the clone repository to interviewer and let them know how long the exercise took.
- Update the frontend landing page to show a list of users and their current weather.
- Clicking a user opens a modal or screen which shows that users detailed weather report.
- Weather update should be no older than 1 hour.
- Internal API request(s) to retrieve weather data should take no longer than 500ms. Consider that external APIs could and will take longer than this from time to time and should be accounted for.
- We are looking for attention to detail!
- Instructions are purposely left somewhat open-ended to allow the developer to make some of their own decisions on implementation and design.
- This is not a designer test so the frontend does not have to look "good", but of course bonus points if you can make it look appealing.

## Things to consider:

- Chose your own weather api such as https://openweathermap.org/api or https://www.weather.gov/documentation/services-web-api.
- Testability.
- Best practices.
- Design patterns.
- Availability of external APIs is not guaranteed and should not cause page to crash.
- Twenty randomized users are added via the seeder process, each having their own unique location (longitude and latitude).
- Redis is available (Docker service) if you wish to use it.
- Queues, workers, websockets could be useful.
- Feel free to use a frontend UI library such as PrimeVue, Vuetify, Bootstrap, Tailwind, etc.
- Anything else you want to do to show off your coding chops!

## To run the local dev environment:

### API

- Navigate to `/api` folder
- Ensure version docker installed is active on host
- Copy .env.example: `cp .env.example .env`
- Start docker containers `docker compose up` (add `-d` to run detached)
- Connect to container to run commands: `docker exec -it app bash`
  - Make sure you are in the `/var/www/html` path
  - Install php dependencies: `composer install`
  - Setup app key: `php artisan key:generate`
  - Migrate database: `php artisan migrate`
  - Seed database: `php artisan db:seed`
  - Run tests: `php artisan test`
- Visit api: `http://localhost`

### Frontend

- Navigate to `/frontend` folder
- Ensure nodejs v18 is active on host
- Install javascript dependencies: `npm install`
- Run frontend: `npm run dev`
- Visit frontend: `http://localhost:5173`

### Work done

#### Backend

- Created a `WeatherService` that fetches data from external APIs
  - `.env.example` has API keys and URLS
- Created a scheduled command to check old weather data and update if necessary
  - The command is can be run on termial using `php artisan weather:updatedata`
  - This will dispacth a queued job for each weather data that needs to be updated

#### Docker

- Added container names on the `docker-compose.yml`
- Added opcache support for increased performance
- Added a bash script `start-container` to change the ownership to `www:data` to prevent log file permission errors
- Added a new `supervisor` container to manage queues and scheduled tasks
  - supervisor configuration is on `supervisor.conf`

#### Frontend

- Landing page displays list of users as cards with pagination
  - Changing pagination parameters will cancel the current fetch request
- Clicking on user cards will open a modal to view more weather information
- Users are managed by `UserStore`
- Responsive minimalist design
- Used Vuetify with tree-shaking enabled

#### Testing

- Created several test scripts on `WeatherServiceTest`
