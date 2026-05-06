#!/usr/bin/env bash

set -euo pipefail

script_dir="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
output="$script_dir/alpha"

compiler_flags=(
  -g
  -O0
  -fno-stack-protector
  -no-pie
  -z execstack
)

if command -v setarch >/dev/null 2>&1; then
  setarch "$(uname -m)" -R bash -lc "gcc ${compiler_flags[*]} -o '$output' '$script_dir/alpha.c'"
else
  gcc "${compiler_flags[@]}" -o "$output" "$script_dir/alpha.c"
fi

echo "[+] Built $output"