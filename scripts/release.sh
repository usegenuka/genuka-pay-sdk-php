#!/usr/bin/env bash
set -euo pipefail

LATEST=$(git describe --tags --abbrev=0 2>/dev/null || echo "v0.0.0")
BUMP="${1:-patch}"

IFS='.' read -r MAJOR MINOR PATCH <<< "${LATEST#v}"

case "$BUMP" in
  major) MAJOR=$((MAJOR + 1)); MINOR=0; PATCH=0 ;;
  minor) MINOR=$((MINOR + 1)); PATCH=0 ;;
  patch) PATCH=$((PATCH + 1)) ;;
  *)
    echo "Usage: $0 [patch|minor|major]"
    exit 1
    ;;
esac

NEXT="v${MAJOR}.${MINOR}.${PATCH}"

echo "Latest tag : $LATEST"
echo "Next tag   : $NEXT"

git tag -a "$NEXT" -m "Release $NEXT"
echo "✓ Tag $NEXT created. Run: git push origin $NEXT"
