# Benford's Law
Simple Laravel console app that calculates if an array of integers follows Benford's Law

## Assumptions
- Benford's Law percentages taken from [Wikipedia](https://en.wikipedia.org/wiki/Benford%27s_law)
- The application tests for the distribution of **leading digit only**
- The application does not test for an *exact match* and instead allows for a *slight variance* in the distribution percentage, this is set in the [benford.php config file]()
  - EX: Benford's Law says all integers with a leading digit of `1` should have a `30.1%` distribution, but if the variance is set to `2` it will allow the  distribution to range from `28.1%` to `32.1%`
- ALL leading digit distributions must be within the variance threshold in order to pass
- Integers must be positive and must be integers (no floats, negatives, etc.)
  - There is *some* minor validations around this
- **Although this is a console app, the logic for determining Benford's Law lives in the [BenfordsLawService.php]()**
  - The idea is this service class could easily be re-used with an HTTP controller, command, queued job, etc.
  - `BenfordsLawService.php` is provided to the [container as a singleton]() (it is assumed the config values would be application-wide)
- The service requires at least 10 integers be pass in, [this is also configurable]()

## Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- This application does not have a custom URL and will just run on `localhost`, make sure this is not blocked by another container or modify the [docker-compose.yml]()

## Installation
```
git clone LINK
cd Benford's-law
cp .env.example .env
docker compose up -d
```

## Console Command Usage
To use, simply call the `Benford's-law:run` Artisan command and pass in a comma or space separated list of integers
```
docker exec Benford's-law-laravel.test-1 php artisan Benford's-law:run <LIST OF INTEGERS>
```

## Running Tests
There are some basic unit tests for the `BenfordsLawService.php` class, to run them, simply run:
```
docker exec Benford's-law-laravel.test-1 php artisan test
```

## Example Output
Success

Failure