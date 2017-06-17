#!/bin/bash
cd "$(dirname "$0")"

CORE="$(dirname "$0")"

echo "git push all repos"
find . -iname ".git" -type d -exec sh -c "cd $CORE && cd {} && cd .. && pwd && git add -A . && git commit -am "sync" && git push" \;
