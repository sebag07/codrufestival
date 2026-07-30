#!/usr/bin/env bash
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
HOOKS_DIR="$REPO_ROOT/.githooks"
GIT_HOOKS_DIR="$REPO_ROOT/.git/hooks"

if [[ ! -d "$GIT_HOOKS_DIR" ]]; then
  echo "install-git-hooks: .git/hooks not found. Run this from a git checkout." >&2
  exit 1
fi

if [[ ! -f "$HOOKS_DIR/pre-commit" ]]; then
  echo "install-git-hooks: missing $HOOKS_DIR/pre-commit" >&2
  exit 1
fi

chmod +x "$HOOKS_DIR/pre-commit"
ln -sf "../../.githooks/pre-commit" "$GIT_HOOKS_DIR/pre-commit"

echo "Installed pre-commit hook from .githooks/pre-commit"
