# Verspiel project contribution
## Making issues
When making an issue make sure to go do so by going through the following steps.
- The issue has not already been reported.
- The title sums up the issue with clarity.
- A description of the workflow needed to reproduce the bug. Please try to make sentences, dumping an error message by itself is frowned upon.
## Quality control tools
Before submitting a PR you have to run both commands.
### PHP
Running cs-fixer
```shell
$ vendor/bin/php-cs-fixer fix src --diff --allow-risky=yes
```
### Frontend
Running the linter
```shell
$ yarn lint
```
