# Benford's Law
Simple Laravel console app that calculates if an array of integers follows Benford's Law

## Assumptions
- Benford's Law percentages taken from [Wikipedia](https://en.wikipedia.org/wiki/Benford%27s_law)
  - Percentages are located in the [benfordslaw.php config file](https://github.com/pardamike/benfords-law/blob/main/config/benfordslaw.php#L13-L22)
- The application tests for the distribution of **leading digit only**
- The application does not test for an *exact match* and instead allows for a *slight variance* in the distribution percentage, this is set in the [benfordslaw.php config file](https://github.com/pardamike/benfords-law/blob/main/config/benfordslaw.php#L24C18-L24C18)
  - EX: Benford's Law says all integers with a leading digit of `1` should have a `30.1%` distribution, but if the variance is set to `2` it will allow the  distribution to range from `28.1%` to `32.1%`
- ALL leading digit distributions must be within the variance threshold in order to pass
- Integers must be positive and must be integers (no floats, negatives, etc.)
  - There is *some* minor validations around this
- **Although this is a console app, the logic for determining Benford's Law lives in the [BenfordsLawService.php](https://github.com/pardamike/benfords-law/blob/main/app/Services/BenfordsLawService.php)**
  - The idea is this service class could easily be re-used with an HTTP controller, command, queued job, etc.
  - `BenfordsLawService.php` is provided to the [container as a singleton](https://github.com/pardamike/benfords-law/blob/main/app/Providers/AppServiceProvider.php#L15-L21) (it is assumed the config values would be application-wide)
- The service requires at least 10 integers be passed in, [this is also configurable](https://github.com/pardamike/benfords-law/blob/main/config/benfordslaw.php#L25)

## Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- This application does not have a custom URL and will just run on `localhost`, make sure this is not blocked by another container or modify the [docker-compose.yml](https://github.com/pardamike/benfords-law/blob/main/docker-compose.yml)

## Installation
```
git clone git@github.com:pardamike/benfords-law.git
cd benfords-law
cp .env.example .env
docker compose up -d
```

## Console Command Usage
To use, simply call the `benfords-law:run` Artisan command and pass in a comma or space separated list of integers
```
docker exec benfords-law-laravel.test-1 php artisan benfords-law:run <LIST OF INTEGERS>
```

## Running Tests
There are some basic unit tests for the `BenfordsLawService.php` class, to run them, simply run:
```
docker exec benfords-law-laravel.test-1 php artisan test
```

## Example Console Output
**Success**
```
SUCCESS: This data set follows Benfords Law!
+-----+------+-------+-------------+-------------------------+
|  n  | Freq | Pct   | Benford Pct | Within Variance (+/-2%) |
+-----+------+-------+-------------+-------------------------+
| 1   | 960  | 30.54 | 30.1        | Yes                     |
| 2   | 580  | 18.45 | 17.6        | Yes                     |
| 3   | 381  | 12.12 | 12.5        | Yes                     |
| 4   | 297  | 9.45  | 9.7         | Yes                     |
| 5   | 234  | 7.45  | 7.9         | Yes                     |
| 6   | 203  | 6.46  | 6.7         | Yes                     |
| 7   | 173  | 5.50  | 5.8         | Yes                     |
| 8   | 163  | 5.19  | 5.1         | Yes                     |
| 9   | 152  | 4.84  | 4.6         | Yes                     |
+-----+------+-------+-------------+-------------------------+
```

**Failure**
```
FAIL: Distribution of one or more numbers fall below Benfords Law - using variance threshold of 2%
+-----+------+-------+-------------+-------------------------+
|  n  | Freq | Pct   | Benford Pct | Within Variance (+/-2%) |
+-----+------+-------+-------------+-------------------------+
| 1   | 344  | 10.90 | 30.1        | No                      |
| 2   | 356  | 11.28 | 17.6        | No                      |
| 3   | 337  | 10.68 | 12.5        | Yes                     |
| 4   | 353  | 11.19 | 9.7         | Yes                     |
| 5   | 350  | 11.09 | 7.9         | No                      |
| 6   | 354  | 11.22 | 6.7         | No                      |
| 7   | 369  | 11.69 | 5.8         | No                      |
| 8   | 333  | 10.55 | 5.1         | No                      |
| 9   | 360  | 11.41 | 4.6         | No                      |
+-----+------+-------+-------------+-------------------------+
```

