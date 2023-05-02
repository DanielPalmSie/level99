## Getting Started

To start the project, run the start.sh script which is in the root of the project.
It will start the project, install all dependencies, and at the end, you should see in the logs that the project has been started successfully.


## Endpoint Calls

The order of the endpoint calls is as follows:

1. Endpoint to generate team tables: http://localhost:80/api/generate-team-tables POST
2. Endpoint to generate games between teams in two divisions: http://localhost:80/api/generate-games-tables POST
3. Endpoint to generate playoff games using a tree structure: http://localhost:80/api/generate-playoff-tables POST

## Running Tests

To run tests, execute the following command in the PHP container: `php bin/phpunit`.

To enter the PHP container, execute the following command: `docker exec -it <your container name> --bash`.
