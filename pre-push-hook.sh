#!/bin/sh

echo "pre-commit hook..."

ROOT="$(git rev-parse --show-toplevel)"
PHP_CS_FIXER="$ROOT/vendor/bin/php-cs-fixer"

echo -e "\tdirty code check..."
DIRTY_DUMP="$(grep -rn '[^\w\d_-]dump(' src tests)"
EXIT_STATUS=$?
if [ $EXIT_STATUS -eq 0 ]; then
  echo -e "\tSymfony dump() function found. Please remove and commit again."
  echo "$DIRTY_DUMP"
  exit 1
fi
echo -e "\tdone."

echo -e "\tphp-cs-fixer..."
if [ -x $PHP_CS_FIXER ]; then
  $PHP_CS_FIXER --version
  git status --porcelain | grep -e '^[AM]\(.*\).php$' | sed -e 's/^[AM]\s*//' |
  while read line; do
    $PHP_CS_FIXER fix --config=$ROOT/.php-cs-fixer.dist.php --quiet "$line"
    git add "$line"
  done
else
  echo "Please check if you have installed the project dependencies with:"
  echo -e "\tcomposer install"
  echo "Or install a local php-cs-fixer:"
  echo -e "\tcomposer require --dev friends-of-php/php-cs-fixer"
  exit 1
fi
echo -e "\tdone."

echo "pre-commit hook done."
